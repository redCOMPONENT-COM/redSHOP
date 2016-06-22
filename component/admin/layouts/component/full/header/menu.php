<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$logoutUrl  = JRoute::_('index.php?option=com_login&task=logout&' . JSession::getFormToken() . '=1');
$user = JFactory::getUser();
?>
<ul class="nav navbar-nav">
	<?php echo RedshopLayoutHelper::render('alert.header_link') ?>
	<li>
		<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . $user->id) ?>">
			<span class="hidden-xs"><?php echo $user->name ?></span>
		</a>
	</li>

	<li>
		<a href="<?php echo $logoutUrl ?>">Logout</a>
	</li>
</ul>
