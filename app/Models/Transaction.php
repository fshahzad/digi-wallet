<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    public const TYPE_SENT = 'sent';
    public const TYPE_RECEIVED = 'received';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'trans_type',
        'commission_fee',
        'sender_balance_before',
        'sender_balance_after',
        'receiver_balance_before',
        'receiver_balance_after',
        'status',
        'extra'
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
        'extra' => 'json',
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
