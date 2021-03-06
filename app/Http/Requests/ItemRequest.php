<?php

namespace App\Http\Requests;

use App\Rules\ValidateCategory;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string name
 * @property int price
 * @property string description
 * @property int weight
 * @property string color
 */
class ItemRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => 'required|string|unique:items,name',
            'price' => 'required|integer',
            'category_id' => ['required', 'integer', new ValidateCategory],
        ];
    }
}
