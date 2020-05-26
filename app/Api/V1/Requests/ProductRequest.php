<?php

namespace App\Api\V1\Requests;

use App\Bill;
use App\Helpers\RuleHelper;
use App\Http\Requests\Request;
use App\Product;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            $ign =','. $this->route('bill');
        }
        $rules = [
            'name' => 'required|max:255',
            'picture' => 'image',
            'category_id'=>'required|integer|exists:categories,id',

        ];




        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
