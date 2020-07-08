<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerPrices extends RedshopController
{
    public function cancel()
    {
        /** @var RedshopModelPrices $model */
        $model = $this->getModel('prices');
        $productId = $model->getProductId();

        $this->setRedirect('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $productId);
    }
}
