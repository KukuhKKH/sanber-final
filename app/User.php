<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['point'];

    public function pertanyaan() {
        return $this->hasMany('App\Pertanyaan');
    }

    public function vote() {
        return $this->belongsToMany('App\Vote', 'vote_has_user');
    }

//    public function points() {
//        return $this->vote()->sum('point');
//    }

    public function getPointAttribute() {
        $up = $this->vote()->where(['status' => 1])->sum('point');
        $down = $this->vote()->where(['status' => 0])->sum('point');
        return $up - $down;
    }
}
