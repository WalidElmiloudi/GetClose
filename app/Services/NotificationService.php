<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send in-app notification
     */
    public function sendInAppNotification($userId, string $title, string $message, string $type = 'info', array $data = [])
    {
        return Notification::create([
            'receiver_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
            'read_at' => null,
        ]);
    }

    /**
     * Send email notification
     */
    public function sendEmailNotification($userId, string $subject, string $view, array $viewData = [])
    {
        $user = User::find($userId);
        
        if ($user && $user->email) {
            Mail::to($user->email)->send(new \App\Mail\GenericNotification($subject, $view, $viewData));
        }
    }

    /**
     * Send both in-app and email notification
     */
    public function sendBoth($userId, string $title, string $message, string $subject, string $type = 'info', array $data = [], string $emailView = 'emails.notification', array $emailData = [])
    {
        $this->sendInAppNotification($userId, $title, $message, $type, $data);
        $this->sendEmailNotification($userId, $subject, $emailView, $emailData);
    }

    /**
     * Notify client when order is placed
     */
    public function notifyOrderPlaced(Order $order)
    {
        $this->sendBoth(
            $order->client_id,
            'Order Placed Successfully',
            "Your order #{$order->id} has been placed successfully.",
            'Order Confirmation - #' . $order->id,
            'success',
            ['order_id' => $order->id],
            'emails.order-placed',
            ['order' => $order]
        );
    }

    /**
     * Notify client when order status changes
     */
    public function notifyOrderStatusChanged(Order $order, string $oldStatus, string $newStatus)
    {
        $statusMessages = [
            'pending' => 'Your order is pending confirmation',
            'paid' => 'Your order payment has been confirmed',
            'processing' => 'Your order is being processed',
            'partialy_shipped' => 'Your order has been partially shipped',
            'shipped' => 'Your order has been shipped',
            'completed' => 'Your order has been completed',
            'cancelled' => 'Your order has been cancelled',
            'refunded' => 'Your order has been refunded',
        ];

        $message = $statusMessages[$newStatus] ?? "Your order status has been updated to {$newStatus}";
        
        $this->sendBoth(
            $order->client_id,
            "Order #{$order->id} Status Updated",
            $message,
            "Order #{$order->id} - " . ucfirst($newStatus),
            'info',
            ['order_id' => $order->id, 'old_status' => $oldStatus, 'new_status' => $newStatus],
            'emails.order-status-updated',
            ['order' => $order, 'old_status' => $oldStatus, 'new_status' => $newStatus]
        );
    }

    /**
     * Notify vendor when they have a new order
     */
    public function notifyVendorNewOrder($vendorId, Order $order)
    {
        $this->sendBoth(
            $vendorId,
            'New Order Received',
            "You have received a new order #{$order->id}.",
            'New Order Received - #' . $order->id,
            'info',
            ['order_id' => $order->id],
            'emails.vendor-new-order',
            ['order' => $order]
        );
    }

    /**
     * Notify admin when new order is placed
     */
    public function notifyAdminNewOrder(Order $order)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            $this->sendInAppNotification(
                $admin->id,
                'New Order Placed',
                "Order #{$order->id} has been placed by {$order->client->name}.",
                'info',
                ['order_id' => $order->id, 'client_name' => $order->client->name]
            );
        }
    }

    /**
     * Get unread notification count for user
     */
    public function getUnreadCount($userId)
    {
        return Notification::unread()->forUser($userId)->count();
    }

    /**
     * Get recent notifications for user
     */
    public function getRecentNotifications($userId, $limit = 10)
    {
        return Notification::forUser($userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get all notifications for user with pagination
     */
    public function getUserNotifications($userId, $perPage = 20)
    {
        return Notification::forUser($userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('receiver_id', $userId)
            ->first();
            
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        
        return false;
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead($userId)
    {
        Notification::unread()
            ->forUser($userId)
            ->update(['read_at' => now()]);
    }
}
