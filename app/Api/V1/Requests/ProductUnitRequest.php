<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUnitRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'quantity' => 'required|numeric',
            'unit' => 'required|max:255',
            'product_id'=>'required|integer|exists:products,id',

        ];




        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
