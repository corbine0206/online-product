<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'birth_date',
        'status',
        'total_purchases',
        'last_purchase_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'total_purchases' => 'decimal:2',
        'last_purchase_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salesTransactions(): HasMany
    {
        return $this->hasMany(SalesTransaction::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAuthIdentifier()
    {
        return $this->user->getAuthIdentifier();
    }

    public function getAuthPassword()
    {
        return $this->user->getAuthPassword();
    }
}
