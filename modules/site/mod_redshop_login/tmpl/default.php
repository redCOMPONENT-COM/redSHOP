<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_login
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$forgot_password = $params->get('forgot_password', 0);
$forgot_username = $params->get('forgot_username', 0);
$creat_account = $params->get('creat_account', 0);
$remember_me = $params->get('remember_me', 0);
$layout = $params->get('layout', 0);
$Itemid = JRequest::getInt('Itemid');

?>
<?php if ($type == 'logout') : ?>
	<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login">
		<?php if ($params->get('greeting')) : ?>
			<div>
				<?php if ($params->get('name')) : {
					echo JText::sprintf('COM_REDSHOP_HINAME', $user->get('name'));
				}
				else : {
					echo JText::sprintf('COM_REDSHOP_HINAME', $user->get('username'));
				} endif; ?>
			</div>
		<?php endif; ?>
		<div align="center">
			<input type="submit" name="Submit" class="button"
			       value="<?php echo JText::_('COM_REDSHOP_BUTTON_LOGOUT'); ?>"/>
		</div>
		<div align="left" class="accountLink">
			<?php
			$accountPage = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $Itemid);
			?>
			<a href="<?php echo $accountPage; ?>" name="<?php echo JText::_('COM_REDSHOP_MY_ACCOUNT'); ?>"
			   title="<?php echo JText::_('COM_REDSHOP_MY_ACCOUNT'); ?>"><?php echo JText::_('COM_REDSHOP_MY_ACCOUNT');?></a>
		</div>
		<input type="hidden" name="option" value="com_users"/>
		<input type="hidden" name="task" value="user.logout"/>
		<input type="hidden" name="return" value="<?php echo $return; ?>"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
