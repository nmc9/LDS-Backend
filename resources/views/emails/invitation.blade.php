@extends('emails.layouts.email')

@section('title')Bringable Assigned  {{ $bringable->name }} @endsection

@section('content')
    @if(empty($to_name))
    <p>Hello,</p>
    @else
    <p>Hello {{ ucfirst($to_name) }},</p>
    @endif

    <p>You have been assigned to bring <strong>{{$bringable->name}}</strong>.</p>
    <p>This is for event {{ $event->name }}

    @component('mail::button', ['url' => $accept_url])
    Accept
    @endcomponent

    @component('mail::button', ['url' => $decline_url])
    Decline
    @endcomponent

@endsection