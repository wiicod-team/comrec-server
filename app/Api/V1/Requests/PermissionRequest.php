<?php

namespace App\Api\V1\Requests;

use App\Helpers\RuleHelper;
use Dingo\Api\Http\FormRequest;


class PermissionRequest extends FormRequest
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
            $ign =','. $this->route('permission');
        }
        $rules = [
            'description' => '',
            'name' => 'required|unique:roles,name'.$ign,
            'display_name' => 'required'
        ];


        return RuleHelper::get_rules($this->method(), $rules,[]);
    }
}
