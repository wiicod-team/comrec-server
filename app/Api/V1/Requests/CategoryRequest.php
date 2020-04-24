<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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

        $rules = [
            'name' => 'required|max:255',
            'description' => 'required|max:255',

        ];

        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
