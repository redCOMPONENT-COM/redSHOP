<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
$db     = JFactory::getDBO();
$model  = $this->getModel('product_detail');
$getSubsctiption        = $model->getNewSubscription();
$subscription_parent    = $model->listAllSubscription("subscription[subscription_parent_id]", $getSubsctiption ->subscription_id);
$newdiv_subscription    = $this->detail->product_type == 'newsubscription' ? 'block' : 'none';
$subs_period_unit_opt[] = JHTML::_('select.option', 'year', JText::_('COM_REDSHOP_SUBSCRIPTION_YEAR'));
$subs_period_unit_opt[] = JHTML::_('select.option', 'month', JText::_('COM_REDSHOP_SUBSCRIPTION_MONTH'));
$subs_period_unit_opt[] = JHTML::_('select.option', 'day', JText::_('COM_REDSHOP_SUBSCRIPTION_YEAR_DAY'));
$subs_period_unit       = JHTML::_('select.genericlist', $subs_period_unit_opt, 'subscription[subscription_period_unit]', 'class="inputbox" size="1" ', 'value', 'text', @$getSubsctiption->subscription_period_unit);

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$acl_group = JHtml::_('access.usergroups', 'subscription[acl_group]', @explode("|", $getSubsctiption->joomla_acl_groups), true);
$user_acl_group = JHtml::_('access.usergroups', 'subscription[fallback_acl_group]', @explode("|", $getSubsctiption->fallback_joomla_acl_groups), true);
?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		// if (document.getElementById('subscription_period').value == "") 
		// {
		// 	alert("<?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_MUST_HAVE_A_PERIOD', true ); ?>");
		// 	return;
		// }else if (document.getElementById('subscription_period').value <= 0 || isNaN(document.getElementById('subscription_period').value)) {
		// 	alert("<?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_MUST_HAVE_A_PERIOD_ERROR', true ); ?>");
		// 	return;
		// }else if (document.getElementById('subscription_level').value == "") {
		// 	alert("<?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_MUST_HAVE_A_LEVEL', true ); ?>");
		// 	return;	
		// }else if (document.getElementById('subscription_level').value <= 0 || isNaN(document.getElementById('subscription_level').value)) {
		// 	alert("<?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_MUST_HAVE_A_LEVEL_ERROR', true ); ?>");
		// 	return;	
		// } 
		submitform(pressbutton);
	}
</script>

<table class="admintable">
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_UNIT');?></td>
		<td><?php echo $subs_period_unit;?><span><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_UNIT_TIP'), JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_UNIT'), 'tooltip.png', '', '', false); ?></span></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD');?></td>
		<td><input type="text" id="subscription_period" name="subscription[subscription_period]"
		           value="<?php echo @$getSubsctiption->subscription_period; ?>"><span><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_TIP'), JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD'), 'tooltip.png', '', '', false); ?></span></td>
	</tr>
	<tr>
		<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PARENT_SUBSCRIPTION'); ?>:</td>
		<td><?php echo $subscription_parent; ?>
		<span><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SUBSCRIPTION_PARENT_SUBSCRIPTION_TOOLTIP' ), JText::_('COM_REDSHOP_SUBSCRIPTION_PARENT_SUBSCRIPTION' ), 'tooltip.png', '', '', false); ?></span>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SELECT_PRODUCTS_APPLICABLE_UNDER_SUBSCRIPTION');?></td>
		<td>
			<?php
			$appProducts        = @explode("|", $getSubsctiption->subscription_applicable_products);
			$appProducts        = $model->removeNullInArray($appProducts);
			$appProductsImplode = implode(",", $appProducts);
			?>
			<input class="text_area" type="text" name="subscription[applicable_products_input]"
			       id="applicable_products_input" size="32" maxlength="250" value=""/><span><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SELECT_PRODUCTS_APPLICABLE_UNDER_SUBSCRIPTION_TIP'), JText::_('COM_REDSHOP_SELECT_PRODUCTS_APPLICABLE_UNDER_SUBSCRIPTION'), 'tooltip.png', '', '', false); ?></span>
			<input class="text_area" type="hidden" name="subscription[subscription_applicable_products]"
			       id="subscription_applicable_products" value="<?php echo $appProductsImplode; ?>"/>

			<div id="applicable_products_output" style="clear: both;">
				<?php
				$aproducts   = $model->getProductInSubscription($appProductsImplode);

				for ($gj = 0, $ngj = count($aproducts); $gj < $ngj; $gj++)
				{
					$link_remove = JRoute::_('index.php?option=com_redshop&view=product_detail&task=removeProduct&cid=' . $this->detail->product_id . '&pids=' . $aproducts[$gj]->product_id);

					if ($aproducts[$gj]->product_name <> "")
					{
						echo "<div><a href='$link_remove'>" . JText::_('COM_REDSHOP_SUBSCRIPTION_REMOVE') . "</a>&nbsp;&nbsp;&nbsp;" . $aproducts[$gj]->product_name . "</div>";
					}
				}
