<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<fieldset class="adminform">

	<legend>
		<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT'); ?>
	</legend>

	<table class="admintable">

		<tr>
			<td>
				<?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT'); ?>
			</td>
			<td>

				<?php echo $this->lists['related_product']; ?>
			</td>
		</tr>

		<tr>
			<td>
				<label for="fetch_child_for_related_product">
					<?php echo JText::_('COM_REDSHOP_CHILD_PRODUCT_AS_RELATED_PRODUCT_TEXT');?>
				</label>
			</td>
			<td>
				<input type="checkbox"
					   value="1"
					   id="fetch_child_for_related_product"
					   name="fetch_child_for_related_product"
					   onclick="updateRelatedProduct(this);"
					/>
			</td>
		</tr>

	</table>

</fieldset>

<script>
	var preproductids = [];

	function updateRelatedProduct(me) {

		if (preproductids.length > 0) {
			updateRelatedBox(preproductids, me.checked);
			return;
		}

		xmlhttp = getHTTPObject();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4) {
				response = xmlhttp.responseText;
				var products = response.split(":");

				updateRelatedBox(products, true);

				preproductids = products;
			}
		};

		var url = "index.php?option=com_redshop&view=product_detail&cid[]=" +
				  document.adminForm.product_id.value +
				  "&task=getChildProducts&tmpl=component&json=1";
		xmlhttp.open("GET", url, true);
		xmlhttp.send(null);
	}

	function updateRelatedBox(products, ischecked) {
		var productids = products[0].split(",");
		var productnames = products[1].split(",");

		var selTo = document.adminForm.related_product;

		if (ischecked) {
			for (var g = 0; g < productids.length; g++) {
				var chk_add = 1;
				for (var i = 0; i < selTo.options.length; i++) {
					if (selTo.options[i].value == productids[g]) chk_add = 0;
				}
				if (chk_add == 1) {
					newOption = new Option(productnames[g], productids[g]);
					selTo.options[selTo.options.length] = newOption;
				}
			}
		} else {
			for (g = 0; g < productids.length; g++) {
				for (i = 0; i < selTo.options.length; i++) {
					if (selTo.options[i].value == productids[g]) selTo.remove(i);
				}
			}
		}
	}
</script>
