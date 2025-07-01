<?php

namespace admin\settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [                   
            'title' => 'required|string|min:3|max:255|unique:settings,title',
            'config_value' => 'required|string|min:3|max:65535',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
