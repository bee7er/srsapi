<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    const ADMIN = 'admin';
    const USER = 'user';
    const ROLES = [self::ADMIN, self::USER];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Have the date fields set automatically
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'surname', 'email', 'password', 'status', 'role'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The statuses a user can adopt.
     *
     * @var array
     */
    public static $statuses = ['available', 'unavailable', 'rendering'];

    /**
     * Set the api_token after a valid registration.
     */
    public function setApiToken()
    {
        $this->api_token = Str::random(80);
    }

    /**
     * Check the api_token has been provided and is valid
     *
     * @param $apiToken
     */
    public function checkApiToken($apiToken)
    {
        if (!isset($apiToken)) {
            throw new \Exception('API authentication not provided');
        }
        if ($apiToken !== $this->api_token) {
            throw new \Exception('API authentication is invalid');
        }
    }

    /**
     * Build and return user name
     */
    public function getName() {
        return $this->first_name . ' ' . $this->surname;
    }

    /**
     * Returns a boolean which indicates whether the user ia n administrator
     */
    public function isAdmin() {
        return (self::ADMIN === $this->role);
    }

    /**
     * Returns arrays of users who are currently available
     *
     * @return array
     */
    public static function getAvailableUsers()
    {
        $builder = DB::table('users as u')
            ->select(
                'u.id','u.surname','u.first_name','u.email','u.status'
            )
            ->where('u.status', '=', 'available');

        $users = $builder
            ->orderBy('u.surname', 'ASC')
            ->get();

        return $users;
    }
}
