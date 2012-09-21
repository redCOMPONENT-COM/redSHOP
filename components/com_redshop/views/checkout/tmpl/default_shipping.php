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
defined ('_JEXEC') or die ('restricted access');
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'shipping.php' );

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$redTemplate = new Redtemplate();
$carthelper 	= new rsCarthelper();

$post = JRequest::get('POST');
$user	=& JFactory::getUser();

$shippingbox_template = $redTemplate->getTemplate ("shipping_box" );
if(count($shippingbox_template)>0 && $shippingbox_template[0]->template_desc)
{
	$box_template_desc = $shippingbox_template[0]->template_desc;
} else {
	$box_template_desc = "<fieldset class=\"adminform\"> <legend><strong>{shipping_box_heading}</strong></legend>\r\n<div>{shipping_box_list}</div>\r\n</fieldset>";
}

$shipping_template = $redTemplate->getTemplate ("redshop_shipping" );
if(count($shipping_template)>0 && $shipping_template[0]->template_desc)
{
	$template_desc = $shipping_template[0]->template_desc;
} else {
	$template_desc = "<fieldset class=\"adminform\"><legend><strong>{shipping_heading}</strong></legend>\r\n<div>{shipping_method_loop_start}\r\n<h3>{shipping_method_title}</h3>\r\n<div>{shipping_rate_loop_start}\r\n<div>{shipping_rate_name} {shipping_rate}</div>\r\n{shipping_rate_loop_end}</div>\r\n{shipping_method_loop_end}</div>\r\n</fieldset>";
}

if($this->users_info_id > 0 )
{
	$shippinghelper 	= new shipping();	
	$shippingBoxes = $shippinghelper->getShippingBox();
	$selshipping_box_post_id = 0;
	if(count($shippingBoxes)>0)
	{
		$selshipping_box_post_id = $shippingBoxes[0]->shipping_box_id;
	}
	if(isset($post['shipping_box_id']))
	{
		$shipping_box_post_id = $post['shipping_box_id'];
	} else {
		$shipping_box_post_id = $selshipping_box_post_id;
	}
	$box_template_desc = $carthelper->replaceShippingBoxTemplate($box_template_desc,$shipping_box_post_id);
	echo eval("?>".$box_template_desc."<?php ");


	$returnarr = $carthelper->replaceShippingTemplate($template_desc,$this->shipping_rate_id,$shipping_box_post_id,$user->id,$this->users_info_id,$this->ordertotal,$this->order_subtotal);
	$template_desc = $returnarr['template_desc'];
	$this->shipping_rate_id = $returnarr['shipping_rate_id'];

	echo eval("?>".$template_desc."<?php ");
	/*$database =  jFactory::getDBO();
	$sql = "SELECT  enabled FROM #__extensions WHERE element ='default_shipping_GLS'" ;
	$database->setQuery($sql);
   	$isEnabled = $database->loadResult();

	if($isEnabled)
	{
	
		JPluginHelper::importPlugin('rs_labels_GLS');
		$dispatcher 				=& JDispatcher::getInstance();
		$sql = "SELECT  * FROM #__".TABLE_PREFIX."_users_info WHERE users_info_id='" . $this->users_info_id . "'" ;
		$database->setQuery($sql);
	   	$values = $database->loadObject();


		$ShopResponses				= $dispatcher->trigger('GetNearstParcelShops',array( $values));
		$ShopRespons				= $ShopResponses[0];

		if(count($ShopRespons)>0)
			$output						= '<fieldset><legend><b>'.JText::_( 'PACKAGE_COLLETCTION_SHOP' ).'</b></legend><table>';

		for($i=0;$i<count($ShopRespons);$i++)
		{
			if($i<= (count($ShopRespons)-1)){
				$checked = 'checked="checked"';
			}
			$output .= "<tr><td><input type='radio' $checked id='shop_id_". $ShopRespons[$i]->Number."' name='shop_id' value='". $ShopRespons[$i]->shop_id ."' />"  ;
			$output .= $ShopRespons[$i]->CompanyName . ", " . $ShopRespons[$i]->Streetname.", ". $ShopRespons[$i]->ZipCode . ", ". $ShopRespons[$i]->CityName . "</td></tr>";
			$output .= "<tr><td>".$ShopRespons[$i]->openingTime. "</td></tr>";

		}

		if(count($ShopRespons)>0)
		{
			$output						.= '</table></fieldset>';
			echo   $output;
		}
	}*/
}
else
{	?>
	<div class="shipnotice"><?php echo JText::_('COM_REDSHOP_FILL_SHIPPING_ADDRESS' ); ?></div>
<?php
}	?>