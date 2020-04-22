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
class RedshopTagsSectionsShippingMethod extends RedshopTagsAbstract
{
	public $tags = array('{shipping_heading}', '{show_when_one_rate}');

	/**
	 * The dispatcher.
	 *
	 * @var  JEventDispatcher
	 */
	public $dispatcher;

	/**
	 * @var  integer
	 */
	public $rateExist = 0;

	/**
	 * @var  string
	 */
	public static $shipping_rate_id;

	public function init()
	{
		$this->dispatcher = $this->getDispatcher('redshop_shipping');
	}

	public function replace()
	{
		$subTemplate          = $this->getTemplateBetweenLoop('{shipping_method_loop_start}', '{shipping_method_loop_end}');
		$shippingMethod       = RedshopHelperOrder::getShippingMethodInfo();

		$this->addReplace('{shipping_heading}', JText::_('COM_REDSHOP_SHIPPING_METHOD'));

		if (!empty($subTemplate))
		{
			$templateMiddle = $subTemplate['template'];

			$templateRateMiddle = "";

			$template1 = $this->getTemplateBetweenLoop('{shipping_rate_loop_start}', '{shipping_rate_loop_end}');

			if (!empty($template1))
			{
				$templateRateMiddle = $template1['template'];
			}

			$rateData = "";

			if ($templateMiddle != "" && count($shippingMethod) > 0)
			{
				$shippingRate = $this->dispatcher->trigger('onListRates', array(&$this->data));

				if (count($shippingRate) <= 1 && count($shippingRate[0]) <= 1)
				{
					$this->addReplace('{show_when_one_rate}', 'none');
				}

				for ($s = 0, $sn = count($shippingMethod); $s < $sn; $s++)
				{
					$rateData .= $this->replaceShippingMethod($templateMiddle, $templateRateMiddle, $shippingRate[$s], $shippingMethod[$s]);
					$this->replaceExtraField($rateData, $shippingMethod[$s]);
				}
			}

			$this->addReplace('{show_when_one_rate}', 'block');
			$this->template = $subTemplate['begin'] . $rateData . $subTemplate['end'];
		}

		if ($this->rateExist == 0)
		{
			$this->template = "<div></div>";
		}

		return parent::replace();
	}

	/**
	 * Replace shipping method
	 *
	 * @param   string  $templateMiddle
	 * @param   string  $templateRateMiddle
	 * @param   array   $shippingRate
	 * @param   object  $shippingMethod
	 *
	 * @return  string|boolean
	 *
	 * @since   3.0
	 */
	public function replaceShippingMethod($templateMiddle, $templateRateMiddle, $shippingRate, $shippingMethod)
	{
		if (isset($shippingRate) === false)
		{
			return false;
		}

		$rate                   = $shippingRate;
		$rateData               = '';
		$this->replacements     = array();
		self::$shipping_rate_id = $this->data['shipping_rate_id'];

		if (!empty($rate))
		{
			if (empty(self::$shipping_rate_id))
			{
				self::$shipping_rate_id = $rate[0]->value;
			}

			$rs = $shippingMethod;

			$rateData .= $templateMiddle;
			$this->replacements['{shipping_method_title}'] = JText::_($rs->name);
			$this->replacements['{shipping_rate_loop_start}'] = '';
			$this->replacements['{shipping_rate_loop_end}'] = '';

			$rateData = $this->strReplace($this->replacements, $rateData);

			if ($templateRateMiddle != "")
			{
				$data = "";

				for ($i = 0, $in = count($rate); $i < $in; $i++)
				{
					$data .= $this->replaceShippingRate($templateRateMiddle, $rate[$i], $shippingMethod, $i);
				}

				return str_replace($templateRateMiddle, $data, $rateData);
			}
		}

		return $rateData;
	}

