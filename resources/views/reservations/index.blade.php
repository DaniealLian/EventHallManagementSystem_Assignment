<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
</head>
<body>
    <h1>Available Events</h1>
@extends('layouts.app')
    @foreach($events as $event)
        <div>
            <h3>{{ $event->title }}</h3>
            <a href="{{ route('reservations.create', $event) }}">Make a Reservation</a>
        </div>
        <hr>
    @endforeach

</body>
</html>