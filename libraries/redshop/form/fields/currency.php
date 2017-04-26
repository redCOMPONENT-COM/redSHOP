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

/**
 * Renders a Productfinder Form
 *
 * @package       Joomla
 * @subpackage    Banners
 * @since         1.5
 */
class JFormFieldcurrency extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'currency';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		// This might get a conflict with the dynamic translation - TODO: search for better solution
		CurrencyHelper::getInstance()->init();
		$currency = array();

		if (count($GLOBALS['converter_array']) > 0)
		{
			foreach ($GLOBALS['converter_array'] as $key => $val)
			{
				$currency[] = $key;
			}

			$currency = implode("','", $currency);
		}

		$shopCurrency = $this->getCurrency($currency);
		$ctrl = $this->name;

		// Construct the various argument calls that are supported.
		$attribs = ' ';

		if ($v = $this->element['size'])
		{
			$attribs .= 'size="' . $v . '"';
		}

		if ($v = $this->element['class'])
		{
			$attribs .= 'class="' . $v . '"';
		}
		else
		{
			$attribs .= 'class="inputbox"';
		}

		if ($this->element['multiple'])
		{
			$attribs .= ' multiple="multiple"';
		}

		return JHTML::_('select.genericlist', $shopCurrency, $ctrl, $attribs, 'value', 'text', $this->value, $this->id);
	}

	/*
	* get Shop Currency Support
	*
	* @params: string $currency comma separated countries
	* @return: array stdClass Array for Shop country
	*
	* currency_code as value
	* currency_name as text
	*/
	function getCurrency($currency = "")
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('currency_code', 'value'))
			->select($db->qn('currency_name', 'text'))
			->from($db->qn('#__redshop_currency'))
			->where($db->qn('currency_code') . ' IN (' . $currency . ')')
			->order($db->qn('currency_name'));

		return $db->setQuery($query)->loadObjectlist();
	}
}
