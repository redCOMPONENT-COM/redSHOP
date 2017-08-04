<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<?php if (!Redshop::getConfig()->isExists()) : ?>
	<form name="getconfig" id="getconfig" action="index.php?option=com_redshop" method="post">
		<h3>
			<?php echo JText::_('COM_REDSHOP_GET_BACK_CONFIG_FILE');?>
			<a class="btn btn-primary" href="index.php?option=com_redshop&view=redshop&task=getDefaultConfig">
				<?php echo JText::_('COM_REDSHOP_CREATE_CONFIG'); ?>
			</a>
		</h3>
	</form>
<?php endif; ?>
