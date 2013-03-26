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
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldconsignornumber extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */

	public $type = 'consignornumber';

	protected function getInput()
	{

		$db = JFactory::getDBO();

		$query = 'SELECT s.* FROM #__extensions AS s '
			. 'WHERE s.type="plugin" and s.folder="redshop_shipping" and enabled =1';

		$db->setQuery($query);
		$options = $db->loadObjectList();

		$html = '<table border="0">';
		$name = $this->name;
		$value = $this->value;

		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			$row = & $options[$i];


			$html .= "<tr><td>" . $row->name . "</td><td><input type='text' id='" . $row->id . "' name='" . $name . "[]'  value='" . $value[$i] . "'   /></td></tr>";
		}

		$html .= '</table>';
		return $html;
	}
}
