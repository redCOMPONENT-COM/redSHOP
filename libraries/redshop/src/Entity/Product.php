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
 * Product Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class Product extends Entity
{
    use \Redshop\Entity\Traits\Product\Related,
        \Redshop\Entity\Traits\Product\Categories,
        \Redshop\Entity\Traits\Product\Media,
        \Redshop\Entity\Traits\Product\Stock;

    /**
     * @var   \Redshop\Entities\Collection  Collections of child products
     * @since  __DEPLOY_VERSION__
     */
    protected $childProducts = null;

    /**
     * Get the associated table
     *
     * @param   string  $name  Main name of the Table. Example: Article for ContentTableArticle
     *
     * @return  \JTable
     * @since   __DEPLOY_VERSION__
     */
    public function getTable($name = null)
    {
        return \JTable::getInstance('Product_Detail', 'Table');
    }

    /**
     * Method for get child products
     *
     * @param   boolean  $reload  Force reload even it's cached
     *
     * @return  \Redshop\Entities\Collection
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getChildProducts($reload = false)
    {
        if (null === $this->childProducts || $reload === true) {
            $this->loadChild();
        }

        return $this->childProducts;
    }

    /**
     * Method to load child product
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadChild()
    {
        if (!$this->hasId()) {
            return $this;
        }

        $this->childProducts = new \Redshop\Entities\Collection;

        $db    = \JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select($db->quoteName('product_id'))
            ->from($db->quoteName('#__redshop_product'))
            ->where($db->quoteName('product_parent_id') . ' = ' . (int)$this->getId());

        $productIds = $db->setQuery($query)->loadColumn();

        foreach ($productIds as $productId) {
            $this->childProducts->add(self::getInstance($productId));
        }

        return $this;
    }

    /**
     * Assign a product with a custom field
     *
     * @param   integer  $fieldId  Field id
     * @param   string   $value    Field value
     *
     * @return boolean
     * @since  __DEPLOY_VERSION__
     */
    public function assignCustomField($fieldId, $value)
    {
        // Try to load this custom field data
        /** @var \Redshop\Entity\FieldData $entity */
        $entity = \Redshop\Entity\FieldData::getInstance()->loadItemByArray(
            array
            (
                'fieldid' => $fieldId,
                'itemid'  => $this->id,
                // Product section
                'section' => 1
            )
        );

        // This custom field data is not linked with this product than create it
        if ($entity->hasId()) {
            return true;
        }

        return (boolean)$entity->save(
            array
            (
                'fieldid'  => $fieldId,
                'data_txt' => $value,
                'itemid'   => $this->id,
                'section'  => 1
            )
        );
    }

    /**
     * @param   float    $productPrice  Product price
     * @param   integer  $userId        User id
     * @param   integer  $taxExempt     Tax
     *
     * @return  boolean|float|integer
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getTax($productPrice = 0.0, $userId = 0, $taxExempt = 0)
    {
        if (!$this->hasId()) {
            return false;
        }

        $redshopUser = \JFactory::getSession()->get('rs_user');

        if ($userId == 0) {
            $user   = \JFactory::getUser();
            $userId = $user->id;
        }

        $productTax  = 0;
        $redshopUser = empty($redshopUser) ? array('rs_is_user_login' => 0) : $redshopUser;

        if ($redshopUser['rs_is_user_login'] == 0 && $userId != 0) {
            \RedshopHelperUser::createUserSession($userId);
        }

        $vatRateData = \RedshopHelperTax::getVatRates($this->getId(), $userId);
        $taxRate     = !empty($vatRateData) ? $vatRateData->tax_rate : 0;

        if ($productPrice <= 0) {
            $productPrice = $this->get('product_price', $productPrice);
        }

        $productPrice = \RedshopHelperProductPrice::priceRound((float)$productPrice);

        if ($taxExempt) {
            return $productPrice * $taxRate;
        }

        if (!$taxRate) {
            return \RedshopHelperProductPrice::priceRound($productTax);
        }

        if (!$userId) {
            $productTax = $productPrice * $taxRate;
        } else {
            $userInformation = \RedshopHelperUser::getUserInformation($userId);

            if (null === $userInformation || $userInformation->requesting_tax_exempt !== 1 || !$userInformation->tax_exempt_approved) {
                $productTax = $productPrice * $taxRate;
            }
        }

        return \RedshopHelperProductPrice::priceRound($productTax);
    }
}
