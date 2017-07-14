<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_productcompare
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
$uri = JURI::getInstance();
$url = $uri->root();

$producthelper = productHelper::getInstance();
$redhelper = redhelper::getInstance();

$Itemid = JRequest::getInt('Itemid');
$cid = JRequest::getInt('cid');
if (Redshop::getConfig()->get('COMPARE_PRODUCTS') == 1)
{
	$compare = new RedshopProductCompare();

	?>

	<div id="mod_compareproduct">
		<!-- Comparable Products -->
		<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<?php
			if (!$compare->isEmpty())
			{
				foreach ($compare->getItems() as $item)
				{
					$row = $producthelper->getProductById($item['item']->productId);

					$cid = $item['item']->categoryId;

					if (!$cid)
					{
						$cid = $producthelper->getCategoryProduct($row->product_id);
					}

					$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);

					if (count($ItemData) > 0)
					{
						$Itemid = $ItemData->id;
					}
					else
					{
						$Itemid = RedshopHelperUtility::getItemId($row->product_id);
					}

					$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $cid . '&Itemid=' . $Itemid);


			?>
						<tr valign="top">
							<td width="95%">
								<span><a href='<?php echo $link; ?>'
								         title='<?php echo $row->product_name; ?>'><?php echo $row->product_name; ?></a></span>
							</td>
							<td width="5%">
								<span><a href='javascript:void(0);'
								         onClick="javascript:remove_compare(<?php echo $row->product_id ?>, <?php echo $cid ?>)"><?php echo JText::_('COM_REDSHOP_DELETE'); ?></a></span>
							</td>
						</tr>
			<?php

				}
			}
			else
			{
				echo "<tr><td colspan='2'>" . JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE') . "</td></tr>";
			}

			if (isset($cid))
			{
				$cid_main = "&cid=" . $cid;
			}
			else
			{
				$cid_main = "";
			}
			?>
		</table>
	</div>
	<div id="mod_compare">
		<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=product&layout=compare&Itemid=' . $Itemid . $cid_main) ?>"><?php echo JText::_('COM_REDSHOP_COMPARE'); ?></a>
	</div>
<?php
}
else
{
	echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE') . "</div>";
}
?>
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
