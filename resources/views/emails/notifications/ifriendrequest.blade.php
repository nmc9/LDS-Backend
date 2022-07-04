@extends('emails.layouts.email')

@section('title')Imaginary Friend Request To {{ $to_email }} @endsection

@section('content')
    @if(empty($from_name))
    <p>Hello,</p>
    @else
    <p>Hello {{ ucfirst($from_name) }},</p>
    @endif

    <p>This is to remind you that you sent an imaginary friend request to <strong>{{$to_email}}<strong></p>

@endsection