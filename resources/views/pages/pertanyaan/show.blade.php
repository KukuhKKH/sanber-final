@extends('layouts.master')
@section('title',"$pertanyaan->judul")

@section('css')
    <style>
        a.disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 text-justify border-right pr-3">
                <div class="row">
                    <div class="col-md-1 text-center">
                        <a href="{{ route('vote.up', [$pertanyaan->id, 'pertanyaan', $pertanyaan->user_id]) }}" {!! ($pertanyaan->user_id == Auth::user()->id) ? 'class="disabled text-dark"' : '' !!}><i class="fas fa-sort-up fa-3x"></i></a>
                        <span>{{ $pertanyaan->point }}</span>
                        <a href="{{ route('vote.down', [$pertanyaan->id, 'pertanyaan', $pertanyaan->user_id]) }}" {!! ($pertanyaan->user_id == Auth::user()->id) ? 'class="disabled text-dark"' : '' !!}><i class="fas fa-sort-down fa-3x"></i></a>
                    </div>
                    <div class="col-md-11 mb-5">
                        <div class="border-bottom pb-3 mb-5">
                            <h1 class="h3">{{ $pertanyaan->judul }}</h1>
                            <small>Create in {{ $pertanyaan->created_at }}</small> . <small>views {{ views($pertanyaan)->count() }}</small>
                            <p class="mt-3 mb-1">{!! $pertanyaan->isi !!}</p>
                            <br>
                            @foreach($pertanyaan->tag as $tag_penanda)
                                <span class="btn btn-success btn-sm mr-2">{{ $tag_penanda->nama }}</span>
                            @endforeach
                        </div>
                        {{-- komentar pertanyaan --}}
                        <div class="ml-5">
                            @foreach($pertanyaan->komentar as $item)
                                <p class="m-0 border-top border-bottom pt-2 pb-2" style="font-size: 12px;">{{ $item->isi }}. <a href="">--{{ $item->user->nama }}--</a> {{ $item->created_at->diffForHumans() }} </p>
                            @endforeach
                        </div>
                        <a href="javascript:void(0)" onclick="formKomentar({{ $pertanyaan->id }}, 'pertanyaan')" style="font-size: 12px;">add a comment</a>
                    </div>

                    {{-- jawaban --}}
                    @if($pertanyaan->jawaban != '[]')
                        <h1 class="h3">Jawaban</h1>
                        @foreach($pertanyaan->jawaban as $value)
                            <div class="row mt-5 pr-3">
                                <div class="col-md-1 text-center">
                                    <a href="{{ route('vote.up', [$value->id, 'jawaban', $value->user_id]) }}" {!! ($value->user_id == Auth::user()->id) ? 'class="disabled text-dark"' : '' !!}><i class="fas fa-sort-up fa-3x"></i></a>
                                    <span>{{ $value->point }}</span>
                                    <a href="{{ route('vote.down', [$value->id, 'jawaban', $value->user_id]) }}" {!! ($value->user_id == Auth::user()->id) ? 'class="disabled text-dark"' : '' !!}><i class="fas fa-sort-down fa-3x"></i></a>
                                </div>
                                <div class="col-md-11">
                                    <div class="border-bottom pb-3 mb-3">
                                        @if($value->benar)
                                            <a href="javascript:void(0)"><i class="fa fa-check-circle" aria-hidden="true"></i> Jawaban Benar</a>
                                        @endif
                                        <small>Create {{ $value->created_at }}</small>
                                        @if(Auth::user()->id == $pertanyaan->user_id)
                                            <a href="{{ route('jawaban.benar', $value->id) }}" class="float-right">Tandai sebagai jawaban Benar</a>
                                        @endif
                                        <p class="mt-3 mb-1">{!! $value->isi !!}</p>
                                    </div>
                                    <div class="media mb-4" style="position: relative; left:65%;">
                                        <img class="mr-2 rounded-circle" width="25" src="{{ ($value->user->foto != '') ? $value->user->foto : asset('images/user.png') }}" alt="{{ $value->user->nama }}">
                                        <div class="media-body">
                                            <small class="mt-0">{{ $value->user->nama }}</small><br>
                                            <small title="point reputations">{{ $value->user->point }}</small> . <small>{{ $value->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>

                                    {{-- komentar jawaban --}}
                                    @foreach($value->komentar as $item)
                                        <div class="ml-5 border-top border-bottom pt-2 pb-2">
                                            <p class="m-0" style="font-size: 12px;">{{ $item->isi }}. <a href="">-- {{ $item->user->nama }} --</a>{{ $item->created_at->diffForHumans() }}</p>
                                        </div>
                                    @endforeach
                                    <a href="javascript:void(0)" style="font-size: 12px;" onclick="formKomentar({{ $value->id }}, 'jawaban')">add a comment</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h3>Belum ada Jawaban</h3>
                    @endif
                </div>
                <hr>
                @if(Auth::guest())
                    <h3>Silahkan login untuk menjawab</h3>
                @else
                    <h3>Jawaban Kamu</h3>
                    <form action="{{ route('pertanyaan.jawab', $pertanyaan->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea type="text" class="form-control" id="tc_input" name="isi" placeholder="Keluhanmu"> </textarea>
                        </div>
                        <button class="btn btn-success float-right">Jawab</button>
                    </form>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card text-white mb-3">
                    <div class="card-header bg-secondary">Pertanyaan Yang Sama</div>
                    <div class="card-body">
                        {{-- pertanyaan yang sama --}}
                        @foreach($like as $value)
                            <div class="media text-dark pb-3 border-bottom mb-3">
                                <img class="mr-3 rounded-circle" width="40" src="{{ ($value->foto != '') ? $value->foto : asset('images/user.png') }}" alt="not found">
                                <div class="media-body">
                                    <p class="mt-0 mb-0 font-weight-bold">{{ $value->nama }}</p>
                                    <small>{{ \Illuminate\Support\Carbon::parse($value->created_at)->diffForHumans() }}</small><br>
                                    <a href="{{ route('pertanyaan.show', $value->id) }}"><small>{{ $value->judul }}</small></a><br>
                                    <?php
                                    $tags = explode(',', $value->tag)
                                    ?>
                                    <div class="">
                                        @foreach($tags as $item)
                                            <small class="btn btn-success btn-sm" style="font-size: 8px;">{{ $item }}</small>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="float-right">
                            <a href="{{ route('pertanyaan.index') }}" class="btn btn-link">See more &nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-alt-circle-right"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalKomentar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formKomentar" action="" method="POST">
                @csrf
                @method("POST")
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Komentar kamu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @guest
                            <h3>Siliahkan login untuk memberi komentar</h3>
                        @else
                            <div class="form-group">
                                <input type="text" class="form-control" name="isi" required autofocus>
                                <small>Note* :masukan komentar Lebih spesifik</small>
                            </div>
                        @endguest
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if(!Auth::guest())
                            <button type="submit" class="btn btn-primary">Submit</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function formKomentar(id, jenis) {
            document.querySelector(`#formKomentar`).action = `/komentar/${id}/${jenis}`
            document.querySelector(`#formKomentar`).method = 'post'
            $("#modalKomentar").modal('show')
        }

        CKEDITOR.replace('isi', {
            extraPlugins: "codesnippet"
        })
    </script>
@endsection
