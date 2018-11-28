<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(
 *      definition="Country",
 *      required={"name", "code"},
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
 *      ),
 *      @SWG\Property(
 *          property="code",
 *          description="code",
 *          type="string"
 *      )
 * )
 */
class Country extends Model
{

    public $table = 'countries';

    public $fillable = [
        'name',
        'code'
    ];

    public $timestamps = false;
}
