<?php

namespace UrApi\Utils;

use Illuminate\Http\Response as IlluminateResponse;

class Response extends IlluminateResponse
{
    use UtilsTrait;
    protected  $statusMessages = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',

        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',

        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded',
    );
    protected $conHeaders = ['Content-Type' => ['value' => 'application/json', 'overWrite' => true]];

    protected $noHeaders = ['X-Powered-By'];

    protected $body;

    public function __construct(array $data, $info = null, $code = 200)
    {

        parent::__construct();
        $this->statusCode = $code;
        $this->statusText = $this->statusMessages[$code] ?: '';
        if ($this->seemsLaravelApplication()) {
            $this->constructLaravel();
        } else {
            //Workaround, si estamos usando la libreria en otro framework distinto a Laravel o Lumen...
            $this->constructOther($info);
        }
        $this->body->append($data);
        $this->setContent($this->getBody());
        $this->hprepare();
    }
    public function seemsLaravelApplication($return = false)
    {
        $toret = [];
        if (!isset($GLOBALS['kernel']) || !isset($GLOBALS['app'])) {
            return $return ? [false, $toret] : false;
        }
        $toret[] = 'Passed kernel & app exists';
        $kernel = $GLOBALS['kernel'];
        $app = $this->getReflectedProperty('app', $kernel);
        $instances = $this->getReflectedProperty('instances', $app);
        if (!(isset($app) && isset($instances))) {
            return $return ? [false, $toret] : false;
        }
        $toret[] = 'Passed kernel ->app & ->instances exists';
        if (!isset($instances['path.base']) || !isset($instances['path.config'])) {
            return $return ? [false, $toret] : false;
        }
        $toret[] = 'Passed instances path.base & instances path.config exists';
        $comparePath = $instances['path.base'] . DIRECTORY_SEPARATOR . 'config';
        if ($comparePath !== $instances['path.config']) {
            return $return ? [false, $toret] : false;
        }
        return $return ? [true, $toret] : true; //Seems like a Laravel Application
    }
    private function constructLaravel()
    {
        $result = new ResponseResult($this->statusCode, $this->statusText);
        $this->body = new ResponseBody
            (
            config('urapi.general.app.name'),
            config('urapi.general.app.version'),
            config('urapi.general.api.name'),
            config('urapi.general.api.version'),
            $result
        );

    }
    private function getReflectedProperty(String $propertyName, $targetObject)
    {
        $reflected = new \ReflectionObject($targetObject);
        $property = $reflected->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($targetObject);
    }
    private function constructOther(array $info)
    {
        if (!(count($info) === 4) || !array_key_exists(0, $info)) {
            throw new \Exception("Error Building ElectryResponse Instance, 'info' passed to 'new' operator isn't an array of length 4, passed an array of length " . count($info), 1);
        }
        $result = new ResponseResult($this->statusCode, $this->statusText);
        $this->body = new ResponseBody($info[0], $info[1], $info[2], $info[3], $result);

    }
    public function append(array $data = [], $replace = false)
    {
        $this->body->append($data, $replace);
        return $this->hprepare()->setContent($this->getBody());
    }
    private function getBody(){
        return json_encode($this->body->toArray());
    }
    private function hprepare()
    {
        foreach ($this->conHeaders as $hname => $hvalue) {
            $this->header($hname, $hvalue['value'], $hvalue['overWrite']);
        }
        if (count($this->noHeaders) > 0) {
            foreach ($this->noHeaders as $header) {
                header_remove($header);
            }
        }
        return $this;
    }
    public function removeHeader($header = '')
    {
        if (!($header == '')) {
            array_merge($this->noHeaders, [$header]);
            if (key_exists($header, $this->conHeaders)) {
                unset($this->conHeaders[$header]);
            }
        }
    }
    public function replaceHeader($hname, $hval)
    {return $this->addHeader($hname, $hval, true);}
    public function addHeader($hname, $hvalue = '', $replace = false)
    {
        if (!isset($hname) || $hvalue === '') {
            return false;
        }
        if (!key_exists($hname, $this->conHeaders)) {

        }
        $this->conHeaders[$hname] = ['value' => $hval, 'overWrite' => $replace];
        return true;
    }
}

trait UtilsTrait
{

    public function toArray()
    {
        $return = [];
        foreach ($this as $key => $value) {
            if (!\is_callable($this->{$key}) && !is_null($value)) {
                $return[$key] = $value;
            }
            if (is_object($this->{$key}) && method_exists($this->{$key}, 'toArray')) {
                $return[$key] = $this->{$key}->toArray();
            }

        }
        return $return;
    }
    public function toObject($json = false)
    {
        $return = new \stdClass();
        foreach ($this as $key => $value) {
            if (!\is_callable($this->{$key}) && !is_null($value)) {
                $return->{$key} = $value;
            }
            if (is_object($this->{$key}) && method_exists($this->{$key}, 'toArray')) {
                $return->{$key} = $this->{$key}->toObject();
            }

        }
        return ($json) ? json_encode($return) : $return;
    }
}
