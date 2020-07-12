@extends('layouts.master')

@section('title', "Profile $user->nama")

@section('css')
   <style>
      .list-group {
         list-style: none;
      }
   </style>
@endsection

@section('content')
<div class="container">
   <div class="row mt-5">
      <div class="col-3">
         <div class="card">
            <div class="card-body">
               <ul class="list-group">
                  <li><a href="{{ route('profile.index') }}">Home</a></li>
                  <li><a href="{{ route('profile.pertanyaan') }}">Pertanyaan Saya</a></li>
                  <li><a href="{{ route('profile.jawaban') }}">Jawaban Saya</a></li>
               </ul>
            </div>
         </div>
      </div>
      <div class="col-9">
         <div class="card">
            <div class="card-body">
               @foreach ($user->pertanyaan as $value)
                  <div class="card mb-2">
                     <h5 class="card-header">{{ $value->judul }}</h5>
                     <div class="card-body">
                     <p class="card-text">{{ (strlen($value->isi) > 60) ? strip_tags(substr($value->isi, 0, 60)) ."....." : strip_tags($value->isi) }}</p>
                     <a href="#" class="btn btn-primary">Edit</a>
                     <a href="#" class="btn btn-danger">Delete</a>
                     </div>
                  </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</div>
@endsection