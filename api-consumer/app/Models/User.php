<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
class User extends Model implements AuthenticatableContract 
{
    public function getAuthIdentifierName()
    {
        return 'id';
    }
public function getAuthIdentifier()
    {
        return $this->id;
    }
public function getAuthPassword()
    {
        return null;
    }
public function getRememberToken()
    {
        return null;
    }
    public function setRememberToken($value) {}
    public function getRememberTokenName() {}
}