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
class RedshopTagsSectionsPrivateBillingTemplate extends RedshopTagsAbstract
{
	public $tags = array(
		'{private_extrafield}'
	);

	public function init()
	{

	}

	public function replace()
	{
		$template = RedshopTagsReplacer::_(
				'commonfield',
				$this->template,
				array(
					'data' => array(),
					'lists' => $this->data['lists'],
					'prefix' => 'private-'
				)
			);

		$hidden = RedshopLayoutHelper::render(
			'tags.common.hidden',
			array(
				'name' => 'is_company',
				'id' => '',
				'value' => 0,
				'attr' => ''
			),
			'',
			array(
				'component'  => 'com_redshop',
				'layoutType' => 'Twig',
				'layoutOf'   => 'library'
			)
		);

		$this->template = $template . $hidden;

		if ($this->isTagExists(('{private_extrafield}')))
		{
			$userExtraFields = Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 2 && $this->data['lists']['extra_field_user'] != "" ?
				$this->data['lists']['extra_field_user'] : '';

			$this->addReplace('{private_extrafield}', $userExtraFields);
		}

		return parent::replace();
	}
}