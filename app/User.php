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

    const DOMAIN = 'powerhouse.industries';

    const ADMIN = 'admin';
    const USER = 'user';
    const ROLES = [self::ADMIN, self::USER];

    const AVAILABLE = 'available';
    const RENDERING = 'rendering';
    const UNAVAILABLE = 'unavailable';

    const DEFAULTUSERNAME = '[Default user name]';

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
    protected $fillable = ['userName', 'email', 'password', 'status', 'role'];

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
    public static $statuses = [self::AVAILABLE, self::UNAVAILABLE, self::RENDERING];

    /**
     * Get a new, unique user_token.
     */
    public function getNewToken()
    {
        $token = null;
        // Try to get a unique token
        for ($i=0; $i<10; $i++) {
            $token = Str::random(16);
            // Check the token is unique
            $user = User::where('user_token', $token)->first();
            if (!$user) {
                return $token;  // Ok, is unique
            }
        }
        throw new \Exception("Could not generate a unique token for new user");
    }

    /**
     * Check the user_token has been provided and is valid
     *
     * @param $userToken
     */
    public function checkUserToken($userToken)
    {
        if (!isset($userToken)) {
            throw new \Exception('API authentication not provided');
        }
        if ($userToken !== $this->user_token) {
            throw new \Exception('API authentication is invalid');
        }
    }

    /**
     * Return user name
     */
    public function getName() {
        return $this->userName;
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
                'u.id','u.userName','u.email','u.status'
            )
            ->where('u.status', '=', 'available');

        $users = $builder
            ->orderBy('u.userName', 'ASC')
            ->get();

        return $users;
    }
}
