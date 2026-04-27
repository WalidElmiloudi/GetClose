@extends('emails.layout')

@section('email-content')
<h2>Order Status Updated</h2>
<p>Hello {{ $order->client->name }},</p>
<p>Your order #{{ $order->id }} status has been updated.</p>

<div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <p><strong>Order Number:</strong> #{{ $order->id }}</p>
    <p><strong>Previous Status:</strong> {{ ucfirst($old_status) }}</p>
    <p><strong>Current Status:</strong> <span style="color: #e74c3c; font-weight: bold;">{{ ucfirst($new_status) }}</span></p>
</div>

<a href="{{ url('/orders/' . $order->id) }}" class="button">View Order</a>

<p>If you have any questions about your order, please contact our support team.</p>
@endsection
