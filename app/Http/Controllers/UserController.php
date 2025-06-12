<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index()
    {
        $users = User::latest()->paginate(8);
        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض بيانات مستخدم محدد
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }


    public function toggleStatus(User $user)
    {
        try {
            // اقلب الحالة من active إلى block أو العكس
            $user->status = $user->status === 'active' ? 'block' : 'active';
            $user->save();

            return response()->json([
                'status' => true,
                'new_status' => $user->status,
                'message' => 'Status updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Toggle status failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to update status.'
            ], 500);
        }
    }
}
