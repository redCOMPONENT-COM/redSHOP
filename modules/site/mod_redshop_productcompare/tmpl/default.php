<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_productcompare
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');
$uri = JURI::getInstance();
$url = $uri->root();

// get product helper
require_once JPATH_ROOT . '/components/com_redshop/helpers/product.php';
$producthelper = new producthelper;

require_once JPATH_ROOT . '/components/com_redshop/helpers/helper.php';
require_once JPATH_ROOT . '/components/com_redshop/helpers/redshop.js.php';
$redhelper = new redhelper;

$option = JRequest::getCmd('option');
$Itemid = JRequest::getInt('Itemid');
$cid = JRequest::getInt('cid');
if (COMARE_PRODUCTS == 1)
{
	$compare = $producthelper->getCompare();

	?>

	<div id="mod_compareproduct">
		<!-- Comparable Products -->
		<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<?php
			$i = 0;
			$cnt = 0;

			if (isset($compare['idx']) && $compare['idx'] > 0)
			{
				for ($i = 0; $i < $compare['idx']; $i++)
				{
					$row = $producthelper->getProductById($compare[$i]["product_id"]);

					$catid = $compare[$i]["category_id"];

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
						$Itemid = $redhelper->getItemid($row->product_id);
					}

					$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $cid . '&Itemid=' . $Itemid);

					if (PRODUCT_COMPARISON_TYPE == "category")
					{
						if ($cid == $catid)
						{
							?>
							<tr valign="top">
								<td width="95%">
									<span><a href='<?php echo $link; ?>'
									         title='<?php echo $row->product_name; ?>'><?php echo $row->product_name; ?></a></span>
								</td>
								<td width="5%">
									<span><a href='javascript:void(0);'
									         onClick="javascript:remove_compare(<?php echo $compare[$i]["product_id"] ?>,<?php echo $compare[$i]["category_id"] ?>)"><?php echo JText::_('COM_REDSHOP_DELETE'); ?></a></span>
								</td>
							</tr>
							<?php
							$cnt++;
						}
					}
					else
					{
						?>
						<tr valign="top">
							<td width="95%">
								<span><a href='<?php echo $link; ?>'
								         title='<?php echo $row->product_name; ?>'><?php echo $row->product_name; ?></a></span>
							</td>
							<td width="5%">
								<span><a href='javascript:void(0);'
								         onClick="javascript:remove_compare(<?php echo $compare[$i]["product_id"] ?>,<?php echo $compare[$i]["category_id"] ?>)"><?php echo JText::_('COM_REDSHOP_DELETE'); ?></a></span>
							</td>
						</tr>
					<?php
					}
				}
			}

			if (PRODUCT_COMPARISON_TYPE == "category")
			{
				if ($cnt == 0)
				{
					echo "<tr><td colspan='2'>" . JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE') . "</td></tr>";
				}
			}
			else
			{
				if (!empty($catid))
				{
					echo "<tr><td colspan='2'>" . JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE') . "</td></tr>";
				}
			}

			if ($cid != "")
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
		var url = site_url + 'index.php?tmpl=component&option=com_redshop&view=product&task=addtocompare&' + args;

		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4)
				window.location.reload(true);
		};
		xmlhttp.open("GET", url, true);
		xmlhttp.send(null);
	}
</script>
