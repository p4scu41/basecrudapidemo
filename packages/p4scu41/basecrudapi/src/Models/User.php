<?php

namespace p4scu41\BaseCRUDApi\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

/**
 * Class associated with the user table
 *
 * @category Models
 * @package  p4scu41\BaseCRUDApi\Models
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 *
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property datetime $last_login
 * @property string $remember_token
 * @property boolean $active
 * @property int $created_by
 * @property int $updated_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \p4scu41\BaseCRUDApi\Models\User $createdBy
 * @property-read \p4scu41\BaseCRUDApi\Models\User $updatedBy
 * @property-read \p4scu41\BaseCRUDApi\Models\Role $role
 *
 * @method static void boot()
 * @method static boolean creatingHandler(\p4scu41\BaseCRUDApi\Models\User $model)
 * @method static array getCatalogs(boolean $withLabels)
 * @method public p4scu41\BaseCRUDApi\Models\Role role(boolean $withLabels)
 * @method public boolean isAdministrador()
 * @method public boolean owns(\Illuminate\Database\Eloquent\Model $related)
 */
class User extends BaseModelActivitylog implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * inheritDoc
     */
    public $table = 'user_account';

    /**
     * inheritDoc
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'last_login',
        'active',
    ];

    /**
     * inheritDoc
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * inheritDoc
     */
    public static $labels = [
        'role_id'               => 'Tipo de Usuario',
        'name'                  => 'Nombre',
        'email'                 => 'E-mail',
        'password'              => 'Contraseña',
        'password_confirmation' => 'Confirmar Contraseña',
        'active'                => 'Activo',
        'last_login'            => 'Último Acceso',
        'created_at'            => 'Fecha de Registro',
    ];

    /**
     * inheritDoc
     */
    public static $rules_create = [
        'role_id'               => 'bail|required|integer|exists:role,id',
        'name'                  => 'bail|required|min:3|max:55',
        'email'                 => 'bail|required|min:3|max:40|email|unique:user_account',
        'password'              => 'bail|required|min:6|max:61',
        'password_confirmation' => 'same:password',
        'active'                => 'bail|required|integer|in:0,1',
    ];

    /**
     * inheritDoc
     */
    public static $rules_update = [
        'role_id'  => 'bail|required|integer|exists:role,id',
        'name'     => 'bail|required|min:3|max:55',
        'email'    => 'bail|required|min:3|max:40|email',
        'password' => 'bail|min:6|max:61',
        'active'   => 'bail|required|integer|in:0,1',
    ];

    /**
     * inheritDoc
     */
    public $error_messages = [
        'role_id.required'           => 'No se especificó el Rol de Usuario',
        'role_id.exis'               => 'El Rol de Usuario especificado no existe',
        'password_confirmation.same' => 'Las Contraseñas no Coinciden',
    ];

    /**
     * @inheritDoc
     */
    public static function creatingHandler($model)
    {
        // Encrypt the password before insert
        $model->password = bcrypt($model->password);

        return true;
    }

    /**
     * @inheritDoc
     */
    public static function updatingHandler($model)
    {
        // Encrypt the password before update
        if ($model->isDirty('password')) {
            $model->password = bcrypt($model->password);
        }

        return true;
    }


    /**
     * Role Model related with role_id
     *
     * @return App\Models\Role $role
     */
    public function role()
    {
        return $this->belongsTo('p4scu41\BaseCRUDApi\Models\Role', 'role_id', 'id');
    }

    /**
     * Check if the user is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active == 1;
    }

    /**
     * Check if the user is SuperAdministrador
     *
     * @return boolean
     */
    public function isSuperAdministrator()
    {
        return $this->role_id == Role::SUPER_ADMINISTRATOR;
    }

    /**
     * Check if the user is Administrador
     *
     * @return boolean
     */
    public function isAdministrator()
    {
        return $this->role_id == Role::ADMINISTRATOR;
    }

    /**
     * Check if the user is the creator (created_by) or owner (user_id) of the $related
     *
     * @param \Illuminate\Database\Eloquent\Model $related Object to check
     *
     * @return boolean
     */
    public function owns($related)
    {
        if (Schema::hasColumn($related->getTable(), 'created_by')) {
            return $this->id == $related->created_by;
        }

        if (Schema::hasColumn($related->getTable(), 'user_id')) {
            return $this->id == $related->user_id;
        }

        return false;
    }
}
