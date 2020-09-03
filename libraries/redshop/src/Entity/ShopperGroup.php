<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity;

defined('_JEXEC') or die;

/**
 * Shopper Group Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class ShopperGroup extends Entity
{
    /**
     * @var    \Redshop\Entities\Collection
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $discounts;

    /**
     * Get the associated table
     *
     * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
     *
     * @return  \JTable|bool
     * @since   __DEPLOY_VERSION__
     */
    public function getTable($name = null)
    {
        return \JTable::getInstance('ShopperGroup', 'RedshopTable');
    }

    /**
     * Method for get discounts of this shopper group
     *
     * @return   \Redshop\Entities\Collection   Redshop\Entities\Collection if success. Null otherwise.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getDiscounts()
    {
        if (!$this->hasId()) {
            return null;
        }

        if (null === $this->discounts) {
            $this->loadDiscounts();
        }

        return $this->discounts;
    }

    /**
     * Method for load discounts for this shopper group
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadDiscounts()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->discounts = new \Redshop\Entities\Collection;

        $db        = \JFactory::getDbo();
        $query     = $db->getQuery(true)
            ->select($db->qn('discount_id'))
            ->from($db->qn('#__redshop_discount_shoppers'))
            ->where($db->qn('shopper_group_id') . ' = ' . $this->getId());
        $discounts = $db->setQuery($query)->loadColumn();

        if (empty($discounts)) {
            return $this;
        }

        foreach ($discounts as $discountId) {
            $this->discounts->add(\Redshop\Entity\Discount::getInstance($discountId));
        }

        return $this;
    }
}
