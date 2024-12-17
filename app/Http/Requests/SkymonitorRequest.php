<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkymonitorRequest extends FormRequest
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
			'user_id' => 'required',
			'city' => 'required|string',
			'email' => 'string',
			'phone' => 'string',
			'uv_index_threshold' => 'required',
			'precipitation_threshold' => 'required',
        ];
    }
}
