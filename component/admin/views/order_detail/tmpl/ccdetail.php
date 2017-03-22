<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
$ccdetail = $this->ccdetail;
$cardnumber = base64_decode($ccdetail->order_payment_number);

$edate = $ccdetail->order_payment_expire;

$j = 1;
if (!isset($edate[5]))
{
	$j = 0;
	$emonth = "0" . $edate[0];
}
else
	$emonth = $edate[0] . $edate[$j + 0];


$eyear = $edate[$j + 1] . $edate[$j + 2] . $edate[$j + 3] . $edate[$j + 4];
?>
<div align="center">
	<h3><?php echo JText::_("COM_REDSHOP_CREDIT_CARD_INFO");?></h3>
	<table class="adminlist table table-striped">
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_CREDIT_CARD_NAME')?>::</th>
			<td><?php echo base64_decode($ccdetail->order_payment_cardname);?></td>
		</tr>
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_CREDIT_CARD_NUMBER')?>::</th>
			<td><?php echo $cardnumber;?></td>
		</tr>
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_CREDIT_CARD_EXPIRY_DATE')?>::</th>
			<td><?php echo $emonth . "/" . $eyear;?></td>
		</tr>
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_CREDIT_CARD_SECURITY_NUMBER')?>::</th>
			<td><?php echo  base64_decode($ccdetail->order_payment_ccv);?></td>
		</tr>
	</table>
</div>
