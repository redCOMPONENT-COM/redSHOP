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


// no direct access
defined('_JEXEC') or die("Direct Access Is Not Allowed");

/**
 * redSHOP google Analytics System Plugin
 *
 * @author     Gunjan Patel
 */
class plgSystemredgoogleanalytics extends JPlugin {

    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @access	protected
     * @param	object	$subject The object to observe
     * @param 	array   $config  An array that holds the plugin configuration
     */
    function plgSystemredGoogle(& $subject, $config) {
        parent::__construct($subject, $config);
    }

    /**
	 * This event is triggered after the framework has loaded and initialised and the router has route the client request.
	 * Routing is the process of examining the request environment to determine which component should receive the request.
	 * The component optional parameters are then set in the request object to be processed when the application is being dispatched.
	 * When this event triggers the router has parsed the route and pushed the request parameters into JRequest for retrieval by the application.
	 */
	function onAfterRoute() {

		$configFile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php';

		$googleFile = JPATH_SITE. DS. 'components' . DS. 'com_redshop' . DS . 'helpers' . DS . 'google_analytics.php';

		$uri 		=& JFactory::getURI();
		$requesturlBase = $uri->base();
		$view = JRequest::getVar('view');
		$format = JRequest::getWord('format','');
		$layout = JRequest::getWord('layout','');

		if(!strstr($requesturlBase,"administrator") && file_exists($configFile)){
           require_once ($configFile);

		   if(file_exists($googleFile)){

		   		if(GOOGLE_ANA_TRACKER_KEY != "" && $format != 'final' && $layout != 'receipt'){

					require_once ($googleFile);
					$google_ana = new googleanalytics();
					$code = $google_ana->placeTrans();
		   		}
			}
		}
	}

}