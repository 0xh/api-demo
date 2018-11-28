<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Order item",
 *      required={"order_id", "product_id", "price", "payment_by"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="order_id",
 *          description="order_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="product_id",
 *          description="product_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="price",
 *          description="price",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="payment_by",
 *          description="payment_by",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class OrderItem extends Model
{
    use SoftDeletes;

    public $table = 'order_items';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'order_id',
        'product_id',
        'price',
        'payment_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'price' => 'float',
        'payment_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'order_id' => 'required',
        'product_id' => 'required',
        'price' => 'required',
        'payment_by' => 'required'
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id');
    }

    public function product_order()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

}
