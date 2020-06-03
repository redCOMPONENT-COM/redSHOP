<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Coupon
 *
 * @package      RedSHOP.Backend
 * @subpackage   Model
 * @since        2.1.0
 */
class RedshopModelCoupon extends RedshopModelForm
{
    use Redshop\Model\Traits\HasDateTimeRange;

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success, False on error.
     *
     * @since   2.1.0
     */
    public function save($data)
    {
        $this->handleDateTimeRange($data['start_date'], $data['end_date']);

        if ($data['start_date'] > $data['end_date']) {
            /** @scrutinizer ignore-deprecated */
            $this->setError(JText::_('COM_REDSHOP_START_DATE_MUST_BE_SOONER_OR_EQUAL_TO_END_DATE'));

            return false;
        }

        if (!empty($data['start_date'])) {
            $data['start_date'] = \JFactory::getDate($data['start_date'])->toSql();
        }

        if (!empty($data['end_date'])) {
            $data['end_date'] = \JFactory::getDate($data['end_date'])->toSql();
        }

        return parent::save($data);
    }

    /**
     * Method for check duplicate code on voucher and coupon
     *
     * @param   string  $discountCode  Discount code.
     *
     * @return  integer
     */
    public function checkDuplicate($discountCode)
    {
        $db = $this->getDbo();

        $voucherQuery = $db->getQuery(true)
            ->select($db->qn('code'))
            ->from($db->qn('#__redshop_voucher'));

        $couponQuery = $db->getQuery(true)
            ->select($db->qn('code'))
            ->from($db->qn('#__redshop_coupons'));

        $couponQuery->union($voucherQuery);

        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from('(' . $couponQuery . ') AS ' . $db->qn('data'))
            ->where($db->qn('data.code') . ' = ' . $db->quote($discountCode));

        return $db->setQuery($query)->loadResult();
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @throws  Exception
     * @since   1.6
     *
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app  = JFactory::getApplication();
        $data = $app->getUserState('com_redshop.edit.coupon.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_redshop.coupon', $data);

        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  JObject|boolean  Object on success, false on failure.
     *
     * @since   2.1.0
     */
    public function getItem($pk = null)
    {
        $item = parent::getItem();

        if (false === $item) {
            return false;
        }

        $item->coupon_users = RedshopEntityCoupon::getInstance($item->id)->getUsers()->ids();
        $item->start_date       = $item->start_date != JFactory::getDbo()->getNullDate() ? JFactory::getDate(
            $item->start_date
        )->toUnix() : null;
        $item->end_date         = $item->end_date != JFactory::getDbo()->getNullDate() ? JFactory::getDate(
            $item->end_date
        )->toUnix() : null;

        return $item;
    }
}
