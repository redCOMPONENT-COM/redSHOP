<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

$productHelper = productHelper::getInstance();

$url = JURI::base();

$layout             = $this->input->getString('layout', '');
$relatedprdId       = $this->input->getInt('relatedprd_id', 0);
$ajaxdetalTemplate = \Redshop\Template\Helper::getAjaxDetailBox($this->data);

if (null !== $ajaxdetalTemplate)
{
	$dataAdd = RedshopTagsReplacer::_(
			'ajaxcartdetailbox',
			$ajaxdetalTemplate->template_desc,
			array('product' => $this->data)
	);

	echo eval("?>" . $dataAdd . "<?php ");
}
