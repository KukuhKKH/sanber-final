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
               <div class="row">
                  <div class="col-3">
                     <img class="mr-2 rounded-circle img-fluid" src="{{ ($user->foto != '') ? $user->foto : asset('images/user.png') }}" alt="{{ $user->nama }}">
                  </div>
                  <div class="col-9">
                     <h2>Nama : {{ $user->nama }}</h2>
                     <h6>Total Pertanyaan {{ count($user->pertanyaan) }}</h6>
                     <h6>Total Jawaban {{ count($user->jawaban) }}</h6>
                     <h6>Total Point {{ $user->point }}</h6>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection