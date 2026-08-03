<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Display full history page for all notifications with search, filters, pagination.
     */
    public function index(Request $request)
    {
        // Run reminder check
        NotificationService::checkAndGenerateCalendarNotifications();

        $query = Notification::query();

        // Search Keyword
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter Type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter Status (unread / read)
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter Date
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Sorting (default: newest first)
        $sortOrder = $request->input('sort', 'desc');
        $query->orderBy('created_at', $sortOrder === 'asc' ? 'asc' : 'desc');

        $notifications = $query->paginate(15)->withQueryString();
        $unreadCount = Notification::where('is_read', false)->count();
        $totalCount  = Notification::count();

        return view('admin.notifications.index', compact(
            'notifications',
            'unreadCount',
            'totalCount'
        ));
    }

    /**
     * API endpoint to get lightweight unread count for navbar badge polling.
     */
    public function unreadCount()
    {
        NotificationService::checkAndGenerateCalendarNotifications();
        $count = Notification::where('is_read', false)->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }

    /**
     * API endpoint to get recent notifications for navbar dropdown list.
     */
    public function recent()
    {
        NotificationService::checkAndGenerateCalendarNotifications();

        $unreadCount = Notification::where('is_read', false)->count();
        $notifications = Notification::orderBy('created_at', 'desc')->take(8)->get();

        return response()->json([
            'unread_count'  => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a single notification as read and return target redirect URL.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);
        }

        $targetUrl = $notification->action_url ?: route('admin.notifications.index');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'action_url' => $targetUrl,
                'unread_count' => Notification::where('is_read', false)->count()
            ]);
        }

        return redirect()->to($targetUrl);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        Notification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi telah ditandai sebagai sudah dibaca.',
                'unread_count' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dihapus.',
                'unread_count' => Notification::where('is_read', false)->count()
            ]);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Delete all notifications or filtered notifications.
     */
    public function destroyAll(Request $request)
    {
        Notification::truncate();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Seluruh riwayat notifikasi telah dihapus.',
                'unread_count' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Seluruh riwayat notifikasi telah dihapus.');
    }
}
