<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTable;
use App\Traits\HasTimestamps;
use App\Traits\HasTranslate;

class OrderStatus extends Model
{
    use HasTable;
    use HasTimestamps;
    use HasTranslate;

    public static function getDb(): string
    {
        return 'order_statuses';
    }

    protected $fillable = [
        'name',
        'email_template'
    ];

    public const STATUS_NEW = 1;
    public const STATUS_PROCESSING = 2;
    public const STATUS_COMPLETED = 3;
}
