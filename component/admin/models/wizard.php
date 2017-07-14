<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


require_once 'components/com_redshop/models/configuration.php';

class RedshopModelWizard extends RedshopModelConfiguration
{
    public $_tax_rates = null;

    /**
     *
     * @return mixed
     *
     */
    public function getTaxRates()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(
            $db->quoteName(array(
                'tax_group_id',
                'id',
                'tax_country',
                'tax_rate'
            ))
        );
        $query->from($db->quoteName('#__redshop_tax_rate'));
        $query->where($db->quoteName('tax_group_id') . '=' . 1);

        return $this->_getList($query);
    }

    /*
     * get Shop Currency Support
     *
     * @params: string $currency comma separated countries
     * @return: array stdClass Array for Shop country
     *
     * currency_code as value
     * currency_name as text
     */
    public function getCurrency($currency = "")
    {
        $db = JFactory::getDbo();

        $where = "";

        if ($currency)
        {
            $where = " WHERE currency_code IN ('" . $currency . "')";
        }

        $query = 'SELECT currency_code as value, currency_name as text FROM #__redshop_currency' . $where . ' ORDER BY currency_name ASC';
        $db->setQuery($query);

        return $db->loadObjectlist();
    }
}
