<?php
/**
 * @package     Redshop\Entity\Traits\Product
 * @subpackage  Related
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity\Traits\Product;

use Redshop\Repositories\Product;

/**
 * Trait Related
 * @package Redshop\Entity\Traits\Product
 *
 * @since   2.1.0
 */
trait Related
{
    /**
     * @var   \Redshop\Entities\Collection  Collections of related products
     *
     * @since 2.1.0
     */
    protected $relatedProducts = null;

    /**
     * @param   boolean  $reload  Force reload even it's cached
     *
     * @return  \Redshop\Entities\Collection
     *
     * @since   2.1.0
     */
    public function getRelatedProducts($reload = false)
    {
        if (null === $this->relatedProducts || $reload === true) {
            $this->loadRelated();
        }

        return $this->relatedProducts;
    }

    /**
     * Method to get related products
     *
     * @return  self
     *
     * @since   2.1.0
     */
    protected function loadRelated()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->relatedProducts = new \Redshop\Entities\Collection;
        $productIds            = Product::getRelatedProductIds($this->getId());

        foreach ($productIds as $productId) {
            $this->relatedProducts->add(self::getInstance($productId));
        }

        return $this;
    }

    /**
     * Check if we have an identifier loaded
     *
     * @return  boolean
     * @since   2.1.0
     */
    abstract public function hasId();

    /**
     * Get the id
     *
     * @return  integer | null
     * @since   2.1.0
     */
    abstract public function getId();
}