?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SELECT_CATEGORIES_UNDER_SUBSCRIPTION');?></td>
		<td>
			<input class="text_area" type="text" name="subscription[applicable_category_input]"
			       id="applicable_category_input" size="32" maxlength="250" value=""/><span><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SELECT_CATEGORIES_UNDER_SUBSCRIPTION_TIP'), JText::_('COM_REDSHOP_SELECT_CATEGORIES_UNDER_SUBSCRIPTION'), 'tooltip.png', '', '', false); ?></span>
			<input class="text_area" type="hidden" name="subscription[subscription_applicable_category]"
			       id="subscription_applicable_category" value=""/>
			<div id="applicable_category_output" style="clear: both;"></div>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_JOOMLA_ACL_GROUP');?></td>
		<td>
			<fieldset class="adminform" id="user-groups">
				<legend><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_ASSIGNED_USER_GROUP');?></legend>
				<?php echo $acl_group;?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_JOOMLA_FALLBACK_ACL_GROUP');?></td>
		<td>
			<fieldset class="adminform" id="user-groups">
				<legend><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_ASSIGNED_USER_GROUP'); ?></legend>
				<?php echo $user_acl_group;?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="hidden" name="subscription[subscription_id]"
		                       value="<?php echo @$getSubsctiption->subscription_id; ?>"></td>
	</tr>
</table>

<script>
	//Product Search
	var subscription_applicable_products_value = document.getElementById('subscription_applicable_products').value;
	var applicableProduct = subscription_applicable_products_value.split(",").sort();
	var applicable_product_options = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&media_section=product&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			var rdaprodct = false;
			for (var ap = 0, nap = applicableProduct.length; ap < nap; ap++) {
				if (applicableProduct[ap] == obj.id) {
					rdaprodct = true;
					alert("Already Exist");
				}
			}
			if (!rdaprodct) {
				applicableProduct.push(obj.id);
				var node_div = document.createElement("DIV");
				var node = document.createElement("SPAN");
				node.setAttribute("id", "product" + obj.id);
				node.innerHTML = "&nbsp;&nbsp;&nbsp;" + obj.value;
				var node_remove = document.createElement("A");
				node_remove.href="javascript:void(0)";
				node_remove.name = obj.id;
				node_remove.onclick=function(){
					var el = document.getElementById("product" + this.name);

					var i = applicableProduct.indexOf(this.name);
					if (i > -1)
					{
						applicableProduct.splice(i, 1);
						document.getElementById('subscription_applicable_products').value = applicableProduct;						
					}

					el.parentNode.removeChild(el);
					this.parentNode.removeChild(this);
					
				};
				node_remove.innerHTML = "Remove";
				node_div.appendChild(node_remove);
				node_div.appendChild(node);
				document.getElementById("applicable_products_output").appendChild(node_div);
			}
			document.getElementById('subscription_applicable_products').value = applicableProduct;
			document.getElementById("applicable_products_input").value = "";
		}
	};
	var appProduct = new bsn.AutoSuggest('applicable_products_input', applicable_product_options);
	// End Of Product Search

	//Category Search
	var subscription_applicable_category_value = document.getElementById('subscription_applicable_category').value;
	var applicableCategory = subscription_applicable_category_value.split(",");
	var applicable_category_option = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&media_section=category&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			var rdaprodct = false;
			for (var ap = 0, nap = applicableCategory.length; ap < nap; ap++) {
				if (applicableCategory[ap] == obj.id) {
					rdaprodct = true;
					alert("Already Exist");
				}
			}
			if (!rdaprodct) {
				applicableCategory.push(obj.id);
				var node_div = document.createElement("DIV");
				var node = document.createElement("SPAN");
				node.setAttribute("id", "category" + obj.id);
				node.innerHTML = "&nbsp;&nbsp;&nbsp;" + obj.value;
				var node_remove = document.createElement("A");
				node_remove.href="javascript:void(0)";
				node_remove.name = obj.id;
				node_remove.onclick=function(){
					var el = document.getElementById("category" + this.name);

					var i = applicableCategory.indexOf(this.name);
					if (i > -1)
					{
						applicableCategory.splice(i, 1);
						document.getElementById('subscription_applicable_category').value = applicableCategory;						
					}

					el.parentNode.removeChild(el);
					this.parentNode.removeChild(this);
					
				};
				node_remove.innerHTML = "Remove";
				node_div.appendChild(node_remove);
				node_div.appendChild(node);
				document.getElementById("applicable_category_output").appendChild(node_div);
			}
			//applicableCategory.push(obj.id);
			document.getElementById('subscription_applicable_category').value = applicableCategory;
			document.getElementById("applicable_category_input").value = "";
		}
	};
	var appCategory = new bsn.AutoSuggest('applicable_category_input', applicable_category_option);
	// End Of Category Search
</script>


 