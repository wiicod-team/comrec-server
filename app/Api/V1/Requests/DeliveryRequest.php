<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Delivery;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryRequest extends FormRequest
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
            'town' => 'required|max:255',
            'district' => 'required|max:255',
            'road' => 'required|max:255',
            'note' => 'max:255',
            'status' => Rule::in(Delivery::$Status),
            'invoice_id'=>'required|integer|exists:invoices,id|unique:deliveries,invoice_id',

        ];

        if($this->method()=='PUT'){
            $rules['invoice_id'].=','.$this->route('delivery');
        }


        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
