<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access'); ?>
<?php

		$forgot_password = $params->get('forgot_password',0);
		$forgot_username = $params->get('forgot_username',0);
		$creat_account   = $params->get('creat_account',0);
		$remember_me     = $params->get('remember_me',0);
		$layout			 = $params->get('layout',0);
		$Itemid =  JRequest::getVar('Itemid');



?>
<?php if($type == 'logout') : ?>
<form action="index.php" method="post" name="login" id="form-login">
<?php if ($params->get('greeting')) : ?>
	<div>
	<?php if ($params->get('name')) : {
		echo JText::sprintf( 'HINAME', $user->get('name') );
	} else : {
		echo JText::sprintf( 'HINAME', $user->get('username') );
	} endif; ?>
	</div>
<?php endif; ?>
	<div align="center">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" />
	</div>
	<div align="left" class="accountLink">
		<?php
			$accountPage = JRoute::_('index.php?option=com_redshop&view=account&Itemid='.$Itemid);
		?>
		<a href="<?php echo $accountPage;?>" name="<?php echo JText::_('MY_ACCOUNT');?>" title="<?php echo JText::_('MY_ACCOUNT');?>"><?php echo JText::_('MY_ACCOUNT');?></a>
	</div>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php else : ?>
<?php
if(JPluginHelper::isEnabled('authentication', 'openid')) :
		$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
		$langScript = 	'var JLanguage = {};'.
						' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
						' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
						' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
						' var modlogin = 1;';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration( $langScript );
		JHTML::_('script', 'openid.js');
endif; ?>
<?php if(!$layout){ ?>
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	<?php echo $params->get('pretext'); ?>
	<div id="form-login-wrapper">
		<div id="input-login-wrapper">
			<fieldset class="input">
			<div id="form-login-username" style="margin-bottom:5px;">
				<div id="label-login-username"><label for="modlgn_username"><?php echo JText::_('Username') ?></label></div>
				<div id="input-login-username"><input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" /></div>
			</div>
			<div id="form-login-password" style="margin-bottom:5px;">
				<div id="label-login-password"><label for="modlgn_passwd"><?php echo JText::_('Password') ?></label></div>
				<div id="input-login-password"><input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" /></div>
			</div>


			<?php
			if($remember_me){

					if(JPluginHelper::isEnabled('system', 'remember')) : ?>
						<div id="form-login-remember" style="margin-bottom:5px;">
							<label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
							<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
						</div>
					<?php endif; ?>
			<?php } ?>
			<div id="submit-login-button" style="margin-bottom:5px;"><input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" /></div>
			</fieldset>
		</div>
		<div id="link-login-wrapper">

			<?php if($forgot_password){ ?>

						<div id="link-login-lostpassword" style="margin-bottom:5px;">
							<a href="<?php echo JRoute::_( 'index.php?option=com_redshop&view=password' ); ?>">
							<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
						</div>

			<?php } ?>

			<?php if($forgot_username){ ?>

					<div id="link-login-username" style="margin-bottom:5px;">
						<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
						<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
					</div>

			<?php } ?>

			<?php

			if($creat_account){
				$usersConfig = &JComponentHelper::getParams( 'com_users' );
				$usersConfig->set('allowUserRegistration',1);
				if ($usersConfig->get('allowUserRegistration')) : ?>
					<div id="link-login-register">
						<a href="<?php echo JRoute::_( 'index.php?option=com_redshop&view=registration&Itemid='.$rItemid ); ?>">
							<?php echo JText::_('REGISTER'); ?></a>
					</div>
				<?php endif; ?>

			<?php } ?>
		</div>

		<input type="hidden" name="option" value="com_user" />
		<input type="hidden" name="task" value="login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<div style="clear:left"></div>
	</div>
	<?php echo $params->get('posttext'); ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<?php } else{ ?>
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	<?php echo $params->get('pretext'); ?>
	<table>
		<tr><td><label for="modlgn_username"><?php echo JText::_('Username') ?></label></td>
			<td colspan="2"><label for="modlgn_passwd"><?php echo JText::_('Password') ?></label></td>
			</tr>
		<tr><td><input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="25" /></td>
			<td ><input id="modlgn_passwd" type="password" name="password" class="inputbox" size="25" alt="password" /></td>
			<td>&nbsp;<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" /></td>
		</tr>
		<tr><td><?php if($forgot_password){ ?>

						<div id="link-login-lostpassword" style="margin-bottom:5px;">
							<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
							<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
						</div>

				<?php } ?>
				<?php if($forgot_username){ ?>

					<div id="link-login-username" style="margin-bottom:5px;">
						<a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
						<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
					</div>

				<?php } ?>
				<?php

			if($creat_account){
				$usersConfig = &JComponentHelper::getParams( 'com_users' );
				$usersConfig->set('allowUserRegistration',1);
				if ($usersConfig->get('allowUserRegistration')) : ?>
					<div id="link-login-register">
						<a href="<?php echo JRoute::_( 'index.php?option=com_redshop&view=registration&Itemid='.$rItemid ); ?>">
							<?php echo JText::_('REGISTER'); ?></a>
					</div>
				<?php endif; ?>

			<?php } ?>
				</td>

				<td valign="top">
				<?php
					if($remember_me){

							if(JPluginHelper::isEnabled('system', 'remember')) : ?>
								<div id="form-login-remember" style="margin-bottom:5px;">
									<label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
									<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
								</div>
							<?php endif; ?>
					<?php } ?>
				</td>
		</tr>
	</table>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo $params->get('posttext'); ?>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<?php } ?>

<?php endif; ?>