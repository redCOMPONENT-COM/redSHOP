<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$quicklink_icon=explode(",",QUICKLINK_ICON);
$redhelper= new reddesignhelper();
$new_arr= $redhelper->geticonarray();


?>
<table class="admintable">
		<tr><td class ="distitle"><?php echo JText::_( 'PRODUCT_MANAGEMENT');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['products']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['prodtxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['prodimages'][$i], $text );

								?>
									<span><input type="checkbox" name="prodmng<?php echo $i?>" value="<?php echo $new_arr['products'][$i];?>" <?php  if(in_array($new_arr['products'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
                <input type="hidden" name="tot_prod" value="<?php echo count($new_arr['products']);?>" >
			</div>
			</td>
		</tr>
        <tr><td class ="distitle"><?php echo JText::_( 'ORDER');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['orders']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['ordertxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['orderimages'][$i], $text );
								?>
									<span><input type="checkbox" name="ordermng<?php echo $i?>" value="<?php echo $new_arr['orders'][$i];?>" <?php  if(in_array($new_arr['orders'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_ord" value="<?php echo count($new_arr['products']);?>" >
			</div>
			</td>
		</tr>
		<tr><td class ="distitle"><?php echo JText::_( 'DISCOUNT');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['discounts']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['discounttxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['discountimages'][$i], $text );
								?>
									<span><input type="checkbox" name="distmng<?php echo $i?>" value="<?php echo $new_arr['discounts'][$i];?>" <?php  if(in_array($new_arr['discounts'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_dist" value="<?php echo count($new_arr['products']);?>" >
			</div>
			</td>
		</tr>
        <tr><td class ="distitle"><?php echo JText::_( 'COMMUNICATION');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['communications']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['commtxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['commimages'][$i], $text );
								?>
									<span><input type="checkbox" name="commmng<?php echo $i?>" value="<?php echo $new_arr['communications'][$i];?>" <?php  if(in_array($new_arr['communications'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_comm" value="<?php echo count($new_arr['communications']);?>" >
			</div>
			</td>
		</tr>
		 <tr><td class ="distitle"><?php echo JText::_( 'SHIPPING');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['shippings']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['shippingtxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['shippingimages'][$i], $text );
								?>
									<span><input type="checkbox" name="shippingmng<?php echo $i?>" value="<?php echo $new_arr['shippings'][$i];?>" <?php  if(in_array($new_arr['shippings'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_shipping" value="<?php echo count($new_arr['shippings']);?>" >
			</div>
			</td>
		</tr>

		 <tr><td class ="distitle"><?php echo JText::_( 'USER');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['users']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['usertxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['userimages'][$i], $text );
								?>
									<span><input type="checkbox" name="usermng<?php echo $i?>" value="<?php echo $new_arr['users'][$i];?>" <?php  if(in_array($new_arr['users'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_user" value="<?php echo count($new_arr['users']);?>" >
			</div>
			</td>
		</tr>


	    <tr><td class ="distitle"><?php echo JText::_( 'VAT_AND_CURRENCY');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['vats']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['vattxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['vatimages'][$i], $text );
								?>
									<span><input type="checkbox" name="vatmng<?php echo $i?>" value="<?php echo $new_arr['vats'][$i];?>" <?php  if(in_array($new_arr['vats'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_vat" value="<?php echo count($new_arr['vats']);?>" >
			</div>
			</td>
		</tr>

		 <tr><td class ="distitle"><?php echo JText::_( 'IMPORT_EXPORT');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['importexport']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['importtxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['importimages'][$i], $text );
								?>
									<span><input type="checkbox" name="impmng<?php echo $i?>" value="<?php echo $new_arr['importexport'][$i];?>" <?php  if(in_array($new_arr['importexport'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_imp" value="<?php echo count($new_arr['importexport']);?>" >
			</div>
			</td>
		</tr>



		 <tr><td class ="distitle"><?php echo JText::_( 'CUSTOMIZATION');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['altration']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['altrationtxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['altrationimages'][$i], $text );
								?>
									<span><input type="checkbox" name="altmng<?php echo $i?>" value="<?php echo $new_arr['altration'][$i];?>" <?php  if(in_array($new_arr['altration'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_alt" value="<?php echo count($new_arr['altration']);?>" >
			</div>
			</td>
		</tr>


		 <tr><td class ="distitle"><?php echo JText::_( 'CUSTOMER_INPUT');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['customerinput']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['customerinputtxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['customerinputimages'][$i], $text );
								?>
									<span><input type="checkbox" name="custmng<?php echo $i?>" value="<?php echo $new_arr['customerinput'][$i];?>" <?php  if(in_array($new_arr['customerinput'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_cust" value="<?php echo count($new_arr['customerinput']);?>" >
			</div>
			</td>
		</tr>


		 <tr><td class ="distitle"><?php echo JText::_( 'ACCOUNTING');?></td></tr>
		<tr>
			<td>
			<div id="cpanel">
				<?php
				for ($i=0;$i<count($new_arr['accountings']);$i++)
				{
				         ?>
						 <div class="icon">
								<?php
								    $text= JText::_( $new_arr['acctxt'][$i]);
									echo JHTML::_('image', 'administrator/components/com_redshop/assets/images/'.$new_arr['accimages'][$i], $text );
								?>
									<span><input type="checkbox" name="accmng<?php echo $i?>" value="<?php echo $new_arr['accountings'][$i];?>" <?php  if(in_array($new_arr['accountings'][$i], $quicklink_icon)) { echo "checked";} ?>><?php echo $text; ?></span>
						</div>
				<?php }  ?>
				<input type="hidden" name="tot_acc" value="<?php echo count($new_arr['accountings']);?>" >
			</div>
			</td>
		</tr>

</table>