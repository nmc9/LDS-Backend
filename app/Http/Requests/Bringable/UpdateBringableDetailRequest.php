<?php

namespace App\Http\Requests\Bringable;

use App\Library\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBringableDetailRequest extends FormRequest
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
            'name' => 'nullable',
            'notes' => 'nullable',
            'importance' => ['nullable', Rule::in(Constants::BRINGABLE_LEVELS)],
        ];
    }
}
