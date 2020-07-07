<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelRedshop extends RedshopModel
{
    public $_table_prefix = null;

    /**
     * RedshopModelRedshop constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_table_prefix = '#__redshop_';
        $this->_filteroption = 3;
    }

    /**
     * Method for insert demo content
     *
     * @return  boolean
     *
     * @since   1.6
     */
    public function demoContentInsert()
    {
        return \Redshop\Demo\Install::demoContentInsert();
    }

    /**
     * @return |null
     * @throws Exception
     */
    public function getNewCustomers()
    {
        return \Redshop\User\Helper::getNewCustomers();
    }

    /**
     * @return |null
     * @throws Exception
     */
    public function getNewOrders()
    {
        return \Redshop\Order\Helper::getNewOrders();
    }

    /**
     * @param $userId
     *
     * @return mixed
     */
    public function getUser($userId)
    {
        $user = \RedshopHelperUser::getUserInformation($userId);

        return $user->user_name;
    }

    /**
     * @param   int  $id
     *
     * @return |null
     * @throws Exception
     */
    public function getTotalOrder($id = 0)
    {
        return \Redshop\Order\Helper::getTotalOrderById($id);
    }

    /**
     * @param $userId
     *
     * @return float|mixed
     * @throws Exception
     */
    public function getOrderTotalAmount($userId)
    {
        return \Redshop\Order\Helper::getOrderTotalAmountByUserId($userId);
    }

    /**
     * @param $userId
     *
     * @return float
     * @throws Exception
     */
    public function getAvgAmount($userId)
    {
        return \Redshop\Order\Helper::getAvgAmountById($userId);
    }

    /**
     * @param $userId
     *
     * @return object
     */
    public function getUserInfo($userId)
    {
        return \RedshopHelperUser::getUserInformation($userId);
    }

    /**
     * @return mixed
     */
    public function getStatisticDashboard()
    {
        return \Redshop\Statistic\Helper::getStatisticDashboard();
    }

    /**
     * Method for insert demo Manufacturers
     *
     * @return void
     */
    private function demoManufacturers()
    {
        return \Redshop\Demo\Install::demoManufacturers();
    }
}
