<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * View Zipcodes
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewShopperGroups extends RedshopViewList
{
    /**
     * @var  boolean
     *
     * @since  __DEPLOY_VERSION__
     */
    public $hasOrdering = true;

    /**
     * Method for render column
     *
     * @param array  $config Row config.
     * @param int    $index  Row index.
     * @param object $row    Row data.
     *
     * @return  string
     * @throws  Exception
     *
     * @since   __DEPLOY_VERSION__
     */
    public function onRenderColumn($config, $index, $row)
    {
        switch ($config['dataCol']) {
            case 'add_discount' :
                $addDiscount = JRoute::_(
                    'index.php?option=com_redshop&view=discount&layout=edit&spgrpdis_filter=' . $row->id
                );

                return '<a href="' . $addDiscount . '">' . JText::_('COM_REDSHOP_ADD_DISCOUNT') . '</a>';
            default:
                return parent::onRenderColumn($config, $index, $row);
        }
    }
}