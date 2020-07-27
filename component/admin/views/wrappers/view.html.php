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
        $productName = '';
        $categoryName = '';
        $model  = $this->getModel('wrappers');

	    if ($row->product_id) {
	        $productName = $model->getProductNameById($row->product_id);
        }

        if ($row->category_id) {
            $categoryName = $model->getCategoryNameById($row->category_id);
        }

		switch ($config['dataCol']) {
			case 'image_file' :
				$wimage_path = 'wrapper/' . $row->image;

				return '<a class="joom-box" href="'. REDSHOP_FRONT_IMAGES_ABSPATH . $wimage_path .'" rel="{handler: \'image\', size: {}}">'. $row->image .'</a>';
			case 'use_to_all':
				return JHTML::_('grid.published', $row->use_to_all, $index, 'tick.png', 'publish_x.png', 'FV');
			case 'product_id':
				$prodlink = JRoute::_(
					'index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id
				);

				return '<a href="'. $prodlink .'">'. $productName .'</a>';
			case 'category_id':
				$catelink = JRoute::_(
					'index.php?option=com_redshop&view=category&layout=edit&id=' . $row->category_id
				);

				return '<a href="'. $catelink .'">'. $categoryName .'</a>';
			default:
				return parent::/** @scrutinizer ignore-call */ onRenderColumn($config, $index, $row);
		}
	}
}
