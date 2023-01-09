<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

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
     * Build and return user name
     */
    public function getName() {
        return $this->first_name . ' ' . $this->surname;
    }

    /**
     * Populate this object from the supplied object
     */
    public function populate($fromObject) {
        $this->first_name = $fromObject->first_name;
        $this->surname = $fromObject->surname;
        $this->email = $fromObject->email;
        $this->role = $fromObject->role;
        $this->status = $fromObject->status;
        $this->password = $fromObject->password;
        $this->remember_token = $fromObject->remember_token;
        $this->created_at = $fromObject->created_at;
        $this->updated_at = $fromObject->updated_at;
    }
}
