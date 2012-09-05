<?php
defined ('_JEXEC') or die ('restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$uri =& JURI::getInstance();
$url= $uri->root();

$user =& JFactory::getUser();

//$option = JRequest::getVar('option');
//$Itemid = JRequest::getVar('Itemid');
$redhelper = new redhelper();
$Itemid = $redhelper->getItemid();
// get product helper
// Getting the configuration
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php' );
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();

if(MY_WISHLIST)
{
	if(!$user->id)
	{
		echo "<div class='mod_redshop_wishlist'>";
		if( count($rows) > 0 )
		{
			$mywishlist_link = JRoute::_ ('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid='.$Itemid) ;
			echo "<a href=\"".$mywishlist_link."\" >".JText::_('VIEW_WISHLIST')."</a>";
			// send mail link
		}
		else
		{
			echo "<div>".JText::_('NO_PRODUCTS_IN_WISHLIST')."</div>";
		}
		echo "</div>";
	}
	else	// if user logged in than display this code.
	{
		echo "<div class='mod_redshop_wishlist'>";
		if( (count($wishlists) > 0) || ( count($rows) > 0)  )
		{
			$mywishlist_link = JRoute::_ ( 'index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid='.$Itemid);
			echo  "<a href=\"".$mywishlist_link."\" >".JText::_('VIEW_WISHLIST')."</a>";
		}
		else
		{
			echo "<div>".JText::_('NO_PRODUCTS_IN_WISHLIST')."</div>";
		}
		echo "</div>";


	}
}
else {
	echo "<div>".JText::_('NO_PRODUCTS_IN_WISHLIST')."</div>";
}?>