@extends('emails.layouts.email')

@section('title')Friend Request To {{ $to_name }} @endsection

@section('content')
    @if(empty($from_name))
    <p>Hello,</p>
    @else
    <p>Hello {{ ucfirst($from_name) }},</p>
    @endif

    <p>This is to remind you that you sent a friend request to <strong>{{$to_name}}<strong></p>

@endsection