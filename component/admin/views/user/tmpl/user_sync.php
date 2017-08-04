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
<div id="element-box">
	<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>

	</div>
	<div class="m">
		<div>
			<div>
				<div>
					<?php
					if ($this->sync_user)
					{
						echo '<font color=green>';
						echo JText::_("COM_REDSHOP_ADDED");
						echo ' ' . $this->sync_user . ' ';
						echo JText::_("COM_REDSHOP_YES_SYNC");
						echo '.</font>';
					}
					else
					{
						echo '<font color=green>';
						echo JText::_('COM_REDSHOP_NO_SYNC');
						echo '!</font>';
					}
					?>
				</div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
	<div class="b">
		<div class="b">
			<div class="b"></div>

		</div>
	</div>
</div>
