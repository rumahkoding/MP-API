<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = array("idUser","fullName","fotoProfile","idKota","alamat");

    public function user(){
		return $this->belongsTo('App\User', 'idUser');
	}

	public function toko(){
		return $this->hasMany('App\Toko');
	}

}
