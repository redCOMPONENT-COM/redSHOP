<?php
/**
* @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
* @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
* Developed by email@recomponent.com - redCOMPONENT.com
*
* redSHOP can be downloaded from www.redcomponent.com
* redSHOP is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License 2
* as published by the Free Software Foundation.
*
* You should have received a copy of the GNU General Public License
* along with redSHOP; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

defined('_JEXEC') or die( 'Restricted access' );



/**
* Renders a Productfinder Form
*
* @package	 Joomla
* @subpackage	Banners
* @since	 1.5
*/
class JFormFieldcurrency extends JFormField
{
		/**
		* Element name
		*
		* @access	protected
		* @var	 string
		*/
		public $type = 'currency';


		protected function getInput()
		{

			require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'currency.php');

			// This might get a conflict with the dynamic translation - TODO: search for better solution
			$convertPrice = new convertPrice();

			$convertPrice->init();

			$currency = array();

			if (count($GLOBALS['converter_array'])>0){
				foreach ($GLOBALS['converter_array'] as $key=>$val){
				$currency[] = $key;
				}

				$currency = implode("','",$currency);
			}

			$shop_currency = $this->getCurrency($currency);
			$ctrl = $this->name;

			// Construct the various argument calls that are supported.
			$attribs = ' ';
			if ($v = $this->element['size' ]) {
				$attribs .= 'size="'.$v.'"';
			}
			if ($v = $this->element['class']) {
				$attribs .= 'class="'.$v.'"';
			} else {
				$attribs .= 'class="inputbox"';
			}
			if ($m = $this->element['multiple'])
			{
				$attribs .= ' multiple="multiple"';
				//$ctrl .= '[]';
			}


			return JHTML::_('select.genericlist', $shop_currency, $ctrl, $attribs, 'value', 'text', $this->value, $this->id );

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
		function getCurrency($currency="")
		{
			$db = &JFactory::getDBO();

			$where="";
			if ($currency){
				$where = " WHERE currency_code IN ('".$currency."')";
			}
			$query = 'SELECT currency_code as value, currency_name as text FROM #__redshop_currency'.$where.' ORDER BY currency_name ASC';
			$db->setQuery($query);
			return $db->loadObjectlist();
		}
}

