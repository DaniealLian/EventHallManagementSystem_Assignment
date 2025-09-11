@extends('layouts.app')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
@section('content')
<h2>Edit Event</h2>

<form method="POST" action="{{ route('events.update', $event) }}">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $event->title) }}" class="form-control" required>
        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control">{{ old('description', $event->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Start Time</label>
        <input type="datetime-local" name="start_time" value="{{ old('start_time', $event->start_time) }}" class="form-control" required>
        @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label>End Time</label>
        <input type="datetime-local" name="end_time" value="{{ old('end_time', $event->end_time) }}" class="form-control" required>
        @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
