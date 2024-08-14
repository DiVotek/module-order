<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTable;
use App\Traits\HasTimestamps;

class OrderHistory extends Model
{
    use HasTable;
    use HasTimestamps;

    public static function getDb():string{
        return 'order_histories';
    }
}
