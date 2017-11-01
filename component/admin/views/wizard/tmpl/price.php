<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$params = JFactory::getApplication()->input->get('params', '', 'raw');
?>

<form action="?option=com_redshop" method="POST" name="installform" id="installform">
    <div class="row">
        <div class="col-md-6">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_REDSHOP_PRICE'); ?></legend>
				<?php echo $this->loadTemplate('price'); ?>
            </fieldset>
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_REDSHOP_TAX_TAB'); ?></legend>
				<?php echo $this->loadTemplate('vat'); ?>
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_REDSHOP_DISCOUPON_TAB'); ?></legend>
				<?php echo $this->loadTemplate('discount'); ?>
            </fieldset>
        </div>
    </div>
    <input type="hidden" name="view" value="wizard"/>
    <input type="hidden" name="task" value="save"/>
    <input type="hidden" name="substep" value="<?php echo $params->step; ?>"/>
    <input type="hidden" name="go" value=""/>
</form>
