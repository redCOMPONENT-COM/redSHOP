<?php

namespace Paymill\Models\Request;

/**
 * Refund Model
 * Refunds are own objects with own calls for existing transactions.
 * The refunded amount will be credited to the account of the client.
 */
class Refund extends Base
{
    /**
     * @var string
     */
    private $_amount;
    
    /**
     * @var string
     */
    private $_description;

    /**
     * Creates an instance of the refund request model
     */
    function __construct()
    {
        $this->_serviceResource = 'Refunds/';
    }

    /**
     * Returns the amount
     * @return string
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * Sets the amount
     * @param string $amount
     * @return \Paymill\Models\Request\Refund
     */
    public function setAmount($amount)
    {
        $this->_amount = $amount;
        return $this;
    }

    /**
     * Returns the description
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Sets the description
     * @param string $description
     * @return \Paymill\Models\Request\Refund
     */
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }

    /**
     * Returns an array of parameters customized for the argumented methodname
     * @param string $method
     * @return array
     */
    public function parameterize($method)
    {
        $parameterArray = array();
        switch ($method) {
            case 'create':
                $parameterArray['amount'] = $this->getAmount();
                $parameterArray['description'] = $this->getDescription();
                break;
            case 'getOne':
                $parameterArray['count'] = 1;
                $parameterArray['offset'] = 0;
                break;
            case 'getAll':
                $parameterArray = $this->getFilter();
                break;
        }

        return $parameterArray;
    }
}
