<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$url   = JURI::base();
$input = JFactory::getApplication()->input;

JHtml::_('behavior.modal');

// Get product helper
$session    = JFactory::getSession();
$itemId     = $input->getInt('Itemid');
$wishlistId = $input->getInt('wishlist_id');
$mail       = $input->getInt('mail', 0);
$window     = $input->getInt('window');

/** @var RedshopModelAccount $model */
$model = $this->getModel('account');

/** @var RedshopModelProduct $productModel */
$productModel = JModelLegacy::getInstance('Product', 'RedshopModel');
$user         = JFactory::getUser();

$isIndividualAddToCart = (boolean)Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE');

JPluginHelper::importPlugin('redshop_product');
$dispatcher = RedshopHelperUtility::getDispatcher();

if ($window == 1) {
	?>
	<script type="text/javascript">window.parent.location.reload();</script>
	<?php
	JFactory::getApplication()->close();
}

if ($this->params->get('show_page_heading', 1)) {
	echo RedshopLayoutHelper::render(
		'tags.common.pageheading',
		[
			'params'      => $this->params,
			'pageheading' => JText::_('COM_REDSHOP_MY_WISHLIST'),
			'class' => 'mywishlist'
		],
		'',
		RedshopLayoutHelper::$layoutOption
	);
}

if ($mail == 0) {
	$wishlist = $model->getMyDetail();
	$template = RedshopHelperTemplate::getTemplate("wishlist_template");

	if (count($template) > 0 && $template[0]->template_desc != "") {
		$data = $template[0]->template_desc;
	} else {
		$data = RedshopHelperTemplate::getDefaultTemplateContent('wishlist_template');
	}

	echo $wishlistTemplateWapper = \RedshopTagsReplacer::_(
		'wishlist',
		$data,
		array(
			'wishlist'     => $wishlist,
			'productModel' => $productModel
		)
	);
} else {
	$mailTemplate = RedshopHelperTemplate::getTemplate("wishlist_mail_template");

	if (count($mailTemplate) > 0 && $mailTemplate[0]->template_desc != "") {
		$wishlistMailData = $mailTemplate[0]->template_desc;
	} else {
		$wishlistMailData = RedshopHelperTemplate::getDefaultTemplateContent('wishlist_mail_template');
	}

	echo $wishlistEmailTemplateWapper = \RedshopTagsReplacer::_(
		'wishlistmail',
		$wishlistMailData,
		array(
			'user'       => $user,
			'itemId'     => $itemId,
			'wishlistId' => $wishlistId
		)
	);
}
