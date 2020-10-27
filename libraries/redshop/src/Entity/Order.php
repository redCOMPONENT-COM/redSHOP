<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity;

defined('_JEXEC') or die;

/**
 * Order Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class Order extends Entity
{
    /**
     * @var   \Redshop\Entities\Collection
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $orderItems;

    /**
     * @var   \Redshop\Entity\OrderPayment
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $payment;

    /**
     * @var    \Redshop\Entities\Collection
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $users;

    /**
     * @var   \Redshop\Entity\OrderUser
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $billing;

    /**
     * @var   \Redshop\Entity\OrderUser
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $shipping;

    /**
     * @var   array
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $statusLog;

    /**
     * Get the associated table
     *
     * @param string $name Main name of the Table. Example: Article for ContentTableArticle
     *
     * @return  boolean|\JTable
     * @since   __DEPLOY_VERSION__
     */
    public function getTable($name = null)
    {
        return \JTable::getInstance('Order_Detail', 'Table');
    }

    /**
     * Method for get order items for this order
     *
     * @return   \Redshop\Entities\Collection   Redshop\Entities\Collection if success. Null otherwise.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getOrderItems()
    {
        if (!$this->hasId()) {
            return null;
        }

        if (null === $this->orderItems) {
            $this->loadOrderItems();
        }

        return $this->orderItems;
    }

    /**
     * Method for load order items for this order
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadOrderItems()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->orderItems = new \Redshop\Entities\Collection;

        $db = \JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_order_item'))
            ->where($db->qn('order_id') . ' = ' . $this->getId());
        $orderItems = $db->setQuery($query)->loadObjectList();

        if (empty($orderItems)) {
            return $this;
        }

        foreach ($orderItems as $orderItem) {
            $entity = \Redshop\Entity\OrderItem::getInstance($orderItem->order_item_id);
            $entity->bind($orderItem);

            $this->orderItems->add($entity);
        }

        return $this;
    }

    /**
     * Method for get order status log for this order
     *
     * @return   array   Redshop\Entities\Collection if success. Null otherwise.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getStatusLog()
    {
        if (!$this->hasId()) {
            return null;
        }

        if (null === $this->statusLog) {
            $this->loadStatusLog();
        }

        return $this->statusLog;
    }

    /**
     * Method for load order status log for this order
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadStatusLog()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $db = \JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('l.*')
            ->select($db->qn('s.order_status_name'))
            ->from($db->qn('#__redshop_order_status_log', 'l'))
            ->leftJoin(
                $db->qn('#__redshop_order_status', 's') . ' ON '
                . $db->qn('l.order_status') . ' = ' . $db->qn('s.order_status_code')
            )
            ->where($db->qn('l.order_id') . ' = ' . $this->getId());

        $this->statusLog = $db->setQuery($query)->loadObjectList();

        return $this;
    }

    /**
     * Method for get payment for this order
     *
     * @return   \Redshop\Entity\OrderPayment   Payment data if success. Null otherwise.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getPayment()
    {
        if (!$this->hasId()) {
            return null;
        }

        if (null === $this->payment) {
            $this->loadPayment();
        }

        return $this->payment;
    }

    /**
     * Method for load payment of this order
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadPayment()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->payment = \Redshop\Entity\OrderPayment::getInstance();

        $db = \JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_order_payment'))
            ->where($db->qn('order_id') . ' = ' . (int)$this->getId());
        $result = $db->setQuery($query)->loadObject();

        if (empty($result)) {
            return $this;
        }

        $this->payment = \Redshop\Entity\OrderPayment::getInstance($result->payment_order_id)->bind($result);
        $this->payment->loadPlugin();

        return $this;
    }

    /**
     * Method for get billing information of this order
     *
     * @return   \Redshop\Entity\OrderUser   User infor if success. Null otherwise.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getBilling()
    {
        if (!$this->hasId()) {
            return null;
        }

        if (null === $this->billing) {
            $this->loadBilling();
        }

        return $this->billing;
    }

    /**
     * Method for load billing user information of this order
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadBilling()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->billing = \Redshop\Entity\OrderUser::getInstance();
        $users = $this->getUsers();

        if ($users->isEmpty()) {
            return $this;
        }

        foreach ($users as $user) {
            if ($user->get('address_type') == 'BT') {
                $this->billing = $user;

                return $this;
            }
        }

        return $this;
    }

    /**
     * Method for get users of this order
     *
     * @return   \Redshop\Entities\Collection   Collection of users if success. Null otherwise.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getUsers()
    {
        if (!$this->hasId()) {
            return null;
        }

        if (null === $this->users) {
            $this->loadUsers();
        }

        return $this->users;
    }

    /**
     * Method for load users of this order
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadUsers()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->users = new \Redshop\Entities\Collection;

        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_order_users_info'))
            ->where($db->qn('order_id') . ' = ' . (int)$this->getId());
        $results = $db->setQuery($query)->loadObjectList();

        if (empty($results)) {
            return $this;
        }

        foreach ($results as $result) {
            $entity = \Redshop\Entity\OrderUser::getInstance($result->order_info_id)->bind($result)->loadExtraFields();

            $this->users->add($entity);
        }

        return $this;
    }

    /**
     * Method for get shipping information of this order
     *
     * @return   \Redshop\Entity\OrderUser   User infor if success. Null otherwise.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getShipping()
    {
        if (!$this->hasId()) {
            return null;
        }

        if (null === $this->shipping) {
            $this->loadShipping();
        }

        return $this->shipping;
    }

    /**
     * Method for load shipping user information of this order
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadShipping()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->shipping = \Redshop\Entity\OrderUser::getInstance();
        $users = $this->getUsers();

        if ($users->isEmpty()) {
            return $this;
        }

        foreach ($users as $user) {
            if ($user->get('address_type') == 'ST') {
                $this->shipping = $user;

                return $this;
            }
        }

        return $this;
    }
}
