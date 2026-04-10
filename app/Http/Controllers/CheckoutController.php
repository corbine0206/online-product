<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\SalesTransactionItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::guard('web')->id();
        
        $dbCartItems = CartItem::where('user_id', $userId)->with('product')->get();
        
        if ($dbCartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $total = 0;
        $cartItems = [];

        foreach ($dbCartItems as $cartItem) {
            $product = $cartItem->product;
            if ($product && $product->stock >= $cartItem->quantity) {
                $total += $cartItem->subtotal;
                
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->subtotal
                ];
            }
        }

        $customer = Auth::guard('web')->user();

        return view('checkout.index', compact('cartItems', 'total', 'customer'));
    }

    public function process(Request $request)
    {
        $userId = Auth::guard('web')->id();
        $dbCartItems = CartItem::where('user_id', $userId)->with('product')->get();
        
        if ($dbCartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $total = 0;
        $lineItems = [];

        foreach ($dbCartItems as $cartItem) {
            $product = $cartItem->product;
            if ($product && $product->stock >= $cartItem->quantity) {
                $total += $cartItem->subtotal;
                
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product->name,
                            'description' => $product->description ?? '',
                        ],
                        'unit_amount' => $product->price * 100, // Convert to cents
                    ],
                    'quantity' => $cartItem->quantity,
                ];
            }
        }

        if (empty($lineItems)) {
            return redirect()->route('cart.index')->with('error', 'Some items in your cart are no longer available');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cart.index'),
                'metadata' => [
                    'user_id' => $userId,
                ],
            ]);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Payment setup failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        \Log::info('Checkout success called', ['query_params' => $request->all()]);
        
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            \Log::error('No session_id provided');
            return redirect()->route('cart.index')->with('error', 'Invalid session');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve($sessionId);
            
            \Log::info('Stripe session retrieved', ['session_id' => $sessionId, 'payment_status' => $session->payment_status]);
            
            if ($session->payment_status !== 'paid') {
                \Log::error('Payment not completed', ['payment_status' => $session->payment_status]);
                return redirect()->route('cart.index')->with('error', 'Payment not completed');
            }

            $userId = $session->metadata['user_id'] ?? null;
            \Log::info('User ID from metadata', ['user_id' => $userId]);
            
            if (!$userId) {
                \Log::error('No user_id in session metadata');
                return redirect()->route('cart.index')->with('error', 'Invalid user data');
            }
            
            $dbCartItems = CartItem::where('user_id', $userId)->with('product')->get();
            \Log::info('Cart items retrieved', ['count' => $dbCartItems->count()]);
            
            if ($dbCartItems->isEmpty()) {
                \Log::error('Cart is empty after payment');
                return redirect()->route('cart.index')->with('error', 'Cart is empty');
            }

            // Create sales transaction
            $user = Auth::guard('web')->user();
            $customerId = $user->customer->id ?? null;
            
            \Log::info('Customer data', ['user_id' => $user->id, 'customer_id' => $customerId]);
            
            if (!$customerId) {
                \Log::info('Creating customer profile');
                try {
                    // Create customer profile from Stripe session data
                    $customer = Customer::create([
                        'user_id' => $user->id,
                        'first_name' => $user->first_name ?? 'Customer',
                        'last_name' => $user->last_name ?? '',
                        'email' => $session->customer_details->email ?? $user->email,
                        'phone' => $session->customer_details->phone ?? '',
                        'address' => $session->customer_details->address->line1 ?? '',
                        'city' => $session->customer_details->address->city ?? '',
                        'state' => $session->customer_details->address->state ?? '',
                        'postal_code' => $session->customer_details->address->postal_code ?? '',
                        'country' => $session->customer_details->address->country ?? 'US',
                        'status' => 'active',
                    ]);
                    $customerId = $customer->id;
                    \Log::info('Customer created', ['customer_id' => $customerId]);
                } catch (\Exception $e) {
                    \Log::error('Error creating customer', ['message' => $e->getMessage()]);
                    // Create basic customer if Stripe data fails
                    $customer = Customer::create([
                        'user_id' => $user->id,
                        'first_name' => $user->first_name ?? 'Customer',
                        'last_name' => $user->last_name ?? '',
                        'email' => $user->email,
                        'status' => 'active',
                    ]);
                    $customerId = $customer->id;
                    \Log::info('Basic customer created', ['customer_id' => $customerId]);
                }
            }
            
            $total = $session->amount_total / 100; // Convert from cents to dollars
            
            \Log::info('Creating sales transaction', ['customer_id' => $customerId, 'total' => $total]);
            
            $transaction = SalesTransaction::create([
                'customer_id' => $customerId,
                'transaction_number' => SalesTransaction::generateTransactionNumber(),
                'subtotal' => $session->amount_subtotal / 100,
                'tax_amount' => $session->total_details->amount_tax / 100,
                'discount_amount' => $session->total_details->amount_discount / 100,
                'total_amount' => $total,
                'payment_method' => 'stripe',
                'payment_status' => 'paid',
                'status' => 'completed',
                'notes' => 'Stripe Checkout Session: ' . $sessionId,
                'transaction_date' => now(),
            ]);

            \Log::info('Transaction created', ['transaction_id' => $transaction->id, 'transaction_number' => $transaction->transaction_number]);

            // Create sales transaction items and update product stock
            foreach ($dbCartItems as $cartItem) {
                \Log::info('Creating transaction item', [
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal
                ]);
                
                $transactionItem = SalesTransactionItem::create([
                    'sales_transaction_id' => $transaction->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);

                \Log::info('Transaction item created', ['transaction_item_id' => $transactionItem->id]);

                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            \Log::info('Transaction items created and stock updated');

            // Clear cart
            CartItem::where('user_id', $userId)->delete();

            \Log::info('Cart cleared', ['user_id' => $userId]);
            return view('checkout.success', compact('transaction'));
        } catch (\Exception $e) {
            \Log::error('Checkout success error', ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
            return redirect()->route('cart.index')->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }
}
