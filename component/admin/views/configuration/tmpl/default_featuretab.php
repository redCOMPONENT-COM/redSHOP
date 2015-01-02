<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td colspan="2">
			<fieldset>
				<legend><?php echo JText::_('COM_REDSHOP_FEATURE_SETTING_TAB'); ?></legend>
				<?php echo JText::_('COM_REDSHOP_FEATURE_SETTING');?>
			</fieldset>
		</td>
	</tr>
</table>
<?php
echo JHtml::_('sliders.start', 'feature-pane', array('startOffset' => 0));
echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_RATING'), 'rating');
?>
<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<?php echo $this->loadTemplate('rating_settings');?>
		</td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_COMPARISON_PRODUCT_TAB'), 'comparison'); ?>
<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<?php echo $this->loadTemplate('comparison_settings');?>
		</td>
	</tr>
</table>
<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_STOCKROOM_TAB'), 'stockroom'); ?>
<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td width="50%">
			<?php echo $this->loadTemplate('stockroom_settings');?>
		</td>
	</tr>
</table>
<?php  echo JHtml::_('sliders.end');
