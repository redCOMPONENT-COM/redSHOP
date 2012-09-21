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

require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php');
$producthelper = new producthelper();

require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helper.php');
$redhelper = new redhelper();

$div_product = $this->detail->product_type=='product' ? 'block' : 'none';
$div_design = $this->detail->product_type=='design' ? 'block' : 'none';
$div_file = (($this->detail->product_type=='file') || ($this->detail->product_download==1)) ? 'block' : 'none';
$div_subscription = $this->detail->product_type=='subscription' ? 'block' : 'none';

$td_style = ($this->detail->product_download_infinite==0) ? 'style="display:table-row;"' : 'style="display:none;"';

?>
<div id="div_product" style="display:<?php echo $div_product; ?>;">
</div>
<div id="div_design" style="display:<?php echo $div_design; ?>;">
<?php
// reddesign
if($this->CheckRedDesign > 0){
	?>
<table>
      <tr>
        <td><?php echo JText::_('COM_REDSHOP_REDDESIGN_NAME'); ?> </td>
        <td><?php echo $this->lists['designlist']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_REDDESIGN_TIP'), JText::_('COM_REDSHOP_REDDESIGN_NAME'), 'tooltip.png', '', '', false); ?> </td>
      </tr>
      <tr>
        <td><?php echo JText::_('COM_REDSHOP_SHOPPERGROUP_NAME'); ?> </td>
        <td><table><tr><td><?php
					//var_dump($this->lists['product_designtype'][0]->shoppergroups);
					//var_dump($this->lists['shoppergrouplist']);

					$selectedarray = explode(",", $this->lists['product_designtype'][0]->shoppergroups);

					 for($i=0;$i<count($this->lists['shoppergrouplist']);$i++)
					 {

					 if(in_array($this->lists['shoppergrouplist'][$i]->shopper_group_id,$selectedarray ))
					 {
					 	 $checked = 'checked="checked"';
					 }else
					 {
						 $checked = '';
					 }
							//echo  "\n\t<input type=\"radio\" name=\"$name\" id=\"$id_text$k\" value=\"".$k."\"$extra $attribs />";
							echo '<input type="checkbox" '.$checked.'  name="shoppergroup[]" id="shoppergroup'.$this->lists['shoppergrouplist'][$i]->shopper_group_id.'" value="'.$this->lists['shoppergrouplist'][$i]->shopper_group_id.'" /> ' .$this->lists['shoppergrouplist'][$i]->shopper_group_name . "<br/>";
							//$optiondtype[] = JHTML::_('select.option', $designtype[$i]->designtype_id,$designtype[$i]->designtype_name);

					 }
					 ?>
					</td><td>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SHOPPERGROUP_TIP'), JText::_('COM_REDSHOP_SHOPPERGROUP_NAME'), 'tooltip.png', '', '', false); ?>
					</td>
					</tr></table>
        </td>
      </tr>
      <?php // Edited 210110 ?>
      <tr>
        <td><?php echo JText::_('COM_REDSHOP_REDDESIGN_ENABLE_NAME'); ?> </td>
        <td><?php echo $this->lists['reddesign_enable']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_REDDESIGN_ENABLE_TIP'), JText::_('COM_REDSHOP_REDDESIGN_ENABLE_NAME'), 'tooltip.png', '', '', false); ?>  </td>
      </tr>
    </table>
  <?php
}
// reddesign End
  ?>
</div>
<?php // subscription
$subscription = $producthelper->getSubscription($this->detail->product_id);
$renewal_detail = $this->model->getSubscriptionrenewal();
$productSerialDetail = $this->productSerialDetail;
$total_serial = count($productSerialDetail);
?>
<div id="div_file" style="display:<?php echo $div_file; ?>;">
	<table class="admintable">
		<tr>
            <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD' ); ?>: </td>
            <td><?php echo $this->lists['product_download'];?> </td>
        </tr>
	    <tr>
        	<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_INFINITE_LIMIT' ); ?>: </td>
        	<td><?php echo JHTML::_('select.booleanlist','product_download_infinite', 'class="inputbox" onclick="hideDownloadLimit(this);"', $this->detail->product_download_infinite );?></td>
        </tr>
        <tr id="download_limit" <?php echo $td_style;?> >
            <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL' ); ?>: </td>
            <td><input type="text" name="product_download_limit" class="input" size="10" value="<?php echo $this->detail->product_download_limit; ?>" /></td>
        </tr>
        <tr id="download_days" <?php echo $td_style;?> >
            <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL' ); ?>: </td>
            <td><input type="text" name="product_download_days" class="input" size="10" value="<?php echo $this->detail->product_download_days; ?>" /></td>
        </tr>
        <tr id="download_clock" <?php echo $td_style;?> >
            <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_CLOCK_LBL' ); ?>: </td>
            <td>
            	<input type="text" name="product_download_clock" class="input" size="2" maxlength="2" value="<?php echo $this->detail->product_download_clock; ?>" />:<input type="text" name="product_download_clock_min" class="input" size="2" maxlength="2" value="<?php echo $this->detail->product_download_clock_min; ?>" />
            </td>
        </tr>
		<tr>
			<td class="key"><?php echo JText::_('COM_REDSHOP_UPLOAD_CSV_FILE')?>:</td>
			<td><input type="file" name='serialcsvFile' id='serialcsvFile' /></td>
		</tr>
	</table>
	<?php if($total_serial>0) { ?>
	<table class="adminlist">
		<tr>
			<th></th>
			<th><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
			<th><?php echo JText::_('COM_REDSHOP_SERIAL_NUMBERS'); ?></th>
			<th><?php echo JText::_('COM_REDSHOP_IS_USED'); ?></th>
		</tr>
	<?php
	$serial = 1;
	for($si=0;$si<$total_serial;$si++) {
		$serial_row = &$productSerialDetail[$si];  ?>
		<tr>
			<td><a href='index.php?option=com_redshop&view=product_detail&task=deleteProdcutSerialNumbers&serial_id=<?php echo $serial_row->serial_id;?>&product_id=<?php echo $this->detail->product_id;?>'><img class="delete_item" src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>cross.jpg" title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>" alt="<?php echo JText::_('COM_REDSHOP_DELETE');?>" ></a></td>
			<td><?php echo $serial; ?></td>
			<td><?php echo $serial_row->serial_number; ?></td>
			<td><?php echo $serial_row->is_used ? JText::_('COM_REDSHOP_YES') : JText::_('COM_REDSHOP_NO'); ?></td>
		</tr>
	<?php
	$serial++;
	} ?>
	</table>
	<?php } ?>
