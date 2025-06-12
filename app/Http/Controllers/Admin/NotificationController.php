<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->paginate(5);
        return view('admin.notifications.index', compact('notifications'));
    }


    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return view('admin.notifications.show', compact('notification'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = 1;
        $notification->save();

        return redirect()->route('admin.notifications.show', ['id' => $id])->with('success', 'Notification marked as read.');
    }


}
