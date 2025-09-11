@extends('layouts.app')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
@section('content')
<h2>Create Event</h2>

<form method="POST" action="{{ route('events.store') }}">
    @csrf
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label>Start Time</label>
        <input type="datetime-local" name="start_time" class="form-control" required>
        @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label>End Time</label>
        <input type="datetime-local" name="end_time" class="form-control" required>
        @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
