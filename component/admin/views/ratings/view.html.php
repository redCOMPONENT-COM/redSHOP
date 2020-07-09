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
class RedshopViewRatings extends RedshopViewList
{
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
     * @since   2.1.3
     */
    public function onRenderColumn($config, $index, $row)
    {

        switch ($config['dataCol']) {
            case 'time' :
                return \RedshopHelperDatetime::convertDateFormat($row->time);
            case 'user_rating':
                return '<img src="' . REDSHOP_MEDIA_IMAGES_ABSPATH . 'star_rating/' . $row->user_rating . '.gif" border="0">';
            case 'favoured':
                return JHTML::_('grid.published', $row->favoured, $index, 'tick.png', 'publish_x.png', 'FV');
            case 'product_id':
                $prodlink = JRoute::_(
                    'index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id
                );

                return '<a href="'. $prodlink .'">'. $row->product_name .'</a>';
            case 'userid':
                if ($row->userid) {
                    $username = RedshopHelperOrder::getUserFullName($row->userid);
                } else {
                    $username = $row->username;
                }

                return $username;
            default:
                return parent::onRenderColumn($config, $index, $row);
        }
    }
}