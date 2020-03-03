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
 * @since  3.0
 */
class RedshopTagsSectionsPrivateBillingTemplate extends RedshopTagsAbstract
{
	public $tags = array(
		'{private_extrafield}'
	);

	public function init()
	{

	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   2.0.0.5
	 */
	public function replace()
	{
		$template = RedshopTagsReplacer::_(
				'commonfield',
				$this->template,
				array(
					'data' => $this->data['data'],
					'lists' => $this->data['lists'],
					'prefix' => 'private-'
				)
			);

		$hidden = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'is_company',
				'id' => '',
				'type' => 'hidden',
				'value' => 0,
				'attr' => '',
				'class' => ''
			),
			'',
			RedshopLayoutHelper::$layoutOption
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
