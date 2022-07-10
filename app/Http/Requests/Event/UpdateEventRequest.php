<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => 'required',
            'location' => 'required|max:255',
            'start_datetime' => 'required|date_format:Y-m-d H:i:s',
            "end_datetime" => "required|date_format:Y-m-d H:i:s",
        ];
    }
}
