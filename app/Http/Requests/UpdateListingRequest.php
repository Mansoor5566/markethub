<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('seller');
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'condition'   => ['required', 'in:new,like_new,good,fair'],
            'price'       => ['required', 'numeric', 'min:1'],
            'quantity'    => ['required', 'integer', 'min:1'],
            'location'    => ['required', 'string', 'max:255'],
            'status'      => ['required', 'in:draft,active,paused'],
            'images'      => ['nullable', 'array', 'max:10'],
            'images.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.*.max'   => 'Each image must be under 5MB.',
            'images.*.mimes' => 'Only JPG, PNG and WEBP images are allowed.',
        ];
    }
}