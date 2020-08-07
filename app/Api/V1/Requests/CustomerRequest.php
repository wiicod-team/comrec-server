<?php

namespace App\Api\V1\Requests;

use App\Customer;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
            'email' => 'email|max:255',
            'pending_days' => 'numeric',
            'sale_network' => 'max:255',
            'status' => Rule::in(Customer::$Status),
            'creation_date'=>'required|date',
            'bvs_id'=>'min:0|max:255|unique:customers,bvs_id',

        ];

        if($this->method()=='PUT'){
            $rules['bvs_id'].=','.$this->route('customer');
        }


        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
