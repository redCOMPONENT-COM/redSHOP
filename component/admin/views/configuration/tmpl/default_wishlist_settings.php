<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_MY_WISHLIST_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_MY_WISHLIST'),
		'field' => $this->lists['my_wishlist']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WISHLIST_LOGIN_REQUIRED_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WISHLIST_LOGIN_REQUIRED'),
		'field' => $this->lists['wishlist_login_required']
	)
);


echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WISHLIST_LIST_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WISHLIST_LIST'),
		'field' => $this->lists['wishlist_list']
	)
);


?>

