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
<div class="views" id="cpanel">
	<?php foreach ($this->views as $view => $info) : ?>
		<div>
			<div class="icon">
				<a href="index.php?option=com_redshop&view=accessmanager&section=<?php echo $view; ?>">
					<img
						alt="<?php echo $view; ?>"
						src="components/com_redshop/assets/images/<?php echo $info['icon']; ?>"
					><span><?php echo JText::_($info['text']); ?></span>
				</a>
			</div>
		</div>
	<?php endforeach; ?>
</div>
