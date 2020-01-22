<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

$cartTemplate = "";
$ajaxTemplate = $this->redTemplate->getTemplate("ajax_cart_box");

if (count($ajaxTemplate) > 0 && $ajaxTemplate[0]->template_desc)
{
	$cartTemplate = $ajaxTemplate[0]->template_desc;
}
else
{
	$cartTemplate = RedshopHelperTemplate::getDefaultTemplateContent('ajax_cart_box');
}

$cartTemplate = RedshopTagsReplacer::_(
					'ajaxcartbox',
					$cartTemplate,
					array()
				);

echo eval("?>" . $cartTemplate . "<?php ");
JFactory::getApplication()->close();
