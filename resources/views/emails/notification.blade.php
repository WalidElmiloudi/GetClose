@extends('emails.layout')

@section('email-content')
<h2>You have a new notification</h2>
<p>Hello,</p>
<p>{{ $message ?? 'You have received a new notification from GetClose.' }}</p>

@if(isset($action_url) && isset($action_text))
<a href="{{ $action_url }}" class="button">{{ $action_text }}</a>
@endif

<p>If you have any questions, feel free to contact our support team.</p>
@endsection
