<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', '.joom-box');

$uri              = JURI::getInstance();
$url              = $uri->root();
$shopperlogo_path = "components/com_redshop/assets/images/shopperlogo";

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PORTAL_SHOP_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PORTAL_SHOP_LBL'),
		'field' => $this->lists['portalshop']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_URL_AFTER_PORTAL_LOGIN'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGIN'),
		'showOn' => 'portal_shop:1',
		'id'     => 'url_after_portal_login',
		'field'  => $this->lists['url_after_portal_login']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_URL_AFTER_PORTAL_LOGOUT'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_URL_AFTER_PORTAL_LOGOUT'),
		'showOn' => 'portal_shop:1',
		'id'     => 'url_after_portal_logout',
		'field'  => $this->lists['url_after_portal_logout']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_DEFAULT_PORTAL_NAME_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_NAME'),
		'showOn' => 'portal_shop:1',
		'id'     => 'default_portal_name',
		'field'  => '<input type="text" name="default_portal_name" id="default_portal_name" class="form-control"
                   value="' . $this->config->get('DEFAULT_PORTAL_NAME') . '"/>'
	)
);

$defaultPortalLogo = $this->config->get('DEFAULT_PORTAL_LOGO');
$html              = '<input type="file" name="default_portal_logo" id="default_portal_logo" size="57"/>'
	. '<input type="hidden" name="default_portal_logo_tmp" value="' . $defaultPortalLogo . '" />';

if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $defaultPortalLogo))
{
	$html .= '<div class="divimages" id="usrdiv">'
		. '<a class="joom-box" href="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . $defaultPortalLogo . '" title="' . $defaultPortalLogo . '"'
		. ' rel="{handler: \'image\', size: {}}">'
		. '<img width="100" height="100" alt="' . $defaultPortalLogo . '" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . $defaultPortalLogo . '"'
		. ' class="thumbnail"/></a>'
		. '<a class="remove_link" href="#" onclick="delimg(\'' . $defaultPortalLogo . '\',\'usrdiv\',\'' . $shopperlogo_path . '\');">'
		. JText::_('COM_REDSHOP_REMOVE_FILE') . '</a></div >';
}

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_DEFAULT_PORTAL_LOGO_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_PORTAL_LOGO_LBL'),
		'showOn' => 'portal_shop:1',
		'id'     => 'default_portal_logo',
		'field'  => $html
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_PRIVATE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_PRIVATE_LBL'),
		'field' => $this->lists['shopper_group_default_private']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_COMPANY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_COMPANY_LBL'),
		'field' => $this->lists['shopper_group_default_company']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL'),
		'field' => $this->lists['shopper_group_default_unregistered']
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL'),
		'field' => $this->lists['new_shopper_group_get_value_from'],
		'line'  => false
	)
);
?>
