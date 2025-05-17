<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Chef;
use App\Mail\AuthMail;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Traits\UploadFileTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChefResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Chef\CreateChefRequest;
use App\Http\Requests\Chef\ChefProfileRequest;

class ChefAuthController extends Controller
{
   
    public function register(CreateChefRequest $request)
    {
        $data = $request->validated();
        $data['otp'] = rand(1111, 9999);
        $data['password'] = isset($data['password']) ? Hash::make($data['password']) : null;

        $chef = Chef::where('phone', $data['phone'])->first();

        if ($chef && $chef->is_verify == 1) {
            return ApiResponse::errorResponse(false, ('This account is already verified and cannot register again.'));
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($chef && $chef->image) {
                Storage::disk('public')->delete(str_replace('storage/', '', $chef->image));
            }
            $data['image'] = 'storage/' . $request->file('image')->store('uploads/images/chefs', 'public');
        }
        Mail::to($chef->email)->send(new AuthMail($chef->otp));

        $chef = Chef::updateOrCreate(
            ['phone' => $data['phone'], 'email' => $data['email']],
            array_filter($data, fn($value) => $value !== null)
        );

        return ApiResponse::sendResponse(true, ('Chef Account Created Successfully'), new ChefResource($chef));
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string|exists:chefs,phone',
                'password' => 'required|string|min:8',
            ]);
            $chef = Chef::where('phone', $request->phone)->first();
            if (!$chef) {
                return ApiResponse::errorResponse(false, ('Phone number does not exist. Please register'));
            }

            if (!$chef->is_verify) {
                return ApiResponse::errorResponse(false, ('Phone number not verified. Please verify to log in'));
            }

            if (!Hash::check($request->password, $chef->password)) {
                return ApiResponse::errorResponse(false, 'Invalid Credentials');
            }

            if ($request->filled('fcm_token') && $chef->fcm_token !== $request->fcm_token) {
                $chef->update(['fcm_token' => $request->fcm_token]);
            }

            $chef->tokens()->delete();
            $chef["token"] = $chef->createToken('Bearer ', ['app:all'])->plainTextToken;

            return ApiResponse::sendResponse(true, 'Login Successful!', new ChefResource($chef));
        } catch (Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage());
        }
    }
    public function logout(Request $request)
    {
        try {
            $chef = $request->user();
            if (!$chef) {
                return ApiResponse::errorResponse(false, 'No authenticated chef.');
            }
            $chef->currentAccessToken()->delete();
            return ApiResponse::sendResponse(true, 'Logout Successful!');
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage());
        }
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
            'phone' => 'required|exists:chefs,phone'
        ]);
        $chef = Chef::where('phone', $request->phone)->first();

        if (!$chef) {
            return ApiResponse::errorResponse(false, 'Chef not found');
        }

        if ($chef->otp != $request->otp) {
            return ApiResponse::errorResponse(false, 'Invalid OTP.');
        }
        $chef->update([
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
                    'exists:chefs,phone',
                ],
            ]);

            $otp = rand(1111, 9999);
            $chef = Chef::where('phone', $request->phone)->where('is_verify', true)->first();

            if (!$chef) {
                return ApiResponse::errorResponse(false, 'Chef not found');
            }
            $chef->update(['otp' => $otp, 'is_verify' => true]);
            Mail::to($chef->email)->send(new AuthMail($chef->otp));

            return ApiResponse::sendResponse(true, 'OTP sent successfully. Please verify to reset your password.');
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage(), [
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
                    'exists:chefs,phone',
                ],
                'password' => 'required|string|min:8|confirmed',

            ]);

            $chef = Chef::where('phone', $request->phone)->where('is_verify', 1)->first();

            if (!$chef) {
                return ApiResponse::errorResponse(false, 'Chef not found');
            }

            $chef->update([
                'password' => Hash::make($request->password),
            ]);


            return ApiResponse::sendResponse(true, 'Password reset successfully.');
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(false, "Something went wrong");
        }
    }

    public function profile()
    {
        $chef = Auth::guard('chef')->user();
        return ApiResponse::sendResponse(true, 'Data Retrieve Successfully', new ChefResource($chef));
    }

    public function updateProfile(ChefProfileRequest $request)
    {
        $chef = Auth::guard('chef')->user();
        $data = $request->validated();

        if (!isset($data['image']) || $data['image'] === null) {
            unset($data['image']);
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $path = 'uploads/images/chefs';


            if (!empty($chef->image)) {
                UploadFileTrait::delete($chef->image);
            }
            UploadFileTrait::store($file, $path);
        }

        if (!isset($data['password']) || empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $chef->update($data);
        return ApiResponse::sendResponse(true, 'Data Updated Successfully', new ChefResource($chef));
    }


    public function resetCode(Request $request)
    {
        try {
            $data = $request->validate([
                'phone' => 'required|string|exists:chefs,phone'
            ]);
            $chef = Chef::where('phone', $request->phone)->first();
            if (!$chef) {
                return ApiResponse::sendResponse(false, 'Chef not found');
            }
            $data['otp'] = rand(1111, 9999);
            $chef->update($data);
            Mail::to($chef->email)->send(new AuthMail($chef->otp));

            return ApiResponse::sendResponse(true, 'Code Resend Successful');
        } catch (\Exception $e) {
            return ApiResponse::errorResponse(false, $e->getMessage());

        }
    }
}