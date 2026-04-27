<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications for the user
     */
    public function index(): View
    {
        $notifications = $this->notificationService->getUserNotifications(auth()->id(), 20);
        $unreadCount = $this->notificationService->getUnreadCount(auth()->id());
        
        return view('pages.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $this->notificationService->markAsRead($id, auth()->id());
        
        return back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->id());
        
        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Get unread notification count (for AJAX)
     */
    public function unreadCount()
    {
        $count = $this->notificationService->getUnreadCount(auth()->id());
        
        return response()->json(['count' => $count]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('receiver_id', auth()->id())
            ->first();
            
        if ($notification) {
            $notification->delete();
            return back()->with('success', 'Notification deleted');
        }
        
        return back()->with('error', 'Notification not found');
    }
}
