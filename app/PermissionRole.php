<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PermissionRole extends Model
{
    //
    use RestTrait;

    protected $table = 'permission_role';
    public $timestamps = false;
    protected $fillable = ['permission_id','role_id'];
    protected $primaryKey = ['permission_id','role_id'];
    public $incrementing = false;


    public function getLabel()
    {
        return $this->role_id.'-'.$this->permission_id ;
    }

    public function permission(){
        return $this->belongsTo(Permission::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('permission_id', '=', $this->getAttribute('permission_id'))
            ->where('role_id', '=', $this->getAttribute('role_id'));
        return $query;
    }
}
