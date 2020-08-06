<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Wrappers
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewWrappers extends RedshopViewList
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
	 * @since   __DEPLOY_VERSION__
	 */
	public function onRenderColumn($config, $index, $row)
	{
		switch ($config['dataCol']) {
            case 'name':
                $nameLink = JRoute::_(
                    'index.php?option=com_redshop&task=wrapper.edit&id=' . $row->id
                );

                return '<a href="'. $nameLink .'">'. $row->name .'</a>';
			case 'image' :
				$wimage_path = 'wrapper/' . $row->image;

				return '<a class="joom-box" href="'. REDSHOP_FRONT_IMAGES_ABSPATH . $wimage_path .'" rel="{handler: \'image\', size: {}}">'. $row->image .'</a>';
			case 'use_to_all':
				return JHtml::_('redshopgrid.published', $row->use_to_all, $index, 'useToAll');
			default:
				return parent::/** @scrutinizer ignore-call */ onRenderColumn($config, $index, $row);
		}
	}
}