<?php else : ?>
	<?php
	if (JPluginHelper::isEnabled('authentication', 'openid')) :
		$lang->load('plg_authentication_openid', JPATH_ADMINISTRATOR);
		$langScript = 'var JLanguage = {};' .
			' JLanguage.WHAT_IS_OPENID = \'' . JText::_('COM_REDSHOP_WHAT_IS_OPENID') . '\';' .
			' JLanguage.LOGIN_WITH_OPENID = \'' . JText::_('COM_REDSHOP_LOGIN_WITH_OPENID') . '\';' .
			' JLanguage.NORMAL_LOGIN = \'' . JText::_('COM_REDSHOP_NORMAL_LOGIN') . '\';' .
			' var modlogin = 1;';
		$document   = JFactory::getDocument();
		$document->addScriptDeclaration($langScript);
		JHTML::_('script', 'openid.js');
	endif; ?>
	<?php if (!$layout)
	{ ?>
		<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="login"
		      id="form-login">
			<?php echo $params->get('pretext'); ?>
			<div id="form-login-wrapper">
				<div id="input-login-wrapper">
					<fieldset class="input">
						<div id="form-login-username" style="margin-bottom:5px;">
							<div id="label-login-username"><label
									for="modlgn_username"><?php echo JText::_('COM_REDSHOP_Username') ?></label></div>
							<div id="input-login-username"><input id="modlgn_username" type="text" name="username"
							                                      class="inputbox" alt="username" size="18"/></div>
						</div>
						<div id="form-login-password" style="margin-bottom:5px;">
							<div id="label-login-password"><label
									for="modlgn_passwd"><?php echo JText::_('COM_REDSHOP_Password') ?></label></div>
							<div id="input-login-password"><input id="modlgn_passwd" type="password" name="password"
							                                      class="inputbox" size="18" alt="password"/></div>
						</div>


						<?php
						if ($remember_me)
						{

							if (JPluginHelper::isEnabled('system', 'remember')) : ?>
								<div id="form-login-remember" style="margin-bottom:5px;">
									<label
										for="modlgn_remember"><?php echo JText::_('COM_REDSHOP_REMEMBER_ME') ?></label>
									<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox"
									       value="yes" alt="Remember Me"/>
								</div>
							<?php endif; ?>
						<?php } ?>
						<div id="submit-login-button" style="margin-bottom:5px;"><input type="submit" name="Submit"
						                                                                class="button"
						                                                                value="<?php echo JText::_('COM_REDSHOP_LOGIN') ?>"/>
						</div>
					</fieldset>
				</div>
				<div id="link-login-wrapper">

					<?php if ($forgot_password)
					{ ?>

						<div id="link-login-lostpassword" style="margin-bottom:5px;">
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
								<?php echo JText::_('COM_REDSHOP_FORGOT_YOUR_PASSWORD'); ?></a>
						</div>

					<?php } ?>

					<?php if ($forgot_username)
					{ ?>

						<div id="link-login-username" style="margin-bottom:5px;">
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
								<?php echo JText::_('COM_REDSHOP_FORGOT_YOUR_USERNAME'); ?></a>
						</div>

					<?php } ?>

					<?php

					if ($creat_account)
					{
						$usersConfig = JComponentHelper::getParams('com_users');
						$usersConfig->set('allowUserRegistration', 1);
						if ($usersConfig->get('allowUserRegistration')) : ?>
							<div id="link-login-register">
								<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=registration&Itemid=' . $rItemid); ?>">
									<?php echo JText::_('COM_REDSHOP_REGISTER'); ?></a>
							</div>
						<?php endif; ?>

					<?php } ?>
				</div>

				<input type="hidden" name="option" value="com_users"/>
				<input type="hidden" name="task" value="user.login"/>
				<input type="hidden" name="return" value="<?php echo $return; ?>"/>

				<div style="clear:left"></div>
			</div>
			<?php echo $params->get('posttext'); ?>
			<?php echo JHTML::_('form.token'); ?>
		</form>

	<?php }
	else
	{ ?>
		<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="login"
		      id="form-login">
			<?php echo $params->get('pretext'); ?>
			<table>
				<tr>
					<td><label for="modlgn_username"><?php echo JText::_('COM_REDSHOP_Username') ?></label></td>
					<td colspan="2"><label for="modlgn_passwd"><?php echo JText::_('COM_REDSHOP_Password') ?></label>
					</td>
				</tr>
				<tr>
					<td><input id="modlgn_username" type="text" name="username" class="inputbox" alt="username"
					           size="25"/></td>
					<td><input id="modlgn_passwd" type="password" name="password" class="inputbox" size="25"
					           alt="password"/></td>
					<td>&nbsp;<input type="submit" name="Submit" class="button"
					                 value="<?php echo JText::_('COM_REDSHOP_LOGIN') ?>"/></td>
				</tr>
				<tr>
					<td><?php if ($forgot_password)
						{ ?>

							<div id="link-login-lostpassword" style="margin-bottom:5px;">
								<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
									<?php echo JText::_('COM_REDSHOP_FORGOT_YOUR_PASSWORD'); ?></a>
							</div>

						<?php } ?>
						<?php if ($forgot_username)
						{ ?>

							<div id="link-login-username" style="margin-bottom:5px;">
								<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
									<?php echo JText::_('COM_REDSHOP_FORGOT_YOUR_USERNAME'); ?></a>
							</div>

						<?php } ?>
						<?php

						if ($creat_account)
						{
							$usersConfig = JComponentHelper::getParams('com_users');
							$usersConfig->set('allowUserRegistration', 1);
							if ($usersConfig->get('allowUserRegistration')) : ?>
								<div id="link-login-register">
									<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=registration&Itemid=' . $rItemid); ?>">
										<?php echo JText::_('COM_REDSHOP_REGISTER'); ?></a>
								</div>
							<?php endif; ?>

						<?php } ?>
					</td>

					<td valign="top">
						<?php
						if ($remember_me)
						{

							if (JPluginHelper::isEnabled('system', 'remember')) : ?>
								<div id="form-login-remember" style="margin-bottom:5px;">
									<label
										for="modlgn_remember"><?php echo JText::_('COM_REDSHOP_REMEMBER_ME') ?></label>
									<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox"
									       value="yes" alt="Remember Me"/>
								</div>
							<?php endif; ?>
						<?php } ?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="option" value="com_users"/>
			<input type="hidden" name="task" value="user.login"/>
			<input type="hidden" name="return" value="<?php echo $return; ?>"/>
			<?php echo $params->get('posttext'); ?>
			<?php echo JHTML::_('form.token'); ?>
		</form>

	<?php } ?>

<?php endif; ?>
