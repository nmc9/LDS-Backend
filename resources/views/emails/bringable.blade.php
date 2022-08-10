@extends('emails.layouts.email')

@section('title')Bringable Assigned  {{ $bringable_name }} @endsection

@section('content')
    @if(empty($to_name))
    <p>Hello,</p>
    @else
    <p>Hello {{ ucfirst($to_name) }},</p>
    @endif

    <p>You have been assigned to bring <strong>{{$bringable_name}}</strong>.</p>
    <p>This is for event {{ $event_name }}

@endsection