@extends('emails.layouts.email')

@section('title')Invitation to  {{ $event->name }} @endsection

@section('content')
    @if(empty($to_name))
    <p>Hello,</p>
    @else
    <p>Hello {{ ucfirst($to_name) }},</p>
    @endif

    <p><strong>{{$from_name}}<strong> wants invite you to <strong>{{$event->name}}</strong>.</p>
    <p>This event is at {{ $event->location }} and {{ $event->start_readable }} - {{ $event->end_readable}}

    @component('mail::button', ['url' => $accept_url])
    Accept
    @endcomponent

    @component('mail::button', ['url' => $decline_url])
    Decline
    @endcomponent

@endsection