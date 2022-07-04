@extends('emails.layouts.email')

@section('title')Imaginary Friend Request {{ !empty($from_name) ? "From $from_name" : "" }} @endsection

@section('content')
    <p>Hello,</p>

    <p><strong>{{$from_name}}<strong> is inviting you to join the Let's Do Stuff System. Click Accept to be notified when this person invites you to events.</p>

    @component('mail::button', ['url' => $accept_url])
    Accept
    @endcomponent

    @component('mail::button', ['url' => $decline_url])
    Decline
    @endcomponent

@endsection