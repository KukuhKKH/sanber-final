<?php

namespace App\Http\Controllers;

use App\Jawaban;
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
        return redirect()->back()->with(['error' => 'false', 'message' => "Jawaban dari ".$jawaban->user->nama. " Benar"]);
    }
}
