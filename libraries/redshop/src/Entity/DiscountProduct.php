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
 * Discount Product Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class DiscountProduct extends Entity
{
    /**
     * @var \Redshop\Entities\Collection
     * @since  __DEPLOY_VERSION__
     */
    protected $shopperGroups;

    /**
     * @var \Redshop\Entities\Collection
     * @since  __DEPLOY_VERSION__
     */
    protected $categories;

    /**
     * Method for get shopper groups associate with this discount
     *
     * @return \Redshop\Entities\Collection
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getShopperGroups()
    {
        if (null === $this->shopperGroups) {
            $this->loadShopperGroups();
        }

        return $this->shopperGroups;
    }

    /**
     * Method for load shopper groups associate with this discount
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadShopperGroups()
    {
        $this->shopperGroups = new \Redshop\Entities\Collection;

        if (!$this->hasId()) {
            return $this;
        }

        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select($db->qn('shopper_group_id'))
            ->from($db->qn('#__redshop_discount_product_shoppers'))
            ->where($db->qn('discount_product_id') . ' = ' . $this->getId());

        $result = $db->setQuery($query)->loadColumn();

        if (empty($result)) {
            return $this;
        }

        foreach ($result as $shopperGroupId) {
            $this->shopperGroups->add(\Redshop\Entity\ShopperGroup::getInstance($shopperGroupId));
        }

        return $this;
    }

    /**
     * Method for get categories associate with this discount
     *
     * @return  \Redshop\Entities\Collection
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getCategories()
    {
        if (null === $this->categories) {
            $this->loadCategories();
        }

        return $this->categories;
    }

    /**
     * Method for load categories associate with this discount
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadCategories()
    {
        $this->categories = new \Redshop\Entities\Collection;

        if (!$this->hasId() || empty($this->get('category_ids'))) {
            return $this;
        }

        $categoryIds = explode(',', $this->get('category_ids'));

        foreach ($categoryIds as $categoryId) {
            $this->categories->add(\Redshop\Entity\Category::getInstance($categoryId));
        }

        return $this;
    }
}
