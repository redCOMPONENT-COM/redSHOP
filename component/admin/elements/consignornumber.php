<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
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
		$db = JFactory::getDbo();

		$query = 'SELECT s.* FROM #__extensions AS s '
			. 'WHERE s.type="plugin" and s.folder="redshop_shipping" and enabled =1';

		$db->setQuery($query);
		$options = $db->loadObjectList();

		$html = '<table border="0">';

		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			$row = $options[$i];
			$value = 0;

			if (isset($this->value[$i]))
			{
				$value = $this->value[$i];
			}

			$html .= "<tr><td>" . $row->name . "</td><td><input type='text' id='" . $row->type . '-' . $row->name . "' name='" . $this->name . "[]'  value='" . $value . "'   /></td></tr>";
		}

		$html .= '</table>';

		return $html;
	}
}
