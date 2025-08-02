<?php

namespace App\Http\Requests\Admin\Archive;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArchiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->user()->can('archive.edit');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|in:active,archived,draft',
            'file' => 'nullable|file|max:102400', // 100MB max, optional for updates
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => trans('all.title_is_required'),
            'category_id.required' => trans('all.category_is_required'),
            'category_id.exists' => trans('all.selected_category_is_invalid'),
            'file.max' => trans('all.file_size_too_large'),
        ];
    }
}
