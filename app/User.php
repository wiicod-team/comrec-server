<?php

namespace App;

use App\Traits\RestTrait;
use Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Laratrust\Traits\LaratrustUserTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, RestTrait, LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'type', 'username', 'bvs_id', 'password', 'settings',
        'has_reset_password', 'status','network','entity'
    ];

    public static $Status = ['enable', 'disable', 'pending'];
    public static $Type = ['user', 'enterprise'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $casts = [
        'settings' => 'array',
        'has_reset_password' => 'boolean'
    ];

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getFullNameAttribute()
    {
        if ($this->name) {
            return $this->name;
        } else {

            return $this->username;

        }
    }

    public function getLabel()
    {
        return "$this->username $this->name";
    }

    public function customer_users()
    {
        return $this->hasMany(CustomerUser::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_users');
    }

    public function bills()
    {
        return $this->hasManyThrough(Bill::class, Customer::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        $user = Auth::user();

        static::addGlobalScope('roles', function (Builder $builder) use ($user) {
            if($user){
                if ( $user->hasRole('comrec.user')) {
                   /* $builder->whereHas('roles',function (Builder $query) {
                        $query->whereName('comrec.user');
                    });*/
                   $builder->whereNotNull('bvs_id');

                }else{
                    /*$builder->whereDoesntHave('roles',function (Builder $query) {
                        $query->whereName('comrec.user');
                    });*/
                    $builder->whereNotNull('bvs_id');

                }
            }

        });
    }
}
