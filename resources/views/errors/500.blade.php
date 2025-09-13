@extends('layout')

@section('content')
  <div class="card" style="text-align:center; padding:2rem; max-width:500px; margin:3rem auto;">
    <h1 style="color:#ff6b6b; margin-bottom:1rem;">500 — Weird Error</h1>
    <p style="color:#ccc; margin-bottom:2rem;">
      How dare you try to abuse the 500 error(URL) -_- <br>
      Be a good boy and go back by clicking the button below <br><br>
      V<br>V<br>V
    </p>
    <a href="{{ route('dashboard') }}" class="button" style="background:#80cbc4;">
      ← Back to Dashboard
    </a>
  </div>
@endsection
