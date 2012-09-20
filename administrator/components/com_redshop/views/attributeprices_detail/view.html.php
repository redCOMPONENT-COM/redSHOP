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
jimport( 'joomla.application.component.view' );

class attributeprices_detailVIEWattributeprices_detail extends JView
{
	function display($tpl = null)
	{
	 	$uri =& JFactory::getURI();
	 	
		$lists = array();
		$detail	=& $this->get('data'); 

		$model = $this->getModel('attributeprices_detail');
		$property = $model->getPropertyName();
		$shoppergroup = $model->getShopperGroup();
		$lists['shopper_group_name'] = JHTML::_('select.genericlist',$shoppergroup,'shopper_group_id','class="inputbox" size="1"','value','text',$detail->shopper_group_id);
	 
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('property',		$property);
		$this->assignRef('request_url',	$uri->toString());
		parent::display($tpl);
	}
}?>