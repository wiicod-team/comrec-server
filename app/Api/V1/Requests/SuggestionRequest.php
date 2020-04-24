<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use App\Suggestion;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuggestionRequest extends FormRequest
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
        $ign = null;

        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|max:500',
            'status' => Rule::in(Suggestion::$Status),
            'user_id'=>'required|integer|exists:users,id',

        ];


        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
