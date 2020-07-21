<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * @param $key
     * @return mixed
     */
    public function getField($key)
    {
        return $this->{$this->fieldMapper[$key]};
    }

    /**
     * @param $key
     * @param $value
     */
    public function setField($key, $value)
    {
        $this->{$this->fieldMapper[$key]} = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getFieldName($key)
    {
        return $this->fieldMapper[$key];
    }
}
