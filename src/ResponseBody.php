<?php

namespace UrApi\Utils;

class ResponseBody
{
    use UtilsTrait;

    protected $appName;

    protected $appVersion;

    protected $apiName;

    protected $apiVersion;

    protected $error;

    protected $result;

    protected $data;

    protected $debug;

    public function __construct($appName = 'app_name', $appVersion = 'app_version', $apiName = 'api_name', $apiVersion = 'api_version', ResponseResult $result = null, $error = false)
    {
        $this->appName = $appName;
        $this->appVersion = $appVersion;
        $this->apiName = $apiName;
        $this->apiVersion = $apiVersion;

        // $this->data = []; //Uncomment if data has to be required in response

        $this->error = $error;

        $this->result = $result ?: new ResponseResult();
    }
    public function append(array $data = [], $replace = false)
    {
        global $otoa, $atoo;
        if (count($data) === 0) {
            return false;
        }
        if (!isset($this->data) || is_null($this->data)) {
            $this->data = [];
        }
        //Append de los elementos opcionales que puede tener el Response.
        foreach ($data as $key => $value) {
            if (!isset($this->data[$key])) {
                //New Value
                $this->data[$key] = $value;
            } else {
                foreach ($value as $k => $v) {
                    if (isset($this->data[$key][$k])) {
                        //Replace ?
                        $this->data[$key][$k] = $replace ? $v : $this->data[$key][$k];
                    } else {
                        //Append
                        $this->data[$key][$k] = $v;
                    }
                }
            }

        }
        return true;
    }
    public function getContent($data = null)
    {
        $content = $this->toArray();
        return $content;
    }

}
