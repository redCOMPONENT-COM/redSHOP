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
class RedshopTagsSectionsCompanyBillingTemplate extends RedshopTagsAbstract
{
	public $tags = array(
		'{company_extrafield}','{ean_number_lbl}', '{ean_number}', '{vat_number_lbl}', '{vat_number}',
		'{tax_exempt_lbl}', '{tax_exempt}'
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
				'prefix' => 'company-'
			)
		);
		$data     = $this->data['data'];
		$options  = RedshopLayoutHelper::$layoutOption;
		$hidden   = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'is_company',
				'id' => '',
				'type' => 'hidden',
				'value' => 1,
				'attr' => '',
				'class' => ''
			),
			'',
			$options
		);

		$this->template = $template . $hidden;
		$this->template = $this->replaceVatNumber($data, $options);
		$this->replaceTaxExempt($data, $options);

		if ($this->isTagExists('{ean_number_lbl}'))
		{
			$htmlEanNumberLbl = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'id' => 'ean_number',
					'class' => '',
					'text' => JText::_('COM_REDSHOP_EAN_NUMBER')
				),
				'',
				$options
			);

			$this->addReplace('{ean_number_lbl}', $htmlEanNumberLbl);
		}

		if ($this->isTagExists('{ean_number}'))
		{
			$htmlEanNumber = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'id' => 'ean_number',
					'name' => 'ean_number',
					'type' => 'text',
					'value' => (isset($data["ean_number"]) ? $data["ean_number"] : ''),
					'class' => 'inputbox form-control',
					'attr' => 'size="32" maxlength="250"'
				),
				'',
				$options
			);

			$this->addReplace('{ean_number}', $htmlEanNumber);
		}

		if ($this->isTagExists(('{company_extrafield}')))
		{
			$companyExtraFields = (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 1 &&  !empty($this->data['lists']['extra_field_company'])) ?
				$this->data['lists']['extra_field_company'] : "";

			$this->addReplace('{company_extrafield}', $companyExtraFields);
		}

		return parent::replace();
	}

	/**
	 * Replace tax exempt
	 *
	 * @param   array  $data
	 * @param   array  $options
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public function replaceTaxExempt($data, $options)
	{
		if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1 && Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT'))
		{
			$allowCompany = isset($data['is_company']) && 1 != (int) $data['is_company'] ? 'style="display:none;"' : '';
			$taxExempt    = isset($data["tax_exempt"]) ? $data["tax_exempt"] : '';

			$taxExemptHtml = JHtml::_(
				'select.booleanlist',
				'tax_exempt',
				'class="inputbox form-control" ',
				$taxExempt,
				JText::_('COM_REDSHOP_COMPANY_IS_VAT_EXEMPTED'),
				JText::_('COM_REDSHOP_COMPANY_IS_NOT_VAT_EXEMPTED')
			);

			if ($this->isTagExists('{tax_exempt_lbl}'))
			{
				$htmlTaxExemptLbl = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'text' => JText::_('COM_REDSHOP_TAX_EXEMPT'),
						'tag' => 'div',
						'id' => 'lblTaxExempt' . $allowCompany,
						'class' => '',
						'attr' => ''
					),
					'',
					$options
				);

				$this->addReplace('{tax_exempt_lbl}', $htmlTaxExemptLbl);
			}

			if ($this->isTagExists('{tax_exempt}'))
			{
				$htmlTaxExempt = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'text' => $taxExemptHtml,
						'tag' => 'div',
						'id' => 'trTaxExempt' . $allowCompany,
						'class' => '',
						'attr' => ''
					),
					'',
					$options
				);

				$this->addReplace('{tax_exempt}', $htmlTaxExempt);
			}
		}
		else
		{
			$this->addReplace('{tax_exempt_lbl}', '');
			$this->addReplace('{tax_exempt}', '');
		}
	}

	/**
	 * Replace vat number
	 *
	 * @param   array  $data
	 * @param   array  $options
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public function replaceVatNumber($data, $options)
	{
		$subTemplate = $this->getTemplateBetweenLoop('{vat_number_start}', '{vat_number_end}');

		if (!empty($subTemplate))
		{
			$html = '';

			if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1)
			{
				$html          = $subTemplate['template'];
				$classRequired = Redshop::getConfig()->get('REQUIRED_VAT_NUMBER') == 1 ? "required" : "";

				if ($this->isTagExists('{vat_number_lbl}'))
				{
					$htmlVatNumberLbl = RedshopLayoutHelper::render(
						'tags.common.label',
						array(
							'id' => 'vat_number',
							'class' => '',
							'text' => JText::_('COM_REDSHOP_VAT_NUMBER')
						),
						'',
						$options
					);

					$this->addReplace('{vat_number_lbl}', $htmlVatNumberLbl);
				}

				if ($this->isTagExists('{vat_number}'))
				{
					$htmlVatNumber = RedshopLayoutHelper::render(
						'tags.common.input',
						array(
							'id' => 'vat_number',
							'name' => 'vat_number',
							'type' => 'text',
							'value' => (isset($data["vat_number"]) ? $data["vat_number"] : ''),
							'class' => 'inputbox form-control ' . $classRequired,
							'attr' => 'size="32" maxlength="250"'
						),
						'',
						$options
					);

					$this->addReplace('{vat_number}', $htmlVatNumber);
				}
			}

			return $subTemplate['begin'] . $html . $subTemplate['end'];
		}

		return $this->template;
	}
}
