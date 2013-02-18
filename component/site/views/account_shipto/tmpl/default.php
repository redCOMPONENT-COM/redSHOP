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
defined ('_JEXEC') or die ('restricted access');

$Itemid = JRequest::getVar('Itemid');
$option = JRequest::getVar('option');

$add_addlink="index.php?option=".$option."&view=account_shipto&task=addshipping&Itemid=".$Itemid;
$backlink="index.php?option=".$option."&view=account&Itemid=".$Itemid;

$pagetitle = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL');
if($this->params->get('show_page_heading',1)) 
{
	if ( $this->params->get('page_title') != $pagetitle)
	{	 ?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		        <?php echo $this->escape(JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL')); ?>
		</h1>
<?php
	}
	else 
	{	?>
	<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $pagetitle; ?></h1>
<?php
	}
} ?>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES' ); ?></legend>
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
<?php	if(OPTIONAL_SHIPPING_ADDRESS)
		{	?>	
		<tr><td>- <?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS' ); ?></td></tr>
<?php	}
		for($i=0;$i<count($this->shippingaddresses);$i++)
		{
			$edit_addlink="index.php?option=".$option."&view=account_shipto&task=addshipping&infoid=".$this->shippingaddresses[$i]->users_info_id."&Itemid=".$Itemid;?>
		<tr><td>
<?php	echo "- <a href='".JRoute::_($edit_addlink)."'>".$this->shippingaddresses[$i]->text."</a>"; ?>
		</td></tr>
<?php	} ?>
	<tr><td>&nbsp;</td></tr>
	<tr><td><a href="<?php echo JRoute::_($add_addlink); ?>"><?php echo JText::_('COM_REDSHOP_ADD_ADDRESS'); ?></a>&nbsp;
			<a href="<?php echo JRoute::_($backlink); ?>"><?php echo JText::_('COM_REDSHOP_BACK'); ?></a></td></tr>
	</table>
</fieldset>