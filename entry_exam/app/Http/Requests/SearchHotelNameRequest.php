<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchHotelNameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_name' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_name.required' => __('何も入力されていません'),
        ];
    }
}
