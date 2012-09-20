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

class JFormFieldcreditcards extends JFormField
{	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	public $type =  'creditcards';

	protected function getInput()
	{
		$db = &JFactory::getDBO();

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$cc_list = array();
		$cc_list['VISA'] = 'Visa';
		$cc_list['MC'] = 'MasterCard';
		$cc_list['amex'] = 'American Express';
		$cc_list['maestro'] = 'Maestro';
		$cc_list['jcb'] = 'JCB';
		$cc_list['diners'] = 'Diners Club';
		$cc_list['discover'] = 'Discover';

		//$selected_cc = explode(",",$this->detail->accepted_credict_card);

		$html='';
		foreach($cc_list as $key => $valuechk)
		{
			$checked='';
			if(count($this->value)> 1){
				$checked = in_array($key,$this->value) ? "checked=\"checked\"" : "";
			}else if ($this->value != ""){
				$checked = ($key==$this->value) ? "checked=\"checked\"" : "";
			}


			$html.="<label><input type='checkbox' id='".$key."'[]' name='".$this->name."[]'  value='".$key."' ".$checked."  />".$valuechk."&nbsp;<br /></label>";
		}


		return $html;
	}
}
?>
