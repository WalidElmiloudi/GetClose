@extends('emails.layout')

@section('email-content')
<h2>Order Placed Successfully!</h2>
<p>Hello {{ $order->client->name }},</p>
<p>Thank you for your order! Your order #{{ $order->id }} has been placed successfully.</p>

<div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <h3 style="margin-top: 0;">Order Details</h3>
    <p><strong>Order Number:</strong> #{{ $order->id }}</p>
    <p><strong>Total Amount:</strong> ${{ number_format($order->total_price, 2) }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
</div>

<a href="{{ url('/orders/' . $order->id) }}" class="button">View Order Details</a>

<p>We'll notify you once your order status changes.</p>
@endsection
