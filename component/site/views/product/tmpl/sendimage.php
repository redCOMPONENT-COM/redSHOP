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

$sentimage 	= JRequest::getVar ( 'sentimage' );
$pid		= JRequest::getVar ( 'pid' );
$imagename 	= JRequest::getVar ( 'imageName' );
$url		= JURI::base();

if(!file_exists(JPATH_COMPONENT_SITE.DS."assets/images/mergeImages/".$imagename))
{
	$producthelper = new producthelper ();
	$product 	   = $producthelper->getProductById($pid);
	
	$imagename = $product->product_full_image;
	if(!$imagename)
	{
		$imagename = $product->product_thumb_image;;
	}
	
	$img = $url."components/com_redshop/assets/images/product/".$imagename;
	
}else{
	$img = $url."components/com_redshop/assets/images/mergeImages/".$imagename;
}

if($sentimage)
{
?>
<form action='index.php' method="post" name="frmsend" >
<table>
<tr><td><?php echo JText::_('NAME');?></td><td><input type="text" name='friend_name' id='friend_name'/></td></tr>
<tr><td><?php echo JText::_('EMAIL');?></td><td><input type="text" name='sendmailto' id='sendmailto'/></td></tr>
</table>
<input type="submit" name="btmsubmit" value='submit'/>
<input type="hidden" name='option' value='com_redshop'/>
<input type="hidden" name="task" value='sendtomail'/>
<input type="hidden" name="view" value='product'/>
<input type="hidden" name="product_id" value='<?php echo $pid;?>'/>
<input type="hidden" name="imageName" value='<?php echo $imagename;?>'/>
</form>	
<?php 
}

$print = JRequest::getVar ( 'print' );
$showimage = JRequest::getVar ( 'showimage' );
$onclick = "onclick='window.print();'";

$print_tag = "<a ".$onclick." title='".JText::_('PRINT_LBL')."'>";
if($showimage )
	$print_tag .= "<img src='".$url."images/M_images/printButton.png' alt='".JText::_('PRINT_LBL')."' title='".JText::_('PRINT_LBL')."' />";
$print_tag .="<img src='".$img."' alt='".JText::_('CLICK_HERE')."' title='".JText::_('CLICK_HERE')."' />";
$print_tag .= "</a>";

echo $print_tag;


?>
