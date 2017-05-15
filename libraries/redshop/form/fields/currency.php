<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

use Redshop\Currency\Currency;
use Redshop\Currency\CurrencyLayer;

/**
 * Renders a Currency Form
 *
 * @package       Joomla
 * @subpackage    Banners
 * @since         1.5
 */
class JFormFieldCurrency extends JFormField
{
	/**
	 * Element name
	 *
	 * @var  string
	 */
	public $type = 'currency';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$currency = array();

		if (Redshop::getConfig()->get('CURRENCY_LIBRARIES') == 1)
		{
			$convertedCurrencies = CurrencyLayer::getInstance()->getConvertedCurrencies();
		}
		else
		{
			$convertedCurrencies = Currency::getInstance()->getConvertedCurrencies();
		}

		if (!empty($convertedCurrencies))
		{
			foreach ($convertedCurrencies as $key => $val)
			{
				$currency[] = $key;
			}

			$currency = '\'' . implode("','", $currency) . '\'';
		}

		$shopCurrency = $this->getCurrency($currency);
		$ctrl         = $this->name;

		// Construct the various argument calls that are supported.
		$attributes = ' ';

		if ($v = $this->element['size'])
		{
			$attributes .= 'size="' . $v . '"';
		}

		if ($v = $this->element['class'])
		{
			$attributes .= 'class="' . $v . '"';
		}
		else
		{
			$attributes .= 'class="form-control inputbox"';
		}

		if ($this->element['multiple'])
		{
			$attributes .= ' multiple="multiple"';
		}

		return JHtml::_('select.genericlist', $shopCurrency, $ctrl, $attributes, 'value', 'text', $this->value, $this->id);
	}

	/**
	 * Get Shop Currency Support
	 *
	 * @param   string $currency Comma separated countries
	 *
	 * @return  array              Array for Shop country
	 *
	 */
	protected function getCurrency($currency = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('currency_code', 'value'))
			->select($db->qn('currency_name', 'text'))
			->from($db->qn('#__redshop_currency'))
			->order($db->qn('currency_name') . ' ASC');

		if (!empty($currency))
		{
			$query->where($db->qn('currency_code') . ' IN (' . $currency . ')');
		}

		return $db->setQuery($query)->loadObjectList();
	}
}
