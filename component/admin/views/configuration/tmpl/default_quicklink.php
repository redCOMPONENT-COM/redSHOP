<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
$quicklink_icon = explode(",", QUICKLINK_ICON);
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/images.php';
$new_arr = RedShopHelperImages::geticonarray();

?>
<style type="text/css">
	#cpanel div.icon a:hover, #cpanel div.icon a:focus, #cpanel div.icon a:active, .cpanel div.icon a:hover, .cpanel div.icon a:focus, .cpanel div.icon a:active {
		background-position: 0 center;
		border-bottom-right-radius: 50% 15px;
		border-bottom-left-radius: 0% 15px;
		box-shadow: 5px 10px 15px rgba(69, 85, 96, 0.25);
		position: relative;
		z-index: 10;
	}

	#cpanel img, .cpanel img {
		float: none;
	}
</style>
<table class="adminlist">
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['products']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['products'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['prodimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='prodmng$i' value=\"" . $new_arr['products'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_prod\" value=\"" . count($new_arr['products']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_ORDER');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['orders']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['orders'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['orderimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='ordermng$i' value=\"" . $new_arr['orders'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_ord\" value=\"" . count($new_arr['orders']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>

		</div>
	</td>
</tr>
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_DISCOUNT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['discounts']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['discounts'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['discountimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='distmng$i' value=\"" . $new_arr['discounts'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_ord\" value=\"" . count($new_arr['discounts']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_COMMUNICATION');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['communications']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['communications'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['commimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='commmng$i' value=\"" . $new_arr['communications'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_comm\" value=\"" . count($new_arr['communications']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_SHIPPING');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['shippings']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['shippingtxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['shippings'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['shippingimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='shippingmng$i' value=\"" . $new_arr['shippings'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_shipping\" value=\"" . count($new_arr['shippings']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>

<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_USER');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['users']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['users'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['userimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='usermng$i' value=\"" . $new_arr['users'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_user\" value=\"" . count($new_arr['users']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>


<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_VAT_AND_CURRENCY');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['vats']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['vats'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['vatimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='vatmng$i' value=\"" . $new_arr['vats'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_vat\" value=\"" . count($new_arr['vats']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>

<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_IMPORT_EXPORT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['importexport']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['importexport'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['importimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='impmng$i' value=\"" . $new_arr['importexport'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_imp\" value=\"" . count($new_arr['importexport']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>


<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_CUSTOMIZATION');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['altration']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['altration'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['altrationimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='altmng$i' value=\"" . $new_arr['altration'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_alt\" value=\"" . count($new_arr['altration']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>


<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_CUSTOMER_INPUT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['customerinput']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['customerinput'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['customerinputimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='custmng$i' value=\"" . $new_arr['customerinput'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_cust\" value=\"" . count($new_arr['customerinput']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>


<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_ACCOUNTING');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['accountings']); $i++)
			{
				$text = JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i]);

				echo "<div style='float: left;'><div class='icon'><a href='javascript:;'>";

				$checked = '';

				if (in_array($new_arr['accountings'][$i], $quicklink_icon))
				{
					$checked = "checked";
				}

				echo    JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['accimages'][$i], $text);
				echo    "<span>
							<input style='float:none;' type='checkbox' name='accmng$i' value=\"" . $new_arr['accountings'][$i] . "\" $checked >$text
							<input type=\"hidden\" name=\"tot_acc\" value=\"" . count($new_arr['accountings']) . "\">
						</span>";
				echo    "</a></div></div>";
			}
			?>
		</div>
	</td>
</tr>
</table>
