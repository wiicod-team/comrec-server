<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class BillRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'status' => Rule::in(Bill::$Status),
            'creation_date'=>'required|date',
            'customer_id'=>'required|integer|exists:customers,id'

        ];


        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
