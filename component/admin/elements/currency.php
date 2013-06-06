<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


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
	 * Element name
	 *
	 * @access    protected
	 * @var     string
	 */
	public $type = 'currency';


	protected function getInput()
	{

		require_once JPATH_SITE . '/components/com_redshop/helpers/currency.php';

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$CurrencyHelper = new CurrencyHelper();

		$CurrencyHelper->init();

		$currency = array();

		if (count($GLOBALS['converter_array']) > 0)
		{
			foreach ($GLOBALS['converter_array'] as $key => $val)
			{
				$currency[] = $key;
			}

			$currency = implode("','", $currency);
		}

		$shop_currency = $this->getCurrency($currency);
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
		if ($m = $this->element['multiple'])
		{
			$attribs .= ' multiple="multiple"';
			//$ctrl .= '[]';
		}


		return JHTML::_('select.genericlist', $shop_currency, $ctrl, $attribs, 'value', 'text', $this->value, $this->id);

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
		$db = JFactory::getDBO();

		$where = "";
		if ($currency)
		{
			$where = " WHERE currency_code IN ('" . $currency . "')";
		}
		$query = 'SELECT currency_code as value, currency_name as text FROM #__redshop_currency' . $where . ' ORDER BY currency_name ASC';
		$db->setQuery($query);
		return $db->loadObjectlist();
	}
}

