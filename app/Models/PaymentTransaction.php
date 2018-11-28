<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Payment transactions",
 *      required={"order_id", "transaction_type", "amount", "brand", "token", "status"},
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
 *          property="transaction_type",
 *          description="transaction_type",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="transaction_method",
 *          description="transaction_method",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="amount",
 *          description="amount",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="last4",
 *          description="last4",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="brand",
 *          description="brand",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="exp_month",
 *          description="exp_month",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="exp_year",
 *          description="exp_year",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="token",
 *          description="token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="boolean"
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
class PaymentTransaction extends Model
{
    use SoftDeletes;

    public $table = 'payment_transactions';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'order_id',
        'transaction_type',
        'transaction_method',
        'amount',
        'last4',
        'brand',
        'exp_month',
        'exp_year',
        'token',
        'status',
        'sumQty'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_id' => 'integer',
        'transaction_type' => 'integer',
        'transaction_method' => 'string',
        'amount' => 'string',
        'last4' => 'string',
        'brand' => 'string',
        'exp_month' => 'string',
        'exp_year' => 'string',
        'token' => 'string',
        'status' => 'boolean',
        'sumQty' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'order_id' => 'required',
        'transaction_type' => 'required',
        'amount' => 'required',
        'brand' => 'required',
        'token' => 'required',
        'status' => 'required'
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id');
    }
    
    public function payment_transaction_type()
    {
        return $this->belongsTo(\App\Models\PaymentTransactionType::class, 'transaction_type');
    }

}
