<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

extract($displayData);

?>

<ul class='nav nav-wizard'>
	<li <?php echo ($step == 1) ? "class='active'" : "" ?>>
		<a data-toggle="tab">
			<?php echo JText::_('COM_REDSHOP_ORDER_INFORMATION');?>
		</a>
	</li>
	<li <?php echo ($step == 2) ? "class='active'" : "" ?>>
		<a data-toggle="tab">
			<?php echo JText::_('COM_REDSHOP_PAYMENT');?>
		</a>
	</li>
	<li <?php echo ($step == 3) ? "class='active'" : "" ?>>
		<a data-toggle="tab">
			<?php echo JText::_('COM_REDSHOP_RECEIPT');?>
		</a>
	</li>
</ul>
