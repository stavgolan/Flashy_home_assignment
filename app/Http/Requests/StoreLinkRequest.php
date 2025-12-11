<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Defines the validation rules for creating a new shortened link
     * @return array{slug: string[], target_url: string[]}
     */
    public function rules(): array
    {
        return [
            'target_url' => ['required', 'url'], //ensures valid URL
            'slug' => ['nullable', 'alpha_dash', 'unique:links,slug'], //alpha_dash (Only letters, numbers, dashes, underscores), slug is and optional
        ];
    }

}
