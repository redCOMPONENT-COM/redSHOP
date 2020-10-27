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
 * Tax group
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class TaxGroup extends Entity
{
    /**
     * List of tax rates belong to this tax group
     *
     * @var    \Redshop\Entities\Collection
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $taxRates;

    /**
     * Method for get all associated tax rates
     *
     * @return  \Redshop\Entities\Collection
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getTaxRates()
    {
        if (is_null($this->taxRates)) {
            $this->loadTaxRates();
        }

        return $this->taxRates;
    }

    /**
     * Method for load all tax rates
     *
     * @return  self
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function loadTaxRates()
    {
        /** @var \Redshop\Entities\Collection taxRates */
        $this->taxRates = new \Redshop\Entities\Collection;

        if (!$this->hasId()) {
            return $this;
        }

        $model = \RedshopModel::getInstance('Tax_Rates', 'RedshopModel', array('ignore_request' => true));
        $model->setState('filter.tax_group', $this->getId());

        $taxRates = $model->getItems();

        if (empty($taxRates)) {
            return $this;
        }

        foreach ($taxRates as $taxRate) {
            $this->taxRates->add(\Redshop\Entity\TaxRate::getInstance($taxRate->id)->bind($taxRate));
        }

        return $this;
    }
}
