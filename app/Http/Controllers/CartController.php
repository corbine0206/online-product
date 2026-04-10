<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::guard('web')->id();
        $cartItems = [];
        $total = 0;

        if ($userId) {
            $dbCartItems = CartItem::where('user_id', $userId)->with('product')->get();
            
            foreach ($dbCartItems as $item) {
                if ($item->product) {
                    $cartItems[] = [
                        'product' => $item->product,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal
                    ];
                    $total += $item->subtotal;
                }
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $userId = Auth::guard('web')->id();
        
        // Redirect to login if user is not authenticated
        if (!$userId) {
            return response()->json([
                'success' => false,
                'redirect' => route('login'),
                'message' => 'Please login to add items to cart'
            ], 401);
        }

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::find($productId);
        if (!$product || $product->stock < $quantity) {
            return response()->json(['error' => 'Product not available or insufficient stock'], 400);
        }

        $existingCartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->updateQuantity($existingCartItem->quantity + $quantity);
            $cartCount = CartItem::where('user_id', $userId)->sum('quantity');
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $quantity,
            ]);
            $cartCount = CartItem::where('user_id', $userId)->sum('quantity');
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $userId = Auth::guard('web')->id();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        if (!$userId) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Product not in cart'], 404);
        }

        $product = Product::find($productId);
        if ($product && $product->stock >= $quantity) {
            $cartItem->updateQuantity($quantity);
            $cartCount = CartItem::where('user_id', $userId)->sum('quantity');

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount
            ]);
        }

        return response()->json(['error' => 'Insufficient stock'], 400);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = Auth::guard('web')->id();
        $productId = $request->input('product_id');

        if (!$userId) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
            $cartCount = CartItem::where('user_id', $userId)->sum('quantity');

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount
            ]);
        }

        return response()->json(['error' => 'Product not in cart'], 404);
    }

    public function clear()
    {
        $userId = Auth::guard('web')->id();
        if ($userId) {
            CartItem::where('user_id', $userId)->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
}
