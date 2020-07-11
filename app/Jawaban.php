<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $table = "jawaban";
    protected $fillable = ['isi', 'pertanyaan_id', 'user_id', 'benar'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function pertanyaan() {
        return $this->belongsTo('App\Jawaban');
    }

    public function komentar() {
        return $this->belongsToMany('App\Komentar', 'jawaban_has_komentar');
    }
}