	/**
	 * Replace shipping rate
	 *
	 * @param   string   $templateRateMiddle
	 * @param   object   $rate
	 * @param   object   $shippingMethod
	 * @param   integer  $index
	 *
	 * @return  string|boolean
	 *
	 * @since   3.0
	 */
	public function replaceShippingRate($templateRateMiddle, $rate, $shippingMethod, $index)
	{
		if (isset($rate->shipping_rate_state) && !empty($rate->shipping_rate_state))
		{
			if (Redshop\Cart\Cart::isDiffCountryState($rate, $this->data['users_info_id'], $_POST))
			{
				return false;
			}
		}

		$this->replacements = array();
		$checked            = '';
		$data               = $templateRateMiddle;
		$mainLocation       = "";
		$displayRate        = (trim($rate->rate) > 0) ? " (" . RedshopHelperProductPrice::formattedPrice((double) trim($rate->rate)) . " )" : "";

		if ((isset($rate->checked) && $rate->checked) || $this->rateExist == 0)
		{
			$checked = "checked";
		}

		if ($checked == "checked")
		{
			self::$shipping_rate_id = $rate->value;
		}

		$className = $shippingMethod->element;

		$shippingRateName = RedshopLayoutHelper::render(
			'tags.shipping_method.shipping_rate_name',
			array(
				'shippingMethod' => $shippingMethod,
				'index' => $index,
				'checked' => $checked,
				'rateText' => html_entity_decode($rate->text),
				'className' => $className,
				'rate' => $rate
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$shippingRateShortDesc = '';

		if (isset($rate->shortdesc) === true)
		{
			$shippingRateShortDesc = html_entity_decode($rate->shortdesc);
		}

		$shippingRateDesc = '';

		if (isset($rate->longdesc) === true)
		{
			$shippingRateDesc = html_entity_decode($rate->longdesc);
		}

		$this->rateExist++;
		$this->replacements['{shipping_rate_name}'] = $shippingRateName;
		$this->replacements['{shipping_rate_short_desc}'] = $shippingRateShortDesc;
		$this->replacements['{shipping_rate_desc}'] = $shippingRateDesc;
		$this->replacements['{shipping_rate}'] = $displayRate;

		if (strpos($data, "{shipping_location}") !== false)
		{
			$shippingLocation = RedshopHelperOrder::getShippingLocationInfo($rate->text);

			for ($k = 0, $kn = count($shippingLocation); $k < $kn; $k++)
			{
				if ($shippingLocation[$k] != '')
				{
					$mainLocation = $shippingLocation[$k]->shipping_location_info;
				}
			}

			$this->replacements['{shipping_location}'] = $mainLocation;
		}

		$this->dispatcher->trigger('onReplaceShippingTemplate', array($this->data, &$data, $className, $checked));

		$this->replacements['{gls_shipping_location}'] = '';

		return $this->strReplace($this->replacements, $data);
	}

	/**
	 * Replace extra field
	 *
	 * @param   string   $template
	 * @param   object   $shippingMethod
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function replaceExtraField(&$template, $shippingMethod)
	{
		if ($this->isTagExists('{shipping_extrafields}'))
		{
			$paymentParamsNew  = new JRegistry($shippingMethod->params);
			$extraFieldPayment = $paymentParamsNew->get('extrafield_shipping');
			$extraFieldHidden  = "";
			$extraFieldTotal   = "";

			if (!empty($extraFieldPayment))
			{
				$countExtrafield = count($extraFieldPayment);

				for ($ui = 0; $ui < $countExtrafield; $ui++)
				{
					$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($extraFieldPayment[$ui], 19, '', 0, 0, 0);
					$extraFieldTotal .= $productUserFields[0] . " " . $productUserFields[1] . "<br>";

					// @Todo check later
					$extraFieldHidden .= RedshopLayoutHelper::render(
						'tags.common.input',
						array(
							'name' => 'extrafields[]',
							'type' => 'hidden',
							'value' => $extraFieldPayment[$ui]
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				}

				$shippingExtraField = RedshopLayoutHelper::render(
					'tags.shipping_method.shipping_extrafield',
					array('extraFieldTotal' => $extraFieldTotal),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$template = str_replace("{shipping_extrafields}", $shippingExtraField, $template);
			}
			else
			{
				$template = str_replace("{shipping_extrafields}", "", $template);
			}
		}
	}
}