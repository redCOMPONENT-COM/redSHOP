<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);

?>
<a href="<?php echo $link; ?>" title="<?php echo $description; ?>">
	<?php echo $title; ?>
	<small class="muted">
		<?php echo JText::_($description); ?>
	</small>
</a>
