<?php

namespace App\Http\Requests\Admin\Profile;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currentPassword' => ['required', 'string', 'min:8'],
            'newPassword' => [
                'required',
                'confirmed',
                'different:currentPassword',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised() // Checks against known data breaches
            ],
            'newPassword_confirmation' => ['required']
        ];
    }
    
    protected function checkCurrentPassword(): bool
    {
        return Hash::check(
            $this->currentPassword, 
            Auth::guard('admin')->user()->password
        );
    }

    public function messages(): array
    {
        return [
            'currentPassword.required' => 'كلمة المرور الحالية مطلوبة',
            'currentPassword.min' => 'كلمة المرور الحالية يجب أن تكون على الأقل 8 أحرف',
            'newPassword.required' => 'كلمة المرور الجديدة مطلوبة',
            'newPassword.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق',
            'newPassword.different' => 'كلمة المرور الجديدة يجب أن تكون مختلفة عن كلمة المرور الحالية',
            'newPassword_confirmation.required' => 'تأكيد كلمة المرور الجديدة مطلوب',
        ];
    }
}
