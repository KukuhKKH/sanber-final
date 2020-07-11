<?php

namespace App\Http\Controllers;

use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function vote_up($id, $jenis, $id_terima) {
        $cekJawab = DB::table('jawaban_has_vote')->where('jawaban_id', $id)->get();
        $cekTanya = DB::table('pertanyaan_has_vote')->where('pertanyaan_id', $id)->get();
        if($cekTanya != '[]' || $cekJawab != '[]') {
            return redirect()->back()->with(['error' => 'true', 'message' => 'Anda sudah melakukan vote']);
        }
        $vote = Vote::create(['point' => 10, 'status' => 1]);
        if($jenis == 'jawaban'){
            DB::table('jawaban_has_vote')
                ->insert(['jawaban_id' => $id, 'vote_id' => $vote->id]);
            DB::table('vote_has_user')
                ->insert(['vote_id' => $vote->id, 'user_id' => $id_terima]);
        } elseif ($jenis == 'pertanyaan') {
            DB::table('pertanyaan_has_vote')
                ->insert(['pertanyaan_id' => $id, 'vote_id' => $vote->id]);
            DB::table('vote_has_user')
                ->insert(['vote_id' => $vote->id, 'user_id' => $id_terima]);
        }
        return redirect()->back()->with(['error' => 'false', 'message' => 'Vote Up berhasil diberikan']);
    }

    public function vote_down($id, $jenis, $id_terima) {
        if(Auth::user()->point < 15) {
            return redirect()->back()->with(['error' => 'true', 'message' => 'Point anda belum mencapai 15 untuk melakukan down vote']);
        }
        $cekJawab = DB::table('jawaban_has_vote')->where('jawaban_id', $id)->get();
        $cekTanya = DB::table('pertanyaan_has_vote')->where('pertanyaan_id', $id)->get();
        if($cekTanya != '[]' || $cekJawab != '[]') {
            $vote_id = (isset($cekTanya[0]->vote_id)) ? $cekTanya[0]->vote_id : $cekJawab[0]->vote_id;
            $vote = Vote::find($vote_id);
            $vote->update([
               'point' => 1,
               'status' => 0
            ]);
            return redirect()->back()->with(['error' => 'false', 'message' => 'Vote Down berhasil diberikan']);
        } else {
            $vote = Vote::create(['point' => 1, 'status' => 0]);
        }
        if($jenis == 'jawaban'){
            DB::table('jawaban_has_vote')
                ->insert(['jawaban_id' => $id, 'vote_id' => $vote->id]);
            DB::table('vote_has_user')
                ->insert(['vote_id' => $vote->id, 'user_id' => $id_terima]);
        } elseif ($jenis == 'pertanyaan') {
            DB::table('pertanyaan_has_vote')
                ->insert(['pertanyaan_id' => $id, 'vote_id' => $vote->id]);
            DB::table('vote_has_user')
                ->insert(['vote_id' => $vote->id, 'user_id' => $id_terima]);
        }
        return redirect()->back()->with(['error' => 'false', 'message' => 'Vote Down berhasil diberikan']);
    }
}
