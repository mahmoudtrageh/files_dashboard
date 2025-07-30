<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdatePasswordRequest;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        
        return view('admin.pages.profile.edit', compact('admin'));
    }
    
    public function update(UpdateProfileRequest $request)
    {
        $admin = $request->user('admin');
    
        $admin->fill($request->only(['name', 'email']));
        
        if (isset($request->profile_image)) {
            $admin->clearMediaCollection('profile_image');
        
            $admin->addMediaFromRequest('profile_image')
                ->toMediaCollection('profile_image');
        }
        
        $admin->save();
        
        return to_route('admin.profile.edit')
                ->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
    
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $admin = $request->user('admin');
        
        $admin->update([
            'password' => Hash::make($request->newPassword),
        ]);
        
        return to_route('admin.profile.edit')->with('success', 'تم تحديث كلمة المرور بنجاح');
    }
}
