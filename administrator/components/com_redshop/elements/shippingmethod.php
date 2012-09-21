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
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('_JEXEC') or die( 'Restricted access' );
/**
 * Renders a Productfinder Form
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JFormFieldshippingmethod extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */

	public $type = 'shippingmethod';

	protected function getInput()
	{

		$db = JFactory::getDBO();

		$query = 'SELECT s.* FROM #__extensions AS s '
				.'WHERE s.type="plugin" and s.folder="redshop_shipping" and enabled =1';

		$db->setQuery($query);
		$options = $db->loadObjectList();

		$html='';
		$name		= $this->name;
		//$value	= $this->value;
		for ($i=0, $n=count( $options ); $i < $n; $i++)
		{
			$row = &$options[$i];


			$html.="&nbsp;<input type='hidden' id='".$row->id."' name='".$name."'  value='".$row->element."'   /><br/>";
		}


		return $html;
	}
}
