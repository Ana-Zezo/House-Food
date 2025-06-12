<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // عرض صفحة التسجيل
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // التعامل مع بيانات التسجيل
    public function register(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // إنشاء مستخدم جديد
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => '0000000000', // ← مؤقتًا لحد ما تظبط الفورم

        ]);

        // تسجيل دخول المستخدم مباشرة بعد التسجيل
        Auth::login($user);

        // تحويل المستخدم للصفحة الرئيسية
        return redirect()->route('home');
    }
}
