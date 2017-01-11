<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$data = array(
	// 'categories'                 => 'COM_REDSHOP_EXPORT_CATEGORIES',
	'products'                   => 'COM_REDSHOP_EXPORT_PRODUCTS',
	// 'attributes'                 => 'COM_REDSHOP_EXPORT_ATTRIBUTES',
	'related_product'            => 'COM_REDSHOP_EXPORT_RELATED_PRODUCTS',
	'fields'                     => 'COM_REDSHOP_EXPORT_FIELDS',
	// 'users'                      => 'COM_REDSHOP_EXPORT_USERS',
	// 'shipping_address'           => 'COM_REDSHOP_EXPORT_SHIPPING_ADDRESS',
	'shopperGroupProductPrice'   => 'COM_REDSHOP_EXPORT_SHOPPER_GROUP_PRODUCT_SPECIFIC_PRICE',
	'shopperGroupAttributePrice' => 'COM_REDSHOP_EXPORT_SHOPPER_GROUP_ATTRIBUTE_SPECIFIC_PRICE',
	// 'manufacturer'               => 'COM_REDSHOP_EXPORT_MANUFACTURER'
);
?>
<h1><?php echo JText::_('COM_REDSHOP_DATA_EXPORT'); ?></h1>
<form
	action="<?php echo 'index.php?option=com_redshop'; ?>"
	method="post"
	name="adminForm"
	id="adminForm"
>
	<!-- Render Export list -->
	<?php foreach ($data as $value => $text): ?>

		<!-- Shopper Group Attribute Price Hint -->
		<?php if ($value == 'shopperGroupAttributePrice')
		{
			$msgList = array('msgList' => array('message' => array(JText::_('COM_REDSHOP_EXPORT_SHOPPER_GROUP_ATTRIBUTE_SPECIFIC_PRICE_HINT'))));
			echo RedshopLayoutHelper::render('system.message', $msgList);
		} ?>
		<p>
			<label class="radio">
			<input
				type="radio"
				onclick="return product_export(this.value)"
				value="<?php echo $value; ?>"
				id="export<?php echo $value; ?>"
				name="export"
			>
				<?php echo JText::_($text); ?>
			</label>
		</p>

		<!-- Display Product Export options -->
		<?php if ($value == 'products') : ?>
			<span id="product_export" style="display: none;">
				<p>
					<b><?php echo JText::_('COM_REDSHOP_EXPORT_PRODUCT_EXTRAFIELD');?></b>
					<?php echo JHtml::_('select.booleanlist', 'export_product_extra_field'); ?>
				</p>
				<p>
					<div><b><?php echo JText::_('COM_REDSHOP_PRODUCT_CATEGORY'); ?></b></div>
					<div>
						<?php echo $this->lists['categories']; ?>
					</div>
				</p>
				<p>
					<div><b><?php echo JText::_('COM_REDSHOP_PRODUCT_MANUFACTURER'); ?></b></div>
					<div>
						<?php echo $this->lists['manufacturers']; ?>
					</div>
				</p>
			</span>
		<?php endif; ?>
	<?php endforeach; ?>

	<!-- Hidden field -->
	<input type="hidden" name="view" value="export"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>

<script type=" text/javascript">
function product_export(val)
{
	if (val == "products")
	{
		document.getElementById('product_export').style.display = "";
	}
	else
	{
		document.getElementById('product_export').style.display = "none";
	}
}
</script>
