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
$ccdetail = $this->ccdetail;
$cardnumber = base64_decode($ccdetail->order_payment_number);
		
$edate = $ccdetail->order_payment_expire;

 $j = 1;
 if(!isset($edate[5]))
 {
 	$j = 0;
 	$emonth = "0".$edate[0];
 }
 else
 	$emonth = $edate[0].$edate[$j+0];
 

$eyear = $edate[$j+1].$edate[$j+2].$edate[$j+3].$edate[$j+4]; 	
?>
<div align="center">
<h3><?php echo JText::_("COM_REDSHOP_CREDIT_CARD_INFO");?></h3>
<table class="adminlist">
	<tr><th><?php echo JText::_('COM_REDSHOP_CREDIT_CARD_NAME')?>::</th><td><?php echo base64_decode($ccdetail->order_payment_cardname);?></td></tr>
	<tr><th><?php echo JText::_('COM_REDSHOP_CREDIT_CARD_NUMBER')?>::</th><td><?php echo $cardnumber;?></td></tr>
	<tr><th><?php echo JText::_('COM_REDSHOP_CREDIT_CARD_EXPIRY_DATE')?>::</th><td><?php echo $emonth."/".$eyear;?></td></tr>
	<tr><th><?php echo JText::_('COM_REDSHOP_CREDIT_CARD_SECURITY_NUMBER')?>::</th><td><?php echo  base64_decode($ccdetail->order_payment_ccv);?></td></tr>
</table>
</div>