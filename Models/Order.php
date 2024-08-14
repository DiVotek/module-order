<?php

namespace Modules\Order\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTable;
use App\Traits\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasTable;
    use HasUuids;
    use HasTimestamps;

    public static function getDb(): string
    {
        return 'orders';
    }

    protected $fillable = [
        'user_id',
        'status',
        'user_data',
        'products',
        'total',
        'currency',
        'tax',
        'payment_method_id',
        'delivery_method_id',
        'payment_status',
        'payment_order_id',
    ];

    protected $casts = [
        'user_data' => 'array',
        'products' => 'array',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'status');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function deliveryMethod(): BelongsTo
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(OrderHistory::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
