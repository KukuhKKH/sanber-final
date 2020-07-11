<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = 'vote';
    protected $fillable = ['point', 'status'];

    public function user() {
        return $this->belongsToMany('App\User', 'vote_has_user');
    }
}
