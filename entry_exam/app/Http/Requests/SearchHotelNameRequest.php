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
            'hotel_name' => ['nullable', 'string', 'max:255'],
            'prefecture_id' => ['nullable', 'integer', 'exists:prefectures,prefecture_id'],
        ];
    }
}
