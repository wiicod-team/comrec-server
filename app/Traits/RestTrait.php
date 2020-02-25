<?php
/**
 * Created by PhpStorm.
 * User: Ets Simon
 * Date: 12/06/2017
 * Time: 11:57
 */

namespace App\Traits;

trait RestTrait{

    protected $foreign = [];
    protected $files = [];



    public function getForeign()
    {
        return $this->foreign;
    }


    public function getFiles()
    {
        return $this->files;
    }

    public function getLabel()
    {
        return $this->id ;
    }
    public function getAppends()
    {
        return $this->appends ;
    }
    public function setAppends(Array $apps)
    {

        $this->appends=$apps;
    }

}