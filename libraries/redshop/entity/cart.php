<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Product Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       __DEPLOY_VERSION__
 */
class RedshopEntityCart extends RedshopEntity
{
    public function getCart()
    {

    }

    public function setCart($cart)
    {

    }

    public function getTable($name = null)
    {
        if (null === $name)
        {
            $class = get_class($this);
            $name  = strstr($class, 'Entity');
        }

        $name = str_replace('Entity', '', $name);

        return \RedshopTable::getAdminInstance($name, array(), $this->getComponent());
    }
}