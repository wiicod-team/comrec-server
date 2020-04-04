<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RoleUser extends Model
{
    //
    use RestTrait;

    protected $table = 'role_user';
    public $timestamps = false;
    protected $fillable = ['user_id','role_id','user_type'];
    protected $primaryKey = ['user_id','role_id'];
    public $incrementing = false;

    public function getLabel()
    {
        return $this->user_id.'-'.$this->role_id ;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('user_id', '=', $this->getAttribute('user_id'))
            ->where('role_id', '=', $this->getAttribute('role_id'));
        return $query;
    }
}
