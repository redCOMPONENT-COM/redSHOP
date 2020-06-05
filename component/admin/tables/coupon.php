<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Coupon
 *
 * @package      RedSHOP.Backend
 * @subpackage   Table
 * @since        2.1.0
 */
class RedshopTableCoupon extends RedshopTable
{
    /**
     * @var  integer
     */
    public $id;
    /**
     * @var  string
     */
    public $code;
    /**
     * @var  integer
     */
    public $type = 0;
    /**
     * @var  float
     */
    public $value = 0.00;
    /**
     * @var  string
     */
    public $start_date = '0000-00-00 00:00:00';
    /**
     * @var  string
     */
    public $end_date = '0000-00-00 00:00:00';
    /**
     * @var  integer
     */
    public $effect = 0;
    /**
     * @var  integer
     */
    public $amount_left;
    /**
     * @var  integer
     */
    public $published;
    /**
     * @var  integer
     */
    public $subtotal;
    /**
     * @var  integer
     */
    public $order_id;
    /**
     * @var  integer
     */
    public $free_shipping;
    /**
     * @var  integer
     */
    public $created_by;
    /**
     * @var  string
     */
    public $created_date = '0000-00-00 00:00:00';
    /**
     * @var  integer
     */
    public $checked_out;
    /**
     * @var  string
     */
    public $checked_out_time = '0000-00-00 00:00:00';
    /**
     * @var  integer
     */
    public $modified_by;
    /**
     * @var  string
     */
    public $modified_date = '0000-00-00 00:00:00';
    /**
     * The table name without the prefix. Ex: cursos_courses
     *
     * @var  string
     */
    protected $_tableName = 'redshop_coupons';

    /**
     * Checks that the object is valid and able to be stored.
     *
     * This method checks that the parent_id is non-zero and exists in the database.
     * Note that the root node (parent_id = 0) cannot be manipulated with this class.
     *
     * @return  boolean  True if all checks pass.
     */
    protected function doCheck()
    {
        if (!parent::doCheck()) {
            return false;
        }

        if (empty($this->code)) {
            return false;
        }

        if (empty($this->value)) {
            return false;
        }

        $db = $this->getDbo();

        // Check duplicate.
        $code = $this->get('code');

        $voucherQuery = $db->getQuery(true)
            ->select($db->qn('code'))
            ->from($db->qn('#__redshop_voucher'));

        $couponQuery = $db->getQuery(true)
            ->select($db->qn('code'))
            ->from($db->qn('#__redshop_coupons'));

        if ($this->hasPrimaryKey()) {
            $couponQuery->where($db->qn('id') . ' <> ' . $this->id);
        }

        $couponQuery->union($voucherQuery);

        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from('(' . $couponQuery . ') AS ' . $db->qn('data'))
            ->where($db->qn('data.code') . ' = ' . $db->quote($code));

        if ($db->setQuery($query)->loadResult()) {
            $this->setError(JText::_('COM_REDSHOP_COUPON_ERROR_CODE_ALREADY_EXIST'));

            return false;
        }

        return true;
    }

    /**
     * Method to bind an associative array or object to the JTable instance.This
     * method only binds properties that are publicly accessible and optionally
     * takes an array of properties to ignore when binding.
     *
     * @param   mixed  $src  An associative array or object to bind to the JTable instance.
     * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
     *
     * @return  boolean  True on success.
     *
     * @throws  Exception
     */
    protected function doBind(&$src, $ignore = array())
    {
        if (isset($src['coupon_users']) && !empty($src['coupon_users'])) {
            $products = is_string($src['coupon_users']) ? explode(
                ',',
                $src['coupon_users']
            ) : $src['coupon_users'];
            $this->setOption('users', $products);
            unset($src['shopper_group']);
        }

        return parent::doBind($src, $ignore);
    }

    /**
     * Do the database store.
     *
     * @param   boolean  $updateNulls  True to update null values as well.
     *
     * @return  boolean
     */
    protected function doStore($updateNulls = false)
    {
        if (!parent::doStore($updateNulls)) {
            return false;
        }

        if ($this->getOption('skip.updateUsers', false) === true || $this->getOption('inlineMode', false) === true) {
            return true;
        }

        return $this->updateUser();
    }

    /**
     * Method for update product xref.
     *
     * @return  boolean
     */
    protected function updateUser()
    {
        $db = $this->getDbo();

        // Clear current reference products.
        $query = $db->getQuery(true)
            ->delete($db->qn('#__redshop_coupon_user_xref'))
            ->where($db->qn('coupon_id') . ' = ' . $this->id);
        $db->setQuery($query)->execute();

        $users = $this->getOption('users', null);

        if (empty(array_filter($users))) {
            return true;
        }

        $query->clear()
            ->insert($db->qn('#__redshop_coupon_user_xref'))
            ->columns($db->qn(array('coupon_id', 'user_id')));

        foreach ($users as $userId) {
            $query->values((int)$this->id . ',' . (int)$userId);
        }

        return $db->setQuery($query)->execute();
    }
}
