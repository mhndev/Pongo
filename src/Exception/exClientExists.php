<?php
namespace mhndev\Pongo\Exception;

use Throwable;

/**
 * Class exClientExists
 * @package mhndev\Pongo\Exception
 */
class exClientExists extends \Exception
{


    /**
     * exClientExists constructor.
     * @param $clientName
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($clientName, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Client with name (%s) not exists.', $clientName);

        parent::__construct($message, $code, $previous);
    }

}
