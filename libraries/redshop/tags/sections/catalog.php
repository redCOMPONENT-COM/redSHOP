<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  2.1
 */
class RedshopTagsSectionsCaTaLog extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  2.1.5
	 */
	public $model = array();

	/**
	 * @var    int
	 *
	 * @since  2.1.5
	 */
	public $itemId;

	/**
	 * Init function
	 * @return mixed|void
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function init()
	{
		$this->model = $this->data['model'];
		$this->itemId = $this->data['itemId'];
		JText::script('COM_REDSHOP_SELECT_CATALOG');
		JText::script('COM_REDSHOP_ENTER_NAME');
		JText::script('COM_REDSHOP_ENTER_AN_EMAIL_ADDRESS');
		JText::script('COM_REDSHOP_EMAIL_ADDRESS_NOT_VALID');
	}

	/**
	 * Executing replace
	 * @return string
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function replace()
	{
		if ($this->isTagExists('{catalog_select}'))
		{
			$catalog        = $this->model->getCatalogList();
			$optionselect   = [];
			$optionselect[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
			$catalog_select = array_merge($optionselect, $catalog);

			$catalogsel = JHTML::_('select.genericlist', $catalog_select, 'catalog_id', 'class="inputbox" size="1" ', 'value', 'text', 0);

			$this->replacements["{catalog_select}"] = $catalogsel;
			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		if ($this->isTagExists('{name_lbl}'))
		{
			$nameLbl = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'text' => JText::_('COM_REDSHOP_NAME_LBL'),
					'tag' => 'div',
					'class' => 'name_lbl'
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{name_lbl}"] = $nameLbl;
			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		if ($this->isTagExists('{name}'))
		{
			$name = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name'  => 'name_2',
					'id'    => 'name',
					'type'  => 'text'
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{name}"] = $name;
			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		if ($this->isTagExists('{email_lbl}'))
		{
			$emailLbl = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'text' => JText::_('COM_REDSHOP_EMAIL_LBL'),
					'tag' => 'div',
					'class' => 'email_lbl'
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{email_lbl}"] = $emailLbl;
			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		if ($this->isTagExists('{email_address}'))
		{
			$nameAddress = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name'  => 'email_address',
					'id'    => 'email_address',
					'type'  => 'text'
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{email_address}"] = $nameAddress;
			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		if ($this->isTagExists('{submit_button_catalog}'))
		{
			$submitButton = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'type'      => 'submit',
					'id'        => 'catalogsend',
					'name'      => 'catalogsend',
					'attr'      => 'onclick="return getCatalogValidation()"',
					'value'     => JText::_('COM_REDSHOP_CATALOG_SEND')
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{submit_button_catalog}"] = $submitButton;
			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		$layout = RedshopLayoutHelper::render(
			'tags.catalog.template',
			array(
				'content' => $this->template,
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$this->template = $layout;

		return parent::replace();
	}
}