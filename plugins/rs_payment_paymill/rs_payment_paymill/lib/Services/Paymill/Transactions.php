<?php

require_once ('Base.php');

/**
 * Paymill API wrapper for transactions resource
 */
class Services_Paymill_Transactions extends Services_Paymill_Base
{
    /**
     * {@inheritDoc}
     */
    protected $_serviceResource = 'transactions/';

    /**
     * General REST PUT verb
     * Update resource item
     *
     * @param array $itemData
     *
     * @return array item updated or null
     */
    public function update(array $itemData = array())
    {
        throw new Services_Paymill_Exception( __CLASS__ . " does not support " . __METHOD__, "404");
    }

    /**
     * General REST DELETE verb
     * Delete or inactivate/cancel resource item
     * 
     * @param string $clientId
     *
     * @return array item deleted
     */
    public function delete($clientId = null)
    {
        throw new Services_Paymill_Exception( __CLASS__ . " does not support " . __METHOD__, "404");
    }
}