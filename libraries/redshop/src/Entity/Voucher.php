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
 * Voucher Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class Voucher extends Entity
{
    /**
     * @var  \Redshop\Entities\Collection
     * @since  __DEPLOY_VERSION__
     */
    protected $products;

    /**
     * Method for get products available with this voucher
     *
     * @return  \Redshop\Entities\Collection
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getProducts()
    {
        if (null === $this->products) {
            $this->loadProducts();
        }

        return $this->products;
    }

    /**
     * Method for load products available with this voucher
     *
     * @return  self
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function loadProducts()
    {
        $this->products = new \Redshop\Entities\Collection;

        if (!$this->hasId()) {
            return $this;
        }

        $db     = \JFactory::getDbo();
        $query  = $db->getQuery(true)
            ->select($db->qn('product_id'))
            ->from($db->qn('#__redshop_product_voucher_xref'))
            ->where($db->qn('voucher_id') . ' = ' . $this->getId());
        $result = $db->setQuery($query)->loadColumn();

        if (empty($result)) {
            return $this;
        }

        foreach ($result as $productId) {
            $this->products->add(\Redshop\Entity\Product::getInstance($productId));
        }

        return $this;
    }
}
