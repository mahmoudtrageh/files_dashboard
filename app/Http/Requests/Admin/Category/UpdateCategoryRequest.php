<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('admin')->user()->can('category.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->category->id)
            ],
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:50',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    // Prevent setting self as parent
                    if ($value == $this->category->id) {
                        $fail(__('A category cannot be its own parent.'));
                    }

                    // Prevent circular reference
                    if ($value && $this->wouldCreateCircularReference($this->category->id, $value)) {
                        $fail(__('This would create a circular reference.'));
                    }
                }
            ],
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
            'name' => __('Category Name'),
            'description' => __('Description'),
            'color' => __('Color'),
            'icon' => __('Icon'),
            'parent_id' => __('Parent Category'),
            'is_active' => __('Status'),
            'sort_order' => __('Sort Order')
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('The category name is required.'),
            'name.unique' => __('A category with this name already exists.'),
            'color.regex' => __('The color must be a valid hex color code.'),
            'parent_id.exists' => __('The selected parent category does not exist.')
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

    /**
     * Check if setting parent would create circular reference
     */
    private function wouldCreateCircularReference($categoryId, $parentId): bool
    {
        $current = \App\Models\Category::find($parentId);

        while ($current) {
            if ($current->id == $categoryId) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }
}
