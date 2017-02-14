<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>

<?php
echo JHtml::_('bootstrap.startTabSet', 'feature-pane', array('active' => 'rating'));
echo JHtml::_('bootstrap.addTab', 'feature-pane', 'rating', JText::_('COM_REDSHOP_RATING', true));
?>
<fieldset class="adminform">
	<?php echo $this->loadTemplate('rating_settings');?>
</fieldset>

<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.addTab', 'feature-pane', 'comparison', JText::_('COM_REDSHOP_COMPARISON_PRODUCT_TAB', true));?>

<fieldset class="adminform">
	<?php echo $this->loadTemplate('comparison_settings');?>
</fieldset>

<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.addTab', 'feature-pane', 'stockroom', JText::_('COM_REDSHOP_STOCKROOM_TAB', true));?>

<fieldset class="adminform">
	<?php echo $this->loadTemplate('stockroom_settings');?>
</fieldset>
<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.addTab', 'feature-pane', 'import_export', JText::_('COM_REDSHOP_IMPORT_EXPORT_TAB', true));?>
    <fieldset class="adminform">
		<?php echo $this->loadTemplate('feature_import_export') ?>
    </fieldset>
<?php
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.endTabSet');
