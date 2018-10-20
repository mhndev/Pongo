<?php
namespace mhndev\Pongo;

use mhndev\Pongo\Exception\exClientExists;
use \MongoDB;

/**
 * Class MongoDriverManager
 * @package mhndev\order
 */
class MongoDriverManager
{

    const CLIENT_DEFAULT = 'master';

    static protected $CLIENT_DEFAULT = self::CLIENT_DEFAULT;

    protected $clients = array(
        # 'clientName' => MongoDB\Client,
    );

    /**
     * Attain Connection Client
     *
     * @param string $clientName
     *
     * @return MongoDB\Client
     * @throws exClientExists
     */
    function byClient($clientName = self::CLIENT_DEFAULT)
    {
        if (!$this->hasClient($clientName))
            throw new exClientExists($clientName);

        $client = $this->clients[$clientName];

        return $client;
    }


    /**
     * Add Client Connection
     *
     * @param MongoDB\Client $clientMongo
     * @param string $clientName
     *
     * @return $this
     * @throws \Exception
     */
    function addClient(MongoDB\Client $clientMongo, $clientName)
    {
        if ($this->hasClient($clientName))
            throw new \Exception(sprintf('Client with name (%s) already exists and cant be replaced.', $clientName));

        $this->clients[$clientName] = $clientMongo;
        return $this;
    }


    /**
     * Has Client Connection?
     *
     * @param string $clientName
     *
     * @return boolean
     */
    function hasClient($clientName)
    {
        return array_key_exists($clientName, $this->clients);
    }
    /**
     * Set Default Client Name
     *
     * @param string $clientName
     */
    static function setDefaultClient($clientName = self::CLIENT_DEFAULT)
    {
        self::$CLIENT_DEFAULT = $clientName;
    }

    /**
     * @param $name
     * @return MongoDB\Client
     * @throws \Exception
     */
    function __get($name)
    {
        return $this->byClient($name);
    }

    /**
     * @param $name
     * @param $value
     * @return MongoDriverManager
     * @throws \Exception
     */
    function __set($name, $value)
    {
        return $this->addClient($value, $name);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    function __call($name, $arguments)
    {
        $masterClient = $this->byClient();

        return call_user_func_array(array($masterClient, $name), $arguments);
    }

    /**
     * Build Object With Provided Options
     *
     * @param array|\Traversable $options Associated Array
     * @param bool $throwException Throw Exception On Wrong Option
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    function with($options, $throwException = false)
    {
        foreach ($options as $name => $conf) {
            $uri        = $conf['host'];
            $uriOptions = (isset($conf['options_uri'])) ? $conf['options_uri'] : array();
            $options    = (isset($conf['options'])) ? $conf['options'] : array();

            $this->addClient(new MongoDB\Client($uri, $uriOptions, $options), $name);
        }
    }

}
