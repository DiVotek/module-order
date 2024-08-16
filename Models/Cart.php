<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTable;
use App\Traits\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Cart extends Model
{
    use HasTable;
    use HasTimestamps;
    use HasUuids;

    protected $primaryKey = 'uuid';


    public static function getDb(): string
    {
        return 'carts';
    }

    protected $fillable = ['uuid', 'user_id', 'products', 'total'];

    protected $casts = [
        'products' => 'array',
    ];
}
