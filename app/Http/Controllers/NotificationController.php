<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    /**
     * Notification Center
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = NotificationLog::where('user_id', $userId)
            ->latest();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        // Filters
        if ($request->filter == 'unread') {
            $query->where('is_read', false);
        }

        if ($request->filter == 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->paginate(10);

        $totalCount = NotificationLog::where('user_id', $userId)->count();

        $unreadCount = NotificationLog::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        $readCount = NotificationLog::where('user_id', $userId)
            ->where('is_read', true)
            ->count();

        return view('notifications.index', compact(
            'notifications',
            'totalCount',
            'unreadCount',
            'readCount'
        ));
    }

    /**
     * Mark Single Notification Read
     */
    public function markAsRead($id)
    {
        $notification = NotificationLog::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->update([
            'status' => 'Read',
            'is_read' => true
        ]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark All Notifications Read
     */
    public function markAllAsRead()
    {
        NotificationLog::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'status' => 'Read',
                'is_read' => true
            ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete Single Notification
     */
    public function destroy($id)
    {
        $notification = NotificationLog::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete Read Notifications
     */
    public function deleteRead()
    {
        NotificationLog::where('user_id', Auth::id())
            ->where('is_read', true)
            ->delete();

        return redirect()->back()->with('success', 'Read notifications deleted.');
    }

    /**
     * Purge Old Logs
     */
    public function purgeOldLogs()
    {
        NotificationLog::where('user_id', Auth::id())
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        return redirect()->back()->with('success', 'Old notifications deleted.');
    }

    /**
     * Firebase Test Notification
     */
    public function sendTestNotification(FirebaseService $firebase)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'message' => 'FCM Token Missing'
            ], 400);
        }

        try {

            $result = $firebase->sendNotification(
                $user->fcm_token,
                '🎉 TaskPulse Test Notification',
                'Firebase Push Notification Working Successfully!'
            );

            return response()->json([
                'success' => true,
                'firebase_response' => $result
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);

        }
    }
}