@extends('emails.layouts.email')

@section('title')Registration
@endsection

@section('content')
    @if(empty($name))
    <p>Hello,</p>
    @else
    <p>Hello {{ ucfirst($name) }},</p>
    @endif

    <p>Thank you for signing up with us. Now Let's Do Stuff</p>
@endsection