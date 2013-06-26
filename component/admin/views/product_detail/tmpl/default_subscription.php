<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');
$db = JFactory::getDBO();
$getSubsctiption = $this->get('Subsctiption');
$newdiv_subscription = $this->detail->product_type == 'newsubscription' ? 'block' : 'none';
$subs_period_unit_opt[] = JHTML::_('select.option', 'year', JText::_('COM_REDSHOP_SUBSCRIPTION_YEAR'));
$subs_period_unit_opt[] = JHTML::_('select.option', 'month', JText::_('COM_REDSHOP_SUBSCRIPTION_MONTH'));
$subs_period_unit_opt[] = JHTML::_('select.option', 'day', JText::_('COM_REDSHOP_SUBSCRIPTION_YEAR_DAY'));
if ($detail->product_download == 1)
	$detail->product_type = 'file';
$subs_period_unit = JHTML::_('select.genericlist', $subs_period_unit_opt, 'subscription[subscription_period_unit]', 'class="inputbox" size="1" ', 'value', 'text', @$getSubsctiption->subscription_period_unit);
$subs_period_lifetime = JHTML::_('select.booleanlist', 'subscription[subscription_period_lifetime]', 'class="inputbox"', (int) @$getSubsctiption->subscription_period_lifetime);
$userhelper = new rsUserhelper();
$shopper_detail = $userhelper->getShopperGroupListSubscriptionPlan();
$shopper_detail_fallback = $userhelper->getShopperGroupListFallback();
$temps = array();
$temps[0]->value = 0;
$temps[0]->text = JText::_('COM_REDSHOP_SELECT');
$shopper_detail = array_merge($temps, $shopper_detail);
$shopper_group = JHTML::_('select.genericlist', $shopper_detail, 'subscription[subs_plan_shopper_group_id]', '', 'value', 'text', (int) @$getSubsctiption->shoppergroup);
$fallback_shopper_group = JHTML::_('select.genericlist', $shopper_detail_fallback, 'subscription[fallback_subs_plan_shopper_group_id]', '', 'value', 'text', (int) @$getSubsctiption->fallback_shoppergroup);
// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$acl_group = JHtml::_('access.usergroups', 'subscription[acl_group]', @explode("|", $getSubsctiption->joomla_acl_groups), true);
$user_acl_group = JHtml::_('access.usergroups', 'subscription[fallback_acl_group]', @explode("|", $getSubsctiption->fallback_joomla_acl_groups), true);
?>

<table class="admintable">
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_UNIT');?></td>
		<td><?php echo $subs_period_unit;?></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD');?></td>
		<td><input type="text" id="subscription_period" name="subscription[subscription_period]"
		           value="<?php echo @$getSubsctiption->subscription_period; ?>"></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_LIFETIME');?></td>
		<td><?php echo $subs_period_lifetime;?></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SELECT_PRODUCTS_APPLICABLE_UNDER_SUBSCRIPTION');?></td>
		<td>
			<?php
			$appProducts = @explode("|", $getSubsctiption->subscription_applicable_products);
			$appProductsImplode = implode(",", $appProducts);
			?>
			<input class="text_area" type="text" name="subscription[applicable_products_input]"
			       id="applicable_products_input" size="32" maxlength="250" value=""/>
			<input class="text_area" type="hidden" name="subscription[subscription_applicable_products]"
			       id="subscription_applicable_products" value="<?php echo $appProductsImplode; ?>"/>

			<div id="applicable_products_output" style="clear: both;">
				<?php
				//$aproducts = $db->setQuery("SELECT CONCAT((SELECT px.product_name FROM #__redshop_product as px WHERE px.product_id = p.product_parent_id ),' - ',p.product_name) AS product_name,p.product_id FROM #__redshop_product as p WHERE p.product_id IN (".$appProductsImplode.")");
				$aproducts = $db->setQuery("SELECT product_name,p.product_id FROM #__redshop_product as p WHERE p.product_id IN (" . $appProductsImplode . ")");
				$aproducts = $db->loadObjectList();
				for ($gj = 0, $ngj = count($aproducts); $gj < $ngj; $gj++)
				{
					$link_remove = JRoute::_('index.php?option=com_redshop&view=product_detail&task=removeProduct&cid=' . $this->detail->product_id . '&pids=' . $aproducts[$gj]->product_id);
					if ($aproducts[$gj]->product_name <> "")
					{
						echo "<div><a href='$link_remove'>Remove</a>&nbsp;&nbsp;&nbsp;" . $aproducts[$gj]->product_name . "</div>";
					}
				}
?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SELECT_CATEGORIES_UNDER_SUBSCRIPTION');?></td>
		<td>
			<?php
			$appcategory = @explode("|", $getSubsctiption->subscription_applicable_categories);
			$appcategoryImplode = implode(",", $appcategory);
			?>
			<input class="text_area" type="text" name="subscription[applicable_category_input]"
			       id="applicable_category_input" size="32" maxlength="250" value=""/>
			<input class="text_area" type="hidden" name="subscription[subscription_applicable_category]"
			       id="subscription_applicable_category" value="<?php echo $appcategoryImplode; ?>"/>

			<div id="applicable_category_output" style="clear: both;">
				<?php
				/*
					//Will edit when need - Implement Vietnam Team Code
					$acategory = $db->setQuery("SELECT category_name FROM #__redshop_category WHERE category_id IN (".$appcategoryImplode.")");
					$acategory = $db->loadObjectList();
					for ($gj=0,$ngj=count($acategory);$gj<$ngj;$gj++)
					{
						echo "<div>".$acategory[$gj]->category_name."</div>";
					}
				*/
				?>
			</div>
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
				<legend><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_ASSIGNED_USER_GROUP');?></legend>
				<?php echo $user_acl_group;?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PLAN_SHOPPERGROUP');?></td>
		<td><?php echo $shopper_group;?></td>
	</tr>
	<tr>
		<td class="key"><?php echo JText::_('COM_REDSHOP_FALLBACK_PLAN_SHOPPERGROUP');?></td>
		<td><?php echo $fallback_shopper_group;?></td>
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
				var node = document.createElement("DIV");
				var textnode = document.createTextNode(obj.value);
				node.appendChild(textnode);
				document.getElementById("applicable_products_output").appendChild(node);
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
				var node = document.createElement("DIV");
				var textnode = document.createTextNode(obj.value);
				node.appendChild(textnode);
				document.getElementById("applicable_category_output").appendChild(node);
			}
			applicableCategory.push(obj.id);
			document.getElementById('subscription_applicable_category').value = applicableCategory;
			document.getElementById("applicable_category_input").value = "";
		}
	};
	var appCategory = new bsn.AutoSuggest('applicable_category_input', applicable_category_option);
	// End Of Category Search
</script>


 