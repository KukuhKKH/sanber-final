@extends('layouts.master')
@section('title','Buat Pertanyaan')
@section('css')
    <style>
        .form-ask{
            -webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
            -moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
            box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-8">
                <h2 class="h4 mt-5">Buat Pertanyaan kamu kedalam forum</h2>
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tenetur, fugiat!</p>
            </div>
            <div class="col-md-4">
                <img src=" {{ asset('images/bgask.PNG') }} " alt="" width="100%">
            </div>
        </div>

        <form class="border mt-3 p-4 rounded form-ask" action="{{ route('pertanyaan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <p class="font-weight-bold">Judul</p>
                <small>Note* : Lebih spesifik dan bayangkan Anda mengajukan pertanyaan kepada orang lain</small>
                <input type="text" name="judul" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required autofocus>
            </div>
            <div class="form-group">
                <p class="font-weight-bold">Isi</p>
                <small>Note* : Sertakan semua informasi yang diperlukan seseorang untuk menjawab pertanyaan Anda</small>
                <textarea id="my-editor" name="isi" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <p class="font-weight-bold">Tag</p>
                <small>Note* : Tambahkan hingga 5 tag untuk menjelaskan tentang pertanyaan Anda</small>
                <select name="tags[]" multiple="multiple" id="tags" class="form-control">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Submit</button>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(() => {
            $("#tags").select2({
                placeholder: "Select Tags",
                maximumSelectionLength: 5
            })
        })
        const options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token=',
            extraPlugins: "codesnippet"
        };
    </script>
    <script>
        CKEDITOR.replace('my-editor', options);
    </script>
@endsection
