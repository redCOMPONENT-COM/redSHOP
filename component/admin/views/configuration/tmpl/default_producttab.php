<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top" align="left">
		<td colspan="2">
			<fieldset>
				<legend><?php echo JText::_('COM_REDSHOP_PRODUCT_INTRO_TAB'); ?></legend>
				<?php echo JText::_('COM_REDSHOP_PRODUCT_INTRO');?>
			</fieldset>
		</td>
	</tr>
</table>
<?php
echo $this->pane->startPane('stat-pane');
echo $this->pane->startPanel(JText::_('COM_REDSHOP_PRODUCT'), 'events');
?>

<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('product_unit');?>
			</fieldset>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('download');?>
			</fieldset>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('wrapping');?>
			</fieldset>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('catalog');?>
			</fieldset>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('color_sample');?>
			</fieldset>
		</td>
		<td width="50%">
			<?php echo $this->loadTemplate('product_template_image_settings');?>
		</td>
	</tr>
</table>
<?php
echo $this->pane->endPanel();
echo $this->pane->startPanel(JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_TAB'), 'events');
echo $this->loadTemplate('accessory_product');
echo $this->pane->endPanel();
echo $this->pane->startPanel(JText::_('COM_REDSHOP_RELATED_PRODUCTS'), 'events');
echo $this->loadTemplate('related_product');
echo $this->pane->endPanel();
echo $this->pane->endPane();?>
