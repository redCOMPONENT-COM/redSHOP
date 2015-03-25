<?php

namespace Paymill\API;

/**
 * Interface
 */
abstract class CommunicationAbstract
{

    /**
     * Perform API and handle exceptions
     *
     * @param $action
     * @param array $params
     * @param string $method
     * @return mixed
     */
    abstract protected function requestApi($action = '', $params = array(), $method = 'POST');
}
