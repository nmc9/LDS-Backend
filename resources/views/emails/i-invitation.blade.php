@extends('emails.layouts.email')

@section('title')Invitation to  {{ $event->name }} @endsection

@section('content')
    <p>Hello,</p>

    <p><strong>{{$from_name}}<strong> wants invite you to <strong>{{$event->name}}</strong>.</p>
    <p>This event is at {{ $event->location }} and {{ $event->start_readable }} - {{ $event->end_readable}}
    <p>It is quick and easy to create an account. To do so please follow the link below</p>

    @component('mail::button', ['url' => $app_url])
    Sign up for LDS
    @endcomponent
@endsection