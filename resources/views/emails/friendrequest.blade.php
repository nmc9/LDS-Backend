@extends('emails.layouts.email')

@section('title')Friend Request {{ !empty($from_name) ? "From $from_name" : "" }} @endsection

@section('content')
    @if(empty($to_name))
    <p>Hello,</p>
    @else
    <p>Hello {{ ucfirst($to_name) }},</p>
    @endif

    <p><strong>{{$from_name}}<strong> wants to be your friend.</p>

    @component('mail::button', ['url' => $accept_url])
    Accept
    @endcomponent

    @component('mail::button', ['url' => $decline_url])
    Decline
    @endcomponent

@endsection