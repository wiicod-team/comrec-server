<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferProductUnitRequest extends FormRequest
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
            'product_unit_id'=>'required|integer|exists:product_units,id|unique_with:offer_product_units,offer_id',
            'offer_id'=>'required|integer|exists:offers,id',

        ];



        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
