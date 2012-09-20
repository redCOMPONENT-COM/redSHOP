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
	defined ('_JEXEC') or die ('Restricted access');

	global $mainframe;
	$mainframe = JFactory::getApplication();
	jimport( 'joomla.html.parameter' );

    $option = JRequest::getCmd('option');
	$view = JRequest::getVar('view');
   	// Getting the configuration
   	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'helpers'.DS.'redshop.cfg.php');
    require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'helpers'.DS.'configuration.php');
    require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'helpers'.DS.'template.php');
    require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'helpers'.DS.'stockroom.php');
    require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'helpers'.DS.'economic.php');
    $Redconfiguration = new Redconfiguration();
   	$Redconfiguration->defineDynamicVars();

    jimport('joomla.html.pagination');

    require_once(JPATH_COMPONENT.DS.'helpers'.DS.'cron.php');
    require_once(JPATH_COMPONENT.DS.'helpers'.DS.'statistic.php');
    require_once(JPATH_COMPONENT.DS.'helpers'.DS.'pagination.php');
	require_once(JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');
	require_once(JPATH_COMPONENT.DS.'helpers'.DS.'product.php');
	require_once(JPATH_COMPONENT.DS.'helpers'.DS.'currency.php');

	// helper object
	$helper = new redhelper();

	# include redCRM if required
	$helper->isredCRM();

    $print = JRequest::getCmd('print');
    //Adding Redshop CSS
    $doc = & JFactory::getDocument ();
    // use diffrent CSS for print layout
    if(!$print)
    	JHTML::Stylesheet('redshop.css', 'components/com_redshop/assets/css/');
	else
	   JHTML::Stylesheet('print.css', 'components/com_redshop/assets/css/');

	    JHTML::Stylesheet('style.css', 'components/com_redshop/assets/css/');
	$Itemid = $helper->getCheckoutItemid();
	$Itemid = JRequest::getInt('Itemid',$Itemid);
	$Itemid = $helper->getCartItemid($Itemid);
	// include redshop js file.
	require_once(JPATH_COMPONENT.DS.'helpers'.DS.'redshop.js.php');

    $controller = JRequest::getCmd( 'view' ,'category');

    $task = JRequest::getCmd('task');
    $format = JRequest::getWord('format','');
    $layout = JRequest::getWord('layout','');

    $params = &$mainframe->getParams('com_redshop');

    // add product in cart from db
   	$helper->dbtocart();

    $categoryid = JRequest::getInt('cid',$params->get('categoryid'));
	$productid = JRequest::getInt('pid',0);

	$sgportal = $helper->getShopperGroupPortal();
	$portal = 0;
	if (count($sgportal)>0)
		$portal = $sgportal->shopper_group_portal;

	$user = JFactory::getUser();

	if ( $task != 'loadProducts'  && $task != "downloadProduct" && $task != "discountCalculator" && $task != "ajaxupload" && $task != 'getShippingrate' && $task !='addtocompare' && $task !='ajaxsearch' && $task != "Download" && $task!='addtowishlist' ){

		echo "<div id='redshopcomponent' class='redshop'>";

		if($format != 'final' && $layout != 'receipt'){

			/*
			 * get redSHOP Google Analytics Plugin is Enable?
			 * If it is Disable than load Google Analytics From redSHOP
			 */
			$isredGoogleAnalytics = JPluginHelper::isEnabled('system','redgoogleanalytics');

			if(!$isredGoogleAnalytics && GOOGLE_ANA_TRACKER_KEY != ""){

				require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'google_analytics.php');

				$google_ana = new googleanalytics ( );

				$anacode = $google_ana->placeTrans();
			}
		}
	}

    if(PORTAL_SHOP == 1)
    {
	    if ($controller == 'product' && $productid >0 && $user->id >0){

	    	$checkcid = $helper->getShopperGroupProductCategory($productid);

	    	if ($checkcid == true){
	    		$controller='login';
	    		JRequest::setVar('view','login' );
	    		JRequest::setVar('layout','portal' );
	    		$mainframe->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
	    	}
	    }else if ($controller == 'category' && $categoryid >0 && $user->id >0){

	    	$checkcid = $helper->getShopperGroupCategory($categoryid);

	    	if ($checkcid == ""){
	    		$controller='login';
	    		JRequest::setVar('view','login' );
	    		JRequest::setVar('layout','portal' );
	    		$mainframe->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
	    	}
	    }else {

	    	$controller='login';
    		JRequest::setVar('view','login' );
    		JRequest::setVar('layout','portal' );

	    }


    }else{

	    if ($controller == 'product' && $productid >0 && $portal == 1){

	    	$checkcid = $helper->getShopperGroupProductCategory($productid);

	    	if ($checkcid == true){
	    		$controller='login';
	    		JRequest::setVar('view','login' );
	    		JRequest::setVar('layout','portal' );
	    		$mainframe->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
	    	}
	    }

	    if ($controller == 'category' && $categoryid >0 && $portal == 1){

	    	$checkcid = $helper->getShopperGroupCategory($categoryid);

	    	if ($checkcid == ""){
	    		$controller='login';
	    		JRequest::setVar('view','login' );
	    		JRequest::setVar('layout','portal' );
	    		$mainframe->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
	    	}
	    }

    	if($controller=='redshop')
    	{
	    	$controller='category';
	    	JRequest::setVar('view','category' );
    	}
    }


    //set the controller page
    if(!file_exists(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php')){

    	$controller='category';
    	JRequest::setVar('view','category' );
    }

   	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');

	//set the controller page

    $classname  = $controller.'controller';

	//create a new class of classname and set the default task:display

    $controller = new $classname( array('default_task' => 'display') );

	// Perform the Request task

    $controller->execute( JRequest::getCmd('task' ));

	// Redirect if set by the controller
	echo "</div>";
    $controller->redirect();
