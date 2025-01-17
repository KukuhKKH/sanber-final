<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;

class Pertanyaan extends Model implements Viewable
{
    use InteractsWithViews;

    protected $table = "pertanyaan";
    protected $fillable = ['judul', 'isi', 'user_id'];
    protected $appends = ['point'];

    public static function boot() {
        parent::boot();
        static::saving(function ($model) {
            $model->user_id = \Auth::user()->id;
        });
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function tag() {
        return $this->belongsToMany("App\Tag");
    }

    public function jawaban() {
        return $this->hasMany('App\Jawaban');
    }

    public function komentar() {
        return $this->belongsToMany('App\Komentar', 'pertanyaan_has_komentar');
    }

    public function vote() {
        return $this->belongsToMany('App\Vote', 'pertanyaan_has_vote');
    }

    public function getPointAttribute() {
        $up = $this->vote()->where(['status' => 1])->count('point');
        $down = $this->vote()->where(['status' => 0])->count('point');
        return $up - $down;
    }
}
