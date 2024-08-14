<?php

namespace Modules\Order\Models;

use App\Traits\HasSorting;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTable;
use App\Traits\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryMethod extends Model
{
    use HasTable;
    use HasTimestamps;
    use HasStatus;
    use HasSorting;

    public static function getDb(): string
    {
        return 'delivery_methods';
    }

    protected $fillable = [
        'delivery_method_id',
        'name',
        'status',
        'sorting',
        'image',
        'commission',
        'settings',
        'fields'
    ];

    protected $casts = [
        'settings' => 'array',
        'fields' => 'array'
    ];

    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_method_id');
    }
}
