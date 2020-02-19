<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$uri         = JURI::getInstance();
$getShm   = $uri->getScheme();
$config   = JFactory::getConfig();
$forceSsl = $config->get('force_ssl');

if ($getShm == 'https' && $forceSsl > 2)
{
	$uri->setScheme('http');
}

if ($this->params->get('show_page_heading', 1)) : ?>
    <h1 class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php echo $this->escape(JText::_('COM_REDSHOP_ORDER_DETAILS')); ?>
    </h1>
<?php endif; ?>
<?php
$ordersDetail   = $this->OrdersDetail;

// Get order Payment method information

if (Redshop::getConfig()->get('USE_AS_CATALOG'))
{
	$ordersListTemplate = RedshopHelperTemplate::getTemplate("catalogue_order_detail");
	$ordersListTemplate = $ordersListTemplate[0]->template_desc;
}
else
{
	$ordersListTemplate = RedshopHelperTemplate::getTemplate("order_detail");

	if (count($ordersListTemplate) > 0 && $ordersListTemplate[0]->template_desc)
	{
		$ordersListTemplate = $ordersListTemplate[0]->template_desc;
	}
	else
	{
		$ordersListTemplate = RedshopHelperTemplate::getDefaultTemplateContent('order_detail');
	}
}

// Replace Reorder Button
$this->replaceReorderButton($ordersListTemplate);

$message = RedshopTagsReplacer::_(
	'orderdetail',
	$ordersListTemplate,
	array(
		'ordersDetail' => $ordersDetail
	)
);

$message = RedshopHelperTemplate::parseRedshopPlugin($message);
$message = Redshop\Order\Template::replaceTemplate($ordersDetail, $message);
echo eval("?>" . $message . "<?php ");
