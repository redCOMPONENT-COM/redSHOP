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
$configobj     = Redconfiguration::getInstance();
$redTemplate   = Redtemplate::getInstance();

$session       = JFactory::getSession();
$itemId        = $input->getInt('Itemid');
$wishlistId   = $input->getInt('wishlist_id');
$mail          = $input->getInt('mail', 0);
$window        = $input->getInt('window');

/** @var RedshopModelAccount $model */
$model = $this->getModel('account');

/** @var RedshopModelProduct $productModel */
$productModel = JModelLegacy::getInstance('Product', 'RedshopModel');
$user  = JFactory::getUser();

$pagetitle     = JText::_('COM_REDSHOP_MY_WISHLIST');
$isIndividualAddToCart = (boolean) Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE');

JPluginHelper::importPlugin('redshop_product');
$dispatcher = RedshopHelperUtility::getDispatcher();

if ($window == 1)
{
	?>
	<script type="text/javascript">window.parent.location.reload();</script>
	<?php
	JFactory::getApplication()->close();
}

if ($this->params->get('show_page_heading', 1))
{
	?>
	<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $pagetitle; ?>
	</h1>
	<div>&nbsp;</div>
<?php
}

if ($mail == 0)
{
	$wishlist = $model->getMyDetail();
	$template   = RedshopHelperTemplate::getTemplate("wishlist_template");

	if (count($template) > 0 && $template[0]->template_desc != "")
	{
		$data = $template[0]->template_desc;
	}
	else
	{
		$data = "<div style=\"float: right;\">{mail_link}</div>{product_loop_start}<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\"><tbody><tr valign=\"top\"><td width=\"40%\"><div style=\"float: left; width: 195px; height: 230px; text-align: center;\">{product_thumb_image}<div>{product_name}</div><div>{product_price}</div><div>{form_addtocart:templet1}</div><div> </div><div>{remove_product_link}</div></div>		</td></tr></tbody></table><div> </div><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">	<tbody><tr> <td> <div></div> </td><td align=\"center\" valign=\"top\"><br><br></td> </tr></tbody></table>{product_loop_end}<div style=\"float: right;\">{back_link}</div>";
	}

	$wishlistTemplateWapper = \RedshopTagsReplacer::_(
		'wishlist',
		$data,
		array(
            'wishlist' => $wishlist,
            'productModel'  => $productModel
		)
    );

	echo $wishlistTemplateWapper;
}
else
{
	$mailTemplate = RedshopHelperTemplate::getTemplate("wishlist_mail_template");

	if (count($mailTemplate) > 0 && $mailTemplate[0]->template_desc != "")
	{
		$wishlistMailData = $mailTemplate[0]->template_desc;
    }
    else
    {
		$wishlistMailData = "<table cellpadding=\"10\" cellspacing=\"10\"><tr><th colspan=\"2\">{email_to_friend}</th></tr><tr><td>{emailto_lbl}</td><td>{emailto}</td></tr><tr><td>{sender_lbl}</td><td>{sender}</td></tr><tr><td>{mail_lbl}</td><td>{mail}</td></tr><tr><td>{subject_lbl}</td><td>{subject}</td></tr><tr><td>	{cancel_button}</td><td>	{send_button}</td></tr></table>";
	}

	$wishlistEmailTemplateWapper = \RedshopTagsReplacer::_(
		'wishlistmail',
		$wishlistMailData,
		array(
			'user'          => $user,
            'itemId'        => $itemId,
            'wishlistId'    => $wishlistId
		)
	);

	echo $wishlistEmailTemplateWapper;
}
