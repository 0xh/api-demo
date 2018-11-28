<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(
 *      definition="City",
 *      required={"name"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      )
 * )
 */
class City extends Model
{
    public $table = 'cities';

    public $fillable = [
        'name'
    ];

    public $timestamps = false;
}
