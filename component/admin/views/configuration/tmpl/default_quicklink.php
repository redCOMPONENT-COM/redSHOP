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
$redhelper = new reddesignhelper();
$new_arr = $redhelper->geticonarray();


?>
<table class="admintable">
<tr>
	<td class="distitle"><?php echo JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT');?></td>
</tr>
<tr>
	<td>
		<div id="cpanel">
			<?php
			for ($i = 0; $i < count($new_arr['products']); $i++)
			{
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['prodimages'][$i], $text);

					?>
					<span><input type="checkbox" name="prodmng<?php echo $i ?>"
					             value="<?php echo $new_arr['products'][$i]; ?>" <?php  if (in_array($new_arr['products'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_prod" value="<?php echo count($new_arr['products']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['orderimages'][$i], $text);
					?>
					<span><input type="checkbox" name="ordermng<?php echo $i ?>"
					             value="<?php echo $new_arr['orders'][$i]; ?>" <?php  if (in_array($new_arr['orders'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_ord" value="<?php echo count($new_arr['products']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['discountimages'][$i], $text);
					?>
					<span><input type="checkbox" name="distmng<?php echo $i ?>"
					             value="<?php echo $new_arr['discounts'][$i]; ?>" <?php  if (in_array($new_arr['discounts'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_dist" value="<?php echo count($new_arr['products']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['commimages'][$i], $text);
					?>
					<span><input type="checkbox" name="commmng<?php echo $i ?>"
					             value="<?php echo $new_arr['communications'][$i]; ?>" <?php  if (in_array($new_arr['communications'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_comm" value="<?php echo count($new_arr['communications']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['shippingtxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['shippingimages'][$i], $text);
					?>
					<span><input type="checkbox" name="shippingmng<?php echo $i ?>"
					             value="<?php echo $new_arr['shippings'][$i]; ?>" <?php  if (in_array($new_arr['shippings'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_shipping" value="<?php echo count($new_arr['shippings']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['userimages'][$i], $text);
					?>
					<span><input type="checkbox" name="usermng<?php echo $i ?>"
					             value="<?php echo $new_arr['users'][$i]; ?>" <?php  if (in_array($new_arr['users'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_user" value="<?php echo count($new_arr['users']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['vatimages'][$i], $text);
					?>
					<span><input type="checkbox" name="vatmng<?php echo $i ?>"
					             value="<?php echo $new_arr['vats'][$i]; ?>" <?php  if (in_array($new_arr['vats'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_vat" value="<?php echo count($new_arr['vats']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['importimages'][$i], $text);
					?>
					<span><input type="checkbox" name="impmng<?php echo $i ?>"
					             value="<?php echo $new_arr['importexport'][$i]; ?>" <?php  if (in_array($new_arr['importexport'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_imp" value="<?php echo count($new_arr['importexport']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['altrationimages'][$i], $text);
					?>
					<span><input type="checkbox" name="altmng<?php echo $i ?>"
					             value="<?php echo $new_arr['altration'][$i]; ?>" <?php  if (in_array($new_arr['altration'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_alt" value="<?php echo count($new_arr['altration']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['customerinputimages'][$i], $text);
					?>
					<span><input type="checkbox" name="custmng<?php echo $i ?>"
					             value="<?php echo $new_arr['customerinput'][$i]; ?>" <?php  if (in_array($new_arr['customerinput'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_cust" value="<?php echo count($new_arr['customerinput']); ?>">
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
				?>
				<div class="icon">
					<?php
					$text = JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i]);
					echo JHTML::_('image', REDSHOP_ADMIN_IMAGES_ABSPATH . $new_arr['accimages'][$i], $text);
					?>
					<span><input type="checkbox" name="accmng<?php echo $i ?>"
					             value="<?php echo $new_arr['accountings'][$i]; ?>" <?php  if (in_array($new_arr['accountings'][$i], $quicklink_icon))
						{
							echo "checked";
						} ?>><?php echo $text; ?></span>
				</div>
			<?php }  ?>
			<input type="hidden" name="tot_acc" value="<?php echo count($new_arr['accountings']); ?>">
		</div>
	</td>
</tr>

</table>
