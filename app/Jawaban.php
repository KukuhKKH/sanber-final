<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $table = "jawaban";
    protected $fillable = ['isi', 'pertanyaan_id', 'user_id', 'benar'];
    protected $appends = ['point'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function pertanyaan() {
        return $this->belongsTo('App\Jawaban');
    }

    public function komentar() {
        return $this->belongsToMany('App\Komentar', 'jawaban_has_komentar');
    }

    public function vote() {
        return $this->belongsToMany('App\Vote', 'jawaban_has_vote');
    }

    public function getPointAttribute() {
        $up = $this->vote()->where(['status' => 1])->count('point');
        $down = $this->vote()->where(['status' => 0])->count('point');
        return $up - $down;
    }
}
