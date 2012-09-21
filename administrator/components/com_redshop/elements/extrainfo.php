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

class JFormFieldextrainfo extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */

	public $type =  'extrainfo';

	protected function getInput()
	{
		$db = JFactory::getDBO();

		$html='';
		$html.="<textarea  name='".$this->name."'[]'  id='".$this->name."'[]' rows='8' cols='20'>".$this->value."</textarea>";

		return $html;
	}
}
