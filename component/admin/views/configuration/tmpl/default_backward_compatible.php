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

<fieldset class="adminform">
	<div class="row">
		<div class="col-sm-6">
			<fieldset class="adminform">
				<div class="form-group">
					<span class="editlinktip hasTip"
						  title="<?php echo JText::_('COM_REDSHOP_BACKWARD_COMPATIBLE_JS_SCRIPT') ?>::<?php echo JText::_('COM_REDSHOP_BACKWARD_COMPATIBLE_JS_SCRIPT_TOOLTIP') ?>">
						<label for="backward_compatible_js"><?php echo JText::_('COM_REDSHOP_BACKWARD_COMPATIBLE_JS_SCRIPT') ?></label></span>
					<?php echo $this->lists['backward_compatible_js'];?>
				</div>
                <div class="form-group">
					<span class="editlinktip hasTip"
                          title="<?php echo JText::_('COM_REDSHOP_BACKWARD_COMPATIBLE_PHP_SCRIPT') ?>::<?php echo JText::_('COM_REDSHOP_BACKWARD_COMPATIBLE_PHP_SCRIPT_TOOLTIP') ?>">
						<label for="backward_compatible_php"><?php echo JText::_('COM_REDSHOP_BACKWARD_COMPATIBLE_PHP_SCRIPT') ?></label></span>
					<?php echo $this->lists['backward_compatible_php'];?>
                </div>
			</fieldset>
		</div>
	</div>
</fieldset>
