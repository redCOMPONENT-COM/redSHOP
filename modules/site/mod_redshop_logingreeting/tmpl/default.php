<?php
/**
 * @package     redSHOP.Frontend
 * @subpackage  
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<div id="mod_logingreeting" class="mod_logingreeting<?php echo $classSuffix;?>">
<?php if ($user->id) : ?>
	<?php if ($params->def('logging_greeting', 1) == 0) : ?>
		<div class="logingreeting">
			<?php echo $greetingText . ' ' . $user->username; ?>
		</div>
	<?php else : ?>
		<div class="loginname">
			<?php echo $greetingText . ' ' . $orderHelper->getUserFullname($user->id); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
</div>