<?php

namespace App\Http\Requests\Admin\Archive;

use Illuminate\Foundation\Http\FormRequest;

class StoreArchiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->guard('admin')->user()->can('archive.create');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|in:active,archived,draft',
            'file' => 'required|file|max:102400', // 100MB max
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => trans('all.title_is_required'),
            'category_id.required' => trans('all.category_is_required'),
            'category_id.exists' => trans('all.selected_category_is_invalid'),
            'file.required' => trans('all.file_is_required'),
            'file.max' => trans('all.file_size_too_large'),
        ];
    }
}
