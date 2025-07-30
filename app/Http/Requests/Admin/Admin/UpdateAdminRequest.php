<?php

namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($this->admin),
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(10)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'is_super' => 'boolean',
            'status' => 'boolean',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'password.min' => 'كلمة المرور يجب أن تحتوي على 10 أحرف على الأقل.',
            'password.letters' => 'كلمة المرور يجب أن تحتوي على حروف.',
            'password.mixed_case' => 'كلمة المرور يجب أن تحتوي على حرف كبير وحرف صغير على الأقل.',
            'password.numbers' => 'كلمة المرور يجب أن تحتوي على رقم واحد على الأقل.',
            'password.symbols' => 'كلمة المرور يجب أن تحتوي على رمز خاص واحد على الأقل (مثل: !@#$%).',
            'password.uncompromised' => 'كلمة المرور هذه ظهرت في تسريب بيانات. الرجاء اختيار كلمة مرور مختلفة.',
        ];
    }
}
