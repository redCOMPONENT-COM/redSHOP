<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
		<a title="Joomla" href="<?php echo JRoute::_('index.php') ?>"><i class="fa fa-joomla"></i></a>
	</li>

	<li>
		<a title="<?php echo JText::_('JHELP'); ?>" href="#" onclick="Joomla.popupWindow('http://docs.redcomponent.com/collection/171-redshop', 'Help', 700, 500, 1)">
			<i class="fa fa-question-circle"></i>

		</a>
	</li>

	<li>
		<a title="<?php echo JText::_('JGLOBAL_VIEW_SITE'); ?>" href="<?php echo JUri::root() ?>" target="_blank"><i class="fa fa-external-link"></i></a>
	</li>
	<li>
		<a title="<?php echo JText::_('JLOGOUT'); ?>" href="<?php echo $logoutUrl ?>"><i class="fa fa-sign-out"></i></a>
	</li>
</ul>
