<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Mail\AuthMail;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\UserProfileRequest;

class AuthUserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['otp'] = rand(1111, 9999);
        $data['password'] = isset($data['password']) ? Hash::make($data['password']) : null;

        $user = User::where('phone', $data['phone'])->first();

        if ($user && $user->is_verify == 1) {
            return ApiResponse::errorResponse(false, 'This account is already verified and cannot register again.');
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($user && $user->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->image));
            }
            $data['image'] = 'storage/' . $request->file('image')->store('uploads/images/users', 'public');
        }

        // إنشاء أو تحديث حساب الشيف
        $user = user::updateOrCreate(
            ['phone' => $data['phone'], 'email' => $data['email']],
            array_filter($data, fn($value) => $value !== null)
        );

        Mail::to($user->email)->send(new AuthMail($user->otp));

        return ApiResponse::sendResponse(true, 'user Account Created Successfully', new UserResource($user));
    }


    public function login(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string|exists:users,phone',
                'password' => 'required|string|min:8',
            ]);
            $user = User::where('phone', $request->phone)->first();
            if (!$user) {
                return ApiResponse::sendResponse(false, ('Phone number does not exist. Please register'));
            }

            if (!$user->is_verify) {
                return ApiResponse::sendResponse(false, ('Phone number not verified. Please verify to log in'));
            }

            if (!Hash::check($request->password, $user->password)) {
                return ApiResponse::sendResponse(false, 'Invalid credentials.');
            }
            if ($request->filled('fcm_token') && $user->fcm_token !== $request->fcm_token) {
                $user->update(['fcm_token' => $request->fcm_token]);
            }

            $user->tokens()->delete();
            $user["token"] = $user->createToken('Bearer ', ['app:all'])->plainTextToken;
            return ApiResponse::sendResponse(true, 'Login Successful!', new UserResource($user));
        } catch (Exception $e) {
            return ApiResponse::sendResponse(false, $e->getMessage());
        }
    }
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return ApiResponse::sendResponse(false, 'No authenticated user.');
            }
            $user->currentAccessToken()->delete();
            return ApiResponse::sendResponse(true, 'Logout Successful!');
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(false, 'Something went wrong.');
        }
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
            'phone' => 'required|exists:users,phone'
        ]);
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return ApiResponse::sendResponse(false, 'User not found');
        }

        if ($user->otp != $request->otp) {
            return ApiResponse::sendResponse(false, 'Invalid OTP.');
        }
        $user->update([
            'is_verify' => true,
        ]);

        return ApiResponse::sendResponse(true, 'Phone verified successfully');
    }
    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'phone' => [
                    'required',
                    'string',
                    'exists:users,phone',
                ],
            ]);

            $otp = rand(1111, 9999);
            $user = User::where('phone', $request->phone)->where('is_verify', true)->first();

            if (!$user) {
                return ApiResponse::sendResponse(false, 'User not found');
            }
            $user->update(['otp' => $otp]);
            // OTPVerification::sendMsg($user->phone, 'Tawsel-Hawe', "Your OTP for password reset is: $otp");
            Mail::to($user->email)->send(new AuthMail($user->otp));

            return ApiResponse::sendResponse(true, 'OTP sent successfully. Please verify to reset your password.');
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(false, 'Something went wrong.', [
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'phone' => [
                    'required',
                    'string',
                    'exists:users,phone',
                ],
                'password' => 'required|string|min:8|confirmed',

            ]);

            $user = User::where('phone', $request->phone)->where('is_verify', 1)->first();

            if (!$user) {
                return ApiResponse::sendResponse(false, 'User not found');
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);


            return ApiResponse::sendResponse(true, 'Password reset successfully.');
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(false, ("something_went_wrong"), [
                'error' => $e->getMessage(),
            ]);

        }
    }
    public function profile()
    {
        $user = Auth::guard('user')->user();
        return ApiResponse::sendResponse(true, 'Data Retrieve Successfully', new UserResource($user));
    }
    public function updateProfile(UserProfileRequest $request)
    {
        $user = Auth::guard('user')->user();
        $data = $request->validated();

        if (!isset($data['image']) || $data['image'] === null) {
            unset($data['image']);
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $path = 'uploads/images/users';

            if (!empty($user->image)) {
                $oldImagePath = str_replace('storage/', '', $user->image);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            $uploadedFilePath = $file->store($path, 'public');
            $data['image'] = 'storage/' . $uploadedFilePath;
        }

        if (!isset($data['password']) || empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return ApiResponse::sendResponse(true, 'Data Updated Successfully', new UserResource($user));
    }


    public function resetCode(Request $request)
    {
        try {
            $data = $request->validate([
                'phone' => 'required|string'
            ]);
            $user = User::where('phone', $request->phone)->first();
            if (!$user) {
                return ApiResponse::sendResponse(false, 'User not found');
            }
            $data['otp'] = rand(1111, 9999);
            $user->update($data);
            return ApiResponse::sendResponse(true, 'Code Resend Successful');
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(false, __("messages.something_went_wrong"), [
                'error' => $e->getMessage(),
            ]);
        }
    }
}