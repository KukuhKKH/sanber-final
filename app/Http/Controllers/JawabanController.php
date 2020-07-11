<?php

namespace App\Http\Controllers;

use App\Jawaban;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JawabanController extends Controller
{
    public function benar($id) {
        $jawaban = Jawaban::find($id);
        DB::table('jawaban')
            ->where('pertanyaan_id', $jawaban->pertanyaan_id)
            ->update(['benar' => 0]);
        $jawaban->update(['benar' => 1]);
        $cekVote = DB::table('vote_has_user')->where(['user_id' => $jawaban->user_id])->get();
        foreach ($cekVote as $value) {
            $result = Vote::find($value->vote_id);
        }
        $vote = Vote::create(['point' => 15, 'status' => 1]);
        if(isset($result)) {
            DB::table('vote_has_user')->update(['user_id' => $jawaban->user_id]);
        } else {
            DB::table('vote_has_user')->insert(['user_id' => $jawaban->user_id, 'vote_id' => $vote->id]);
        }
        return redirect()->back()->with(['error' => 'false', 'message' => "Jawaban dari ".$jawaban->user->nama. " Benar"]);
    }
}
