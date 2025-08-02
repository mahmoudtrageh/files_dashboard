<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('admin')->user()->can('category.create');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => trans('all.Category Name'),
            'parent_id' => trans('all.Parent Category'),
            'is_active' => trans('all.Status'),
            'sort_order' => trans('all.Sort Order')
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'name.required' => trans('all.The category name is required.'),
            'name.unique' => trans('all.A category with this name already exists.'),
            'parent_id.exists' => trans('all.The selected parent category does not exist.')
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'sort_order' => $this->integer('sort_order', 0)
        ]);
    }
}
