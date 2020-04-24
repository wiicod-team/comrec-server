<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use App\Offer;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferRequest extends FormRequest
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
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'picture' => 'image',
            'type' => Rule::in(Offer::$Type),
            'status' => Rule::in(Offer::$Status),
            'amount'=>'required|numeric',
            'category_id'=>'required|integer|exists:categories,id',

        ];



        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
