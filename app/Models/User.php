<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lcobucci\JWT\Builder;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;
use Laravel\Cashier\Billable;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="User",
 *      required={"email", "password"},
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
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="token_id",
 *          description="token_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="company_id",
 *          description="company_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="UUID",
 *          description="UUID",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="stripe_id",
 *          description="stripe_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="card_brand",
 *          description="card_brand",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="card_last_four",
 *          description="card_last_four",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="trail_ends_at",
 *          description="trail_ends_at",
 *          type="string",
 *          format="date-time"
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
class User extends Authenticatable implements Presentable
{
    use Notifiable;
    use PresentableTrait;
    use Billable;
    use SoftDeletes;
    use Uuids;

    public $table = 'users';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'email',
        'password',
        'company_id',
        'token_id',
        'UUID',
        'stripe_id',
        'card_brand',
        'card_last_four',
        'trail_ends_at',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'email' => 'string',
        'password' => 'string',
        'token_id' => 'string',
        'company_id' => 'integer',
        'UUID' => 'string',
        'stripe_id' => 'string',
        'card_brand' => 'string',
        'card_last_four' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'email' => 'required|email|unique:users|max:250',
        'password' => 'required',
        // 'company_id' => 'numeric'
    ];

    public static $rulesUpdate = [
        'email' => 'required',
    ];

    const ROLE_ADMIN    = 1;
    const ROLE_COMPANY  = 2;
    const ROLE_WALKER   = 3;
    const ROLE_CLIENT   = 4;


    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCompany()
    {
        return $this->role === self::ROLE_COMPANY;
    }


    public function isWalker()
    {
        return $this->role === self::ROLE_WALKER;
    }

    public function isClient()
    {
        return $this->role === self::ROLE_CLIENT;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pets()
    {
        return $this->hasMany(\App\Models\Pet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function devices()
    {
        return $this->hasMany(\App\Models\Device::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }

    public function companies()
    {
        return $this->hasMany(\App\Models\Company::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function profile()
    {
        return $this->hasOne(\App\Models\Profile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function animals()
    {
        return $this->hasMany(\App\Models\Animal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function breeds()
    {
        return $this->hasMany(\App\Models\Breed::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function images()
    {
        return $this->hasMany(\App\Models\Image::class);
    }
    public function hasShareDevices(){
        return $this->hasMany(\App\Models\ShareDevice::class);
    }

    public function carts()
    {
        return $this->hasMany(\App\Models\Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Orders::class);
    }

    public function generateAccessToken()
    {
        $this->token_id = (string) (new Builder())->setIssuer('http://2solid.vn/') 
            ->setAudience('http://2solid.vn/')
            ->setId('4f1g23a12aa', true) 
            ->setIssuedAt(time())
            ->setExpiration(time() + 86400)
            ->set('id', $this->id)
            ->set('email', $this->email)
            ->getToken();
    }

    public function getAvatar()
    {
        $image = \App\Models\Image::where('user_id', $this->id)->where('pet_id', null)->where('is_avatar', 1)->select('url')->first();

        if($image){
            if(\Storage::disk('public')->exists('users/'.$this->id.'/'.$image->url)){
                $url = url('/storage/users/'.$this->id.'/'.$image->url);
            }else{
                $url = url('/img/default/default-avatar.png');
            }
        }else{
            $url = url('/img/default/default-avatar.png');
        }
        return $url;
    }

    public function name(){
        $profile = Profile::where('user_id',$this->id)->first();
        if($profile){
            $name = $profile['name'];
        }else{
            $name = null;
        }
        return $name;
    }

    public function conversations() {
        return $this->belongsToMany(\App\Models\Conversation::class, 'conversation_users', 'user_id', 'conversation_id');
    }

    public function countMessageNotRead(){
        $message_not_read = \App\Models\MessageNortification::where('user_id', $this->id)->where('read', false)->count();
        return $message_not_read;
    }
}
