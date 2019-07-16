<?php

namespace UrApi\Utils;

use Illuminate\Http\Response as IlluminateResponse;

class Response extends IlluminateResponse
{
    use UtilsTrait;

    protected $conHeaders = ['Content-Type' => ['value' => 'application/json', 'overWrite' => true]];

    protected $noHeaders = ['X-Powered-By'];

    protected $status_code;

    public $body;

    public function __construct(array $data, $info = null, $code = 200)
    {

        parent::__construct();

        if ($this->seemsLaravelApplication()) {
            $this->constructLaravel();
        } else {
            //Workaround, si estamos usando la libreria en otro framework distinto a Laravel o Lumen...
            $this->constructOther($info);
        }

        $this->body->append();
        $this->status_code = $code;
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
        $this->body = new ResponseBody
            (
            config('urapi.general.api.name'),
            config('urapi.general.api.version'),
            config('urapi.general.app.name'),
            config('urapi.general.app.version')
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
        $this->body = new ResponseBody($info[0], $info[1], $info[2], $info[3]);

    }
    public function send()
    {
        foreach ($this->conHeaders as $hname => $hvalue) {
            $this->header($hname, $hvalue, true);
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
