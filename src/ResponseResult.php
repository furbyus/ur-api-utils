<?php

namespace UrApi\Utils;

class ResponseResult
{

    use UtilsTrait;

    protected $status_code;

    protected $status_description;

    protected $validation_errors;

    protected $other_errors;

    public function __construct($status_code = 200, $status_description = '', $validation_errors = [], $other_errors = [])
    {
        $this->status_code = $status_code;
        $this->status_description = $status_description;
        $this->validation_errors = $validation_errors;
        $this->other_errors = $other_errors;

    }

}
