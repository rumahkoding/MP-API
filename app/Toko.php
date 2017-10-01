<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    
    protected $primaryKey = 'id';

    protected $fillable = array("idProfil","lat","long","namaToko","fotoToko","deksripsi");

    public function profil(){
		return $this->belongsTo('App\Profile', 'id');
	}

}
