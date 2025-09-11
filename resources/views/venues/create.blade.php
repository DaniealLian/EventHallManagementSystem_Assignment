@extends('layout')

@section('content')
  <h1>{{ isset($venue) ? 'Edit Venue' : 'Add New Venue' }}</h1>

  @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert-success" style="background:#3b2628; border-left-color:#e91e63;">
      <ul>
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <form action="{{ isset($venue)
                    ? route('venues.update', $venue->code)
                    : route('venues.store') }}"
          method="POST">
      @csrf
      @if(isset($venue)) @method('PUT') @endif

      <label for="name">Name:</label>
      <input id="name" name="name" type="text"
             value="{{ old('name', $venue->name ?? '') }}">

      <label for="address">Address:</label>
      <textarea id="address" name="address">{{ old('address', $venue->address ?? '') }}</textarea>

      <label for="capacity">Capacity:</label>
      <input id="capacity" name="capacity" type="number"
             value="{{ old('capacity', $venue->capacity ?? '') }}">

      <label for="postal_code">Postal Code:</label>
      <input id="postal_code" name="postal_code" type="text"
             value="{{ old('postal_code', $venue->postal_code ?? '') }}">

      <button type="submit" class="button">
        {{ isset($venue) ? 'Update Venue' : 'Add Venue' }}
      </button>
    </form>
  </div>
@endsection
