@extends('layout')

@section('content')
  <div class="card" style="text-align:center; padding:2rem; max-width:500px; margin:3rem auto;">
    <h1 style="color:#ff6b6b; margin-bottom:1rem;">404 — Nothing Found</h1>
    <p style="color:#ccc; margin-bottom:2rem;">
      X! The thing are looking for dont exist or has been removed.
    </p>
    <a href="{{ route('dashboard') }}" class="button" style="background:#80cbc4;">
      Go back to dashboard.
    </a>  
  </div>
@endsection
