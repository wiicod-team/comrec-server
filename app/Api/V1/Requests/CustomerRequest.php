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
        $ign = null;
        if ($this->method() == 'PUT'){
            $ign =','. $this->route('bills');
        }
        $rules = [
            'name' => 'required|max:255',
            'sale_network' => 'max:255',
            'status' => Rule::in(Customer::$Status),
            'creation_date'=>'required|date',

        ];


        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
