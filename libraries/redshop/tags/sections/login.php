<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsLogin extends RedshopTagsAbstract
{
	public $tags = array(
		'{rs_username}', '{rs_username_lbl}', '{rs_password}', '{rs_password_lbl}',
		'{rs_login_button}', '{forget_password_link}'
	);

	public function init()
	{

	}

	public function replace()
	{
		$layoutOption = RedshopLayoutHelper::$layoutOption;

		$txtUsername = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'id' => 'username',
				'name' => 'username',
				'class' => 'inputbox',
				'type' => 'text'
			),
			'',
			$layoutOption
		);

		$this->addReplace('{rs_username}', $txtUsername);

		$usernameLbl = RedshopLayoutHelper::render(
			'tags.common.label',
			array(
				'id' => 'username',
				'name' => 'username',
				'text' => JText::_('COM_REDSHOP_USERNAME'),
			),
			'',
			$layoutOption
		);

		$this->addReplace('{rs_username_lbl}', $usernameLbl);

		$txtPassword = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'id' => 'password',
				'name' => 'password',
				'class' => 'inputbox',
				'type' => 'password'
			),
			'',
			$layoutOption
		);

		$this->addReplace('{rs_password}', $txtPassword);

		$passwordLbl = RedshopLayoutHelper::render(
			'tags.common.label',
			array(
				'id' => 'password',
				'name' => 'password',
				'text' => JText::_('COM_REDSHOP_PASSWORD')
			),
			'',
			$layoutOption
		);

		$this->addReplace('{rs_password_lbl}', $passwordLbl);

		$loginButton = RedshopLayoutHelper::render(
			'tags.login.button',
			array(
				'returnUrl' => $this->data['returnUrl'],
				'Itemid' => $this->data['Itemid']
			),
			'',
			$layoutOption
		);

		$this->addReplace('{rs_login_button}', $loginButton);

		$forgotPwd = RedshopLayoutHelper::render(
			'tags.common.link',
			array(
				'link' => JRoute::_('index.php?option=com_users&view=reset'),
				'content' => JText::_('COM_REDSHOP_FORGOT_PWD_LINK')
			),
			'',
			$layoutOption
		);

		$this->addReplace('{forget_password_link}', $forgotPwd);

		$this->template = RedshopLayoutHelper::render(
			'tags.login.form',
			array(
				'content' => $this->template
			),
			'',
			$layoutOption
		);

		return parent::replace();
	}
}