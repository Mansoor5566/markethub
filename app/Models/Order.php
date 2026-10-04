<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'listing_id',
        'unit_price',
        'quantity',
        'total',
        'status',
        'stripe_session_id',
        'shipping_address',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'total'      => 'decimal:2',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────

    public function canBeReviewed(): bool
    {
        return $this->status === 'completed' && !$this->review()->exists();
    }
}