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

defined( '_JEXEC' ) or die( 'Restricted access' );


jimport( 'joomla.application.component.controller' );

class shippingcontroller extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}
	function display()
	{
		parent::display();
	}

	function importeconomic()
	{
		$db = JFactory::getDBO();
		#Add product to economic
		if(ECONOMIC_INTEGRATION == 1)
		{
			$economic = new economic();

			$query = "SELECT s.*, r.* FROM #__redshop_shipping_rate r "
					."LEFT JOIN #__plugins s ON r.shipping_class = s.element "
					."WHERE s.published=1 and s.folder='redshop_shipping'"
//					."AND r.apply_vat=1"
					;
		    $db->setQuery( $query );
			$shipping =  $db->loadObjectList();

			for($i=0;$i<count($shipping);$i++)
			{
				$shipping_nshortname = (strlen($shipping[$i]->name)>15) ? substr($shipping[$i]->name,0,15) : $shipping[$i]->name;
				$shipping_number = $shipping_nshortname.' '.$shipping[$i]->shipping_rate_id;
				$shipping_name = $shipping[$i]->shipping_rate_name;
				$shipping_rate = $shipping[$i]->shipping_rate_value;

				$ecoShippingrateNumber = $economic->createShippingRateInEconomic($shipping_number,$shipping_name,$shipping_rate,$shipping[$i]->apply_vat);
			}
		}
		$msg = JText::_("IMPORT_RATES_TO_ECONOMIC_SUCCESS");
		$this->setRedirect( 'index.php?option=com_redshop&view=shipping',$msg );
	}
}	?>