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

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
jimport('joomla.html.pane');
?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		if (form.payment_method_name.value == ""){
			alert( "<?php echo JText::_( 'PAYMENT_METHOD_MUST_HAVE_A_NAME', true ); ?>" );
		} else {

			submitform( pressbutton );
		}
	}
	function hide_show_cclist(val)
	{
		if(val==1)
		{
			document.getElementById('cc_tr').style.display = 'block';
			document.getElementById('cc_tr_lbl').style.display = 'block';
		}
		else
		{
			document.getElementById('cc_tr').style.display = 'none';
			document.getElementById('cc_tr_lbl').style.display = 'none';
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<?php
//Get JPaneTabs instance
$myTabs = & JPane::getInstance('tabs', array('startOffset'=>0));
$output = '';

//Create Pane
$output .= $myTabs->startPane( 'pane' );
//Create 1st Tab
echo $output .= $myTabs->startPanel(JText::_('DETAILS'), 'tab1' );
?>
 <input  type="hidden" name="plugin" id="plugin" size="32" maxlength="250" value="<?php echo $this->detail->plugin;?>"  />

		<table class="admintable" width="100%">

		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'PAYMENT_NAME' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="payment_method_name" id="payment_method_name" size="32" maxlength="250" value="<?php echo $this->detail->payment_method_name;?>"  />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'PAYMENT_CLASS' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="payment_class" id="payment_class" size="32" maxlength="250" value="<?php echo $this->detail->payment_class;?>" readonly="readonly"  />
			</td>
		</tr>
			<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'PAYMENT_PRICE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="payment_oprand" id="payment_oprand" size="3" maxlength="250" value="<?php echo $this->detail->payment_oprand;?>"  />
				<input class="text_area" type="text" name="payment_price" id="payment_price" size="26" maxlength="250" value="<?php echo $this->detail->payment_price;?>"  />
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'DISCOUNT_TYPE' ); ?>:
			</td>
			<td>

        <input type="radio" class="inputbox" id="payment_discount_is_percent0" name="payment_discount_is_percent" value="1" <?php if($this->detail->payment_discount_is_percent ==1) echo "checked=\"checked\""; ?> />
        <label for="payment_discount_is_percent0"><?php echo  JText::_( 'PAYMENT_DISCOUNT_ISPERCENT' ) ;  ?></label>&nbsp;&nbsp;&nbsp;
  		<br>
        <input type="radio" class="inputbox" id="payment_discount_is_percent1" name="payment_discount_is_percent" value="0" <?php if($this->detail->payment_discount_is_percent == 0) echo "checked=\"checked\""; ?> />
        <label for="payment_discount_is_percent1"><?php echo JText::_( 'PAYMENT_DISCOUNT_ISTOTAL' ); ?></label>
      </td>
	</tr>

	<tr>
		<td valign="top" align="right" class="key">
			<?php echo JText::_( 'SHOPPER_GROUP_LBL' ); ?>:
		</td>
		<td>
			<?php echo $this->lists['shopper_group']; ?>
		</td>
	</tr>

	<?php
		if ($this->detail->plugin != 'rs_payment_banktransfer' && $this->detail->plugin != 'rs_payment_eantransfer' && $this->detail->plugin != 'rs_payment_cashtransfer' && $this->detail->plugin != 'rs_payment_banktransfer_discount' || $this->detail->plugin != 'rs_payment_banktransfer2' || $this->detail->plugin != 'rs_payment_banktransfer3' || $this->detail->plugin != 'rs_payment_banktransfer4' || $this->detail->plugin != 'rs_payment_banktransfer5'){
	?>
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'IS_CREDIT_CART' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['is_creditcard']; ?>
			</td>
		</tr>
	<?php }?>

		<tr>
			<td valign="top" align="right" class="key">
				<div id='cc_tr_lbl' <?php echo ($this->detail->is_creditcard==0) ? "style='display:none'" : "" ?>>
				<?php echo JText::_( 'ACCEPTED_CREDICT_CARD' ); ?>:
				</div>
			</td>
			<td>
				<div id='cc_tr' <?php echo ($this->detail->is_creditcard==0) ? "style='display:none'" : "" ?>>
				<?php
					$selected_cc = explode(",",$this->detail->accepted_credict_card);
					foreach($this->cc_list as $key => $value)
					{
						$checked = in_array($key,$selected_cc) ? "checked" : "";
						echo "<input type='checkbox' id='".$key."' name='accepted_credict_card[]' value='".$key."' ".$checked." />".$value."&nbsp;";
					}
				?>
				</div>
			</td>
		</tr>

		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'PUBLISHED' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
			<tr>
			<td valign="top"  colspan="2"  >
				  <?php echo JText::_( 'PAYMENT_EXTRA_INFO' ); ?>
					<br>

				<textarea class="text_area" cols="100" rows="20" name="payment_extrainfo" id="payment_extrainfo"><?php echo $this->detail->payment_extrainfo; ?></textarea>

			</td>
		</tr>
	</table>

 <?php
echo $myTabs->endPanel();
//Create 2nd Tab
echo  $myTabs->startPanel(JText::_('CONFIG'), 'tab2' );
echo $this->params;
 if(method_exists($this->ps,'show_configuration')){

 $this->ps->show_configuration();

 }
echo $myTabs->endPanel();
echo $myTabs->endPane();
?>

<div class="clr"></div>

<input type="hidden" name="payment_method_id" value="<?php echo $this->detail->payment_method_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="payment_detail" />
</form>

