@extends('layout')

@section('content')
  <h1>Venues</h1>

  @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif

  <a href="{{ route('venues.create') }}" class="button">+ Add New Venue</a>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Code</th><th>Name</th><th>Address</th>
          <th>Postal Code</th><th>Capacity</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($venues as $v)
          <tr>
            <td>{{ $v->id }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->address }}</td>
            <td>{{ $v->postal_code }}</td>
            <td>{{ $v->capacity }}</td>
            <td class="actions">
              <a href="{{ route('venues.edit', $v->id) }}">Edit</a>
              <form action="{{ route('venues.destroy', $v->id) }}"
                    method="POST">
                @csrf @method('DELETE')
                <button onclick="return confirm('Delete this venue?')">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6">No venues yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
