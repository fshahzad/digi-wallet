<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'commission_fee',
        'sender_balance_before',
        'sender_balance_after',
        'receiver_balance_before',
        'receiver_balance_after',
        'status',
        'meta'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'commission_fee' => 'decimal:2',
        'sender_balance_before' => 'decimal:2',
        'sender_balance_after' => 'decimal:2',
        'receiver_balance_before' => 'decimal:2',
        'receiver_balance_after' => 'decimal:2',
    ];

    /**
     * Summary of sender user
     * @return BelongsTo<User, Transaction>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Summary of receiver user
     * @return BelongsTo<User, Transaction>
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

}
