<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$Itemid = JRequest::getVar('Itemid');
$option = JRequest::getVar('option');

$add_addlink = "index.php?option=" . $option . "&view=account_shipto&task=addshipping&Itemid=" . $Itemid;
$backlink = "index.php?option=" . $option . "&view=account&Itemid=" . $Itemid;

$pagetitle = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL');

if ($this->params->get('show_page_heading', 1))
{
	if ($this->params->get('page_title') != $pagetitle)
	{
		?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $this->escape(JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL')); ?>
		</h1>
	<?php
	}
	else
	{
		?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<?php echo $pagetitle; ?></h1>
	<?php
	}
} ?>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES'); ?></legend>
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
		<?php
		if (OPTIONAL_SHIPPING_ADDRESS)
		{
			?>
			<tr>
				<td>- <?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS'); ?></td>
			</tr>
		<?php
		}

		for ($i = 0; $i < count($this->shippingaddresses); $i++)
		{
			$edit_addlink = "index.php?option=" . $option . "&view=account_shipto&task=addshipping&infoid=" . $this->shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid;?>
			<tr>
				<td>
					<?php    echo "- <a href='" . JRoute::_($edit_addlink) . "'>" . $this->shippingaddresses[$i]->text . "</a>"; ?>
				</td>
			</tr>
		<?php
		}
		?>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a href="<?php echo JRoute::_($add_addlink); ?>">
					<?php echo JText::_('COM_REDSHOP_ADD_ADDRESS'); ?></a>&nbsp;
				<a href="<?php echo JRoute::_($backlink); ?>">
					<?php echo JText::_('COM_REDSHOP_BACK'); ?></a></td>
		</tr>
	</table>
</fieldset>
