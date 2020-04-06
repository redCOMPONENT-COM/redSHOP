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
 * @since  3.0.1
 */
class RedshopTagsSectionsProductSample extends RedshopTagsAbstract
{
	public function init()
	{
		JText::script('COM_REDSHOP_SELECT_CATALOG');
		JText::script('COM_REDSHOP_ENTER_NAME');
		JText::script('COM_REDSHOP_ENTER_AN_EMAIL_ADDRESS');
		JText::script('COM_REDSHOP_EMAIL_ADDRESS_NOT_VALID');
	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY__VERSION__
	 */
	public function replace()
	{
		$app = JFactory::getApplication();

		$itemId = $app->input->getInt('Itemid');
		$layout = $app->input->getCmd('layout', 'default');

		$this->replaceProductSample();

		if ($this->isTagExists('{address_fields}')) {
			$this->replacements['{address_fields}'] = RedshopLayoutHelper::render(
				'tags.common.tag',
				[
					'tag'  => 'table',
					'text' => RedshopHelperExtrafields::listAllField(RedshopHelperExtrafields::SECTION_COLOR_SAMPLE, 0)
				],
				'',
				$this->optionLayout
			);
		}

		$this->replacements['{name}'] = RedshopLayoutHelper::render(
			'tags.common.input',
			[
				'type' => 'text',
				'name' => 'name_2',
				'id'   => 'name'
			],
			'',
			$this->optionLayout
		);

		$this->replacements['{name_lbl}']  = JText::_('COM_REDSHOP_NAME_LBL');
		$this->replacements['{email_lbl}'] = JText::_('COM_REDSHOP_EMAIL_LBL');

		$this->replacements['{email_address}'] = RedshopLayoutHelper::render(
			'tags.common.input',
			[
				'type' => 'text',
				'name' => 'email_address',
				'id'   => 'email_address'
			],
			'',
			$this->optionLayout
		);

		$this->replacements['{submit_button_sample}'] = RedshopLayoutHelper::render(
			'tags.common.input',
			[
				'type'  => 'submit',
				'name'  => 'samplesend',
				'id'    => 'samplesend',
				'attr'  => 'onClick="return getCatalogSampleValidation();"',
				'value' => JText::_('COM_REDSHOP_SAMPLE_SEND')
			],
			'',
			$this->optionLayout
		);

		$this->template = $this->strReplace($this->replacements, $this->template);

		$db          = JFactory::getDbo();
		$pageHeading = RedshopLayoutHelper::render(
			'tags.common.pageheading',
			[
				'class'          => 'product-sample',
				'pageheading'    => $db->escape($this->data['params']->get('page_title')),
				'params'         => $this->data['params'],
				'pageHeadingTag' => ''
			],
			'',
			$this->optionLayout
		);

		$this->template = $pageHeading . RedshopLayoutHelper::render(
				'tags.product_sample.form',
				[
					'content' => $this->template,
					'itemId'  => $itemId,
					'layout'  => $layout
				],
				'',
				$this->optionLayout
			);

		return parent::replace();
	}

	/**
	 * Replace product sample
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION
	 */
	private function replaceProductSample()
	{
		if ($this->isTagExists('{product_samples}')) {
			$catalogSample = \Redshop\Catalog\Sample::getCatalogSampleList();
			$sampleData    = "";

			for ($k = 0, $kn = count($catalogSample); $k < $kn; $k++) {
				$sampleData .= RedshopLayoutHelper::render(
					'tags.product_sample.sample',
					[
						'catalogSample' => $catalogSample[$k]
					],
					'',
					$this->optionLayout
				);
			}

			$this->replacements['{product_samples}'] = $sampleData;
		}
	}
}