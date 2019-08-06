<?php

namespace UrApi\Utils;

class ResponseResult
{

    use UtilsTrait;

    protected $statusCode;

    protected $statusDescription;

    public $validationErrors;

    public $otherErrors;

    public function __construct($statusCode = 200, $statusDescription = '', $validationErrors = [], $otherErrors = [])
    {
        $this->statusCode = $statusCode;
        $this->statusDescription = $statusDescription;
        $this->validationErrors = $validationErrors;
        $this->otherErrors = $otherErrors;

    }

}
