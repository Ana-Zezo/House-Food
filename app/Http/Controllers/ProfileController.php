<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function index()
    {

        $admin = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }




    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();



        $admin->fill($request->validated());

        if ($admin->isDirty('email')) {
            $admin->email_verified_at = null;
        }

        if ($request->hasFile('profileImage')) {
            $image = $request->file('profileImage');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/admins'), $imageName);

            if ($admin->image && file_exists(public_path('uploads/admins/' . $admin->image))) {
                unlink(public_path('uploads/admins/' . $admin->image));
            }

            $admin->image = $imageName;
        }

        $admin->job = $request->input('job');
        $admin->company = $request->input('company');
        $admin->country = $request->input('country');
        $admin->address = $request->input('address');
        $admin->phone = $request->input('phone');

        $admin->save();

        return Redirect::route('dashboard.profile.index')->with('status', 'profile-updated');
    }



    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $admin->password = Hash::make($request->password);

        /** @var \App\Models\Admin $admin */
        $admin->save();


        return redirect()->route('dashboard.profile.index')->with('success', 'Password changed successfully.');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