</div>
<div id="div_subscription" style="display:<?php echo $div_subscription; ?>;">
	<table id="tbl_scbscription" class="adminlist" >
		<tr>
			<td colspan="4"><input type="button" value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>" onClick="add_subscription_row();" /></td>
		</tr>
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_NUM');?></th>
			<th><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD')." ";
			echo JHTML::tooltip(JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_TIP'), JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD'), 'tooltip.png', '', '', false);
			?></th>
			<th><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PRICE');?>
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_SUBSCRIPTION_PRICE_TIP'), JText::_('COM_REDSHOP_SUBSCRIPTION_PRICE'), 'tooltip.png', '', '', false); ?>
			</th>
			<th><?php echo JText::_('COM_REDSHOP_DELETE');?></th>
		</tr>
		<?php
			$option = array();
			$option[]   	= JHTML::_('select.option', 'days', JText::_('COM_REDSHOP_DAYS'));
			$option[]   	= JHTML::_('select.option', 'month', JText::_('COM_REDSHOP_MONTH'));
			$option[]   	= JHTML::_('select.option', 'year', JText::_('COM_REDSHOP_YEAR'));
		 for($sub=0;$sub<count($subscription);$sub++)
		{
			$subrow =& $subscription[$sub];
		?>
		<tr id="tr_subsc<?php echo $sub; ?>">
			<td><?php echo ($sub+1);?><input type="hidden" name="subscription_id[]" value="<?php echo $subrow->subscription_id; ?>" /></td>
			<td><input type="text" name="subscription_period[]" class="input" size="10" value="<?php echo $subrow->subscription_period; ?>" />
			<?php
				echo JHTML::_('select.genericlist',$option,  'period_type[]', 'class="inputbox" size="1" ' , 'value', 'text',  $subrow->period_type ); ?>
			</td>
			<td><input type="text" name="subscription_price[]" class="input" size="10" value="<?php echo $subrow->subscription_price; ?>" /></td>
			<td><input type="button" value="X" title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>" name="btndelete" onClick="delete_subscription_row(<?php echo $sub;?>);" /></td>
		</tr>
		<?php } ?>
	</table>
	<br />
	<table>
	<tr>
	<td class="key"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_RENEWAL_MAIL'); ?>:
	</td>
	<td><input type="text" name="before_no_days" value="<?php echo isset($renewal_detail[0]->before_no_days) ? $renewal_detail[0]->before_no_days : 1; ?>" maxlength="2" size="3" />
		<input type="hidden" name="renewal_id" value="<?php echo isset($renewal_detail[0]->renewal_id) ? $renewal_detail[0]->renewal_id : ''; ?>" />
		<?php echo JText::_('COM_REDSHOP_DAYS_BEFORE_SUBSCRIPTION_END'); ?>
	</td>
	</tr>
	</table>
</div>
<?php 
	$remove_format = JHtml::$formatOptions;
	
	$add_subscription_row = " ".JHTML::_('select.genericlist',$option,  'period_type[]', 'class="inputbox" size="1" ' , 'value', 'text');
	$add_subscription_row = str_replace ($remove_format['format.indent'], "", $add_subscription_row);
	$add_subscription_row = str_replace ($remove_format['format.eol'], "", $add_subscription_row);	
	
?>
<script type="text/javascript">
var rowCount  = <?php echo (count($subscription)+1); ?>;
var rowIndex = <?php echo (count($subscription)+1); ?>;
function add_subscription_row()
{
	var getTABLE = document.getElementById('tbl_scbscription');
	var tBody = getTABLE.getElementsByTagName("TBODY")[0];
	var newTR = document.createElement('tr');
	var newTD0 = document.createElement('td');
	var newTD1 = document.createElement('td');
	var newTD2 = document.createElement('td');
	var newTD3 = document.createElement('td');

	newTD0.innerHTML = rowIndex;
	newTD1.innerHTML = '<input type="text" name="subscription_period[]" class="input" size="10" /><?php echo $add_subscription_row; ?>';
	newTD2.innerHTML = '<input type="text" name="subscription_price[]" class="input" size="10" />';
	newTD3.innerHTML = '<input type="button" value="X" name="btndelete" title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>" onClick="delete_subscription_row('+rowCount+');" />';

	newTR.appendChild (newTD0);
	newTR.appendChild (newTD1);
	newTR.appendChild (newTD2);
	newTR.appendChild (newTD3);

	newTR.id = 'tr_subsc'+rowCount;
	tBody.appendChild (newTR);
	rowCount++;
	rowIndex++;
}
function delete_subscription_row(index)
{
	var row = document.getElementById('tr_subsc'+index);
	var getTABLE = document.getElementById('tbl_scbscription');
	var tBody = getTABLE.getElementsByTagName("TBODY")[0];
	tBody.removeChild(row);
	rowIndex--;
}
</script>
