<?php

namespace App\Http\Controllers;

use App\Jawaban;
use App\Komentar;
use App\Tag;
use App\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\Pertanyaan\PertanyaanCreateRequest;
use App\Http\Requests\Pertanyaan\PertanyaanUpdateRequest;
use App\Http\Requests\Pertanyaan\PertanyaanJawabRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PertanyaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pertanyaan = Pertanyaan::orderBy('id', 'DESC')->paginate(10);
        $populer = $this->getPopular();
        return view('pages.pertanyaan.index', compact('pertanyaan', 'populer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pertanyaan = Pertanyaan::orderBy('id', 'desc')->limit(1)->get();
        $tags = Tag::all();
        return view('pages.pertanyaan.create', compact('tags', 'pertanyaan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PertanyaanCreateRequest $request)
    {
        try {
            $pertanyaan = Pertanyaan::create($request->all());
            $pertanyaan->tag()->sync($request->tags);
            return redirect()->back()->with(['error' => 'false', 'message' => 'Create pertanyaan success']);
        } catch(\Exception $e) {
            return redirect()->back()->with(['error' => 'true', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pertanyaan = Pertanyaan::find($id);
        $tag_id = [];
        foreach ($pertanyaan->tag as $value) {
            $tag_id[] = $value->id;
        }
        $like = $this->like($tag_id, $pertanyaan->id, 5);
        views($pertanyaan)->record();
        return view('pages.pertanyaan.show', compact('pertanyaan', 'like'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PertanyaanUpdateRequest $request, $id)
    {
        try {
            $pertanyaan = Pertanyaan::find($id);
            $pertanyaan->update($request->all());
            $pertanyaan->tag()->sync($request->tags);
            return redirect()->back()->with(['error' => 'false', 'message' => 'Update pertanyaan success']);
        } catch(\Exception $e) {
            return redirect()->back()->with(['error' => 'true', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $pertanyaan = Pertanyaan::find($id);
            if($pertanyaan->jawaban->isEmpty()) {
                $pertanyaan->delete();
                return redirect()->back()->with(['error' => 'false', 'message' => 'Delete pertanyaan success']);
            } else {
                return redirect()->back()->with(['error' => 'false', 'message' => 'Pertanyaan masih memiliki jawaban']);
            }
        } catch(\Exception $e) {
            return redirect()->back()->with(['error' => 'true', 'message' => $e->getMessage()]);
        }
    }

    public function jawab($id, PertanyaanJawabRequest $request) {
        try {
            Jawaban::create([
                'isi' => $request->isi,
                'pertanyaan_id' => $id,
                'user_id' => Auth::user()->id
            ]);
            return redirect()->back()->with(['error' => 'false', 'message' => 'Jawaban berhasil di simpan']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'true', 'message' => $e->getMessage()]);
        }
    }

    public function komentar($id, $jenis, Request $request) {
        $komentar = Komentar::create(['isi' => $request->isi, 'user_id' => Auth::user()->id]);
        if($jenis == 'pertanyaan') {
            DB::table('pertanyaan_has_komentar')
                ->insert([
                    'pertanyaan_id' => $id,
                    'komentar_id' => $komentar->id
                ]);
        } elseif ($jenis == 'jawaban') {
            DB::table('jawaban_has_komentar')
                ->insert([
                    'jawaban_id' => $id,
                    'komentar_id' => $komentar->id
                ]);
        }
        return redirect()->back()->with(['error' => 'false', 'message' => 'Komentar berhasil di simpan']);
    }

    private function like($where, $id_pertanyaan = 0, $limit = 10) {
        return DB::table('pertanyaan')
            ->select('pertanyaan.id', 'pertanyaan.judul', 'pertanyaan.created_at', 'users.nama', 'users.foto', DB::raw('GROUP_CONCAT(tag.nama) AS tag'))
            ->join('users', 'users.id', '=', 'pertanyaan.user_id', 'left')
            ->join('pertanyaan_tag', 'pertanyaan.id', '=', 'pertanyaan_tag.pertanyaan_id', 'left')
            ->join('tag', 'tag.id', '=', 'pertanyaan_tag.tag_id', 'left')
            ->groupBy('pertanyaan.id')
            ->where('pertanyaan.id', '!=',$id_pertanyaan)
            ->whereIn('tag.id', $where)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    private function getPopular(){
        return DB::table("pertanyaan")
            ->join('views', 'pertanyaan.id', '=', 'views.viewable_id', 'left')
            ->join('users', 'pertanyaan.user_id', '=', 'users.id', 'left')
            ->select(DB::raw('count(viewable_id) as count'), 'pertanyaan.id', 'pertanyaan.judul', 'users.nama')
            ->groupBy('pertanyaan.id', 'judul', 'nama')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
    }
}
