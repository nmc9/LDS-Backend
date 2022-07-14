@extends('emails.layouts.email')

@section('title'){{ $invited_user_name }} declined the invitation to  {{ $event_name }}@endsection

@section('content')
    @if(empty($inviter_name))
    <p>Hello,</p>
    @else
    <p>Hello {{ ucfirst($inviter_name) }},</p>
    @endif

    <p>This is to notify you that {{ $invited_user_name }} declined the invitation to  {{ $event_name }}<strong></p>

@endsection