<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'username', 'email', 'password', 'token', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token', 'status',
    ];

    public function profile(){
        return $this->hasOne('App\Profile','idUser','id');
    }  

    public function isConfirm(){

       if($this->status == '1') return true;
       return false;
    }
}
