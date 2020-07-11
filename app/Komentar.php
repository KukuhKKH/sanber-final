<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = "komentar";
    protected $fillable = ['isi', 'user_id'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function jawaban() {
        return $this->belongsToMany('App\Jawaban');
    }

    public function pertanyaan() {
        return $this->belongsToMany('App\Pertanyaan');
    }
}
