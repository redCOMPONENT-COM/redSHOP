<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_productcompare
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<?php $compare = new RedshopProductCompare();?>
<div id="mod_compareproduct">
	<!-- Comparable Products -->
	<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<?php if (!$compare->isEmpty()): ?>
			<?php foreach ($compare->getItems() as $item): ?>
				<?php $row = $productHelper->getProductById($item['item']->productId); ?>
				<?php $cid = $item['item']->categoryId; ?>

				<?php if (!$cid): ?>
					<?php $cid = $productHelper->getCategoryProduct($row->product_id); ?>
				<?php endif ?>

				<?php $itemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id); ?>

				<?php if (count($itemData) > 0): ?>
					<?php $itemId = $itemData->id; ?>
				<?php else: ?>
					<?php $itemId = $redHelper->getItemid($row->product_id); ?>
				<?php endif ?>

				<?php $link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $cid . '&Itemid=' . $itemId); ?>
				<tr valign="top">
					<td width="95%">
						<span><a href='<?php echo $link; ?>' title='<?php echo $row->product_name; ?>'><?php echo $row->product_name; ?></a></span>
					</td>
					<td width="5%">
						<span><a href='javascript:void(0);'
						         onClick="javascript:remove_compare(<?php echo $row->product_id ?>, <?php echo $cid ?>)"><?php echo JText::_('COM_REDSHOP_DELETE'); ?></a></span>
					</td>
				</tr>
			<?php endforeach ?>
		<?php else: ?>
			<tr><td colspan='2'><?php echo JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE') ?></td></tr>
		<?php endif ?>

		<?php if (isset($cid)): ?>
			<?php $cidMain = "&cid=" . $cid; ?>
		<?php else: ?>
			<?php $cidMain = ""; ?>
		<?php endif ?>
	</table>
</div>
<div id="mod_compare">
	<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=product&layout=compare&Itemid=' . $itemId . $cidMain) ?>"><?php echo JText::_('COM_REDSHOP_COMPARE'); ?></a>
</div>
<script language='javascript'>
	function modGetXmlHttpObject() {
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			return new XMLHttpRequest();
		}
		if (window.ActiveXObject) {
			// code for IE6, IE5
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		return null;
	}
	function remove_compare(pid, cid) {
		xmlhttp = modGetXmlHttpObject();
		var args = 'pid=' + pid + '&cmd=remove&cid=' + cid + '&sid=' + Math.random();
		;
		var url = redSHOP.RSConfig._('SITE_URL') + 'index.php?tmpl=component&option=com_redshop&view=product&task=addtocompare&' + args;

		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4)
				window.location.reload(true);
		};
		xmlhttp.open("GET", url, true);
		xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xmlhttp.send(null);
	}
</script>

