<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app        = JFactory::getApplication();
$Itemid     = $app->input->getInt('Itemid');
$loginlink  = 'index.php?option=com_redshop&view=login&Itemid=' . $Itemid;
$mywishlist = $app->input->getString('wishlist');

if ($mywishlist != '')
{
	$newuser_link = 'index.php?wishlist=' . $mywishlist . '&option=com_redshop&view=registration&Itemid=' . $Itemid;
}
else
{
	$newuser_link = 'index.php?option=com_redshop&view=registration&Itemid=' . $Itemid;
}

$params       = $app->getParams('com_redshop');
$returnitemid = $params->get('login', $Itemid);

?>
<form action="<?php echo JRoute::_($loginlink); ?>" method="post">
	<div class="redshop-login form-horizontal">
		<p><?php echo JText::_('COM_REDSHOP_LOGIN_DESCRIPTION'); ?></p>
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php echo JText::_('COM_REDSHOP_USERNAME'); ?>:</label>
			<div class="col-sm-9"><input class="inputbox" type="text" id="username" name="username"/></div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label"><?php echo JText::_('COM_REDSHOP_PASSWORD'); ?>:</label>
			<div class="col-sm-9"><input class="inputbox" id="password" name="password" type="password"/></div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<a href="<?php echo JRoute::_($newuser_link); ?>">
					<?php echo JText::_('COM_REDSHOP_CREATE_USER_LINK'); ?></a>&nbsp;/&nbsp;<a
					href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo JText::_('COM_REDSHOP_FORGOT_PWD_LINK'); ?></a>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<input type="submit" name="submit" class="button btn btn-primary" value="<?php echo JText::_('COM_REDSHOP_LOGIN'); ?>">
			</div>
		</div>
	</div>

	<input type="hidden" name="task" id="task" value="setlogin">
	<input type="hidden" name="mywishlist" id="mywishlist" value="<?php echo $app->input->getString('wishlist'); ?>">
	<input type="hidden" name="product_id" id="product_id" value="<?php echo $app->input->getString('product_id'); ?>">
	<input type="hidden" name="returnitemid" id="returnitemid" value="<?php echo $returnitemid; ?>">
	<input type="hidden" name="option" id="option" value="com_redshop"/>
</form>
