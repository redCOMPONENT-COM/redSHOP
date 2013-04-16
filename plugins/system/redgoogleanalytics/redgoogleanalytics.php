<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * redSHOP google Analytics System Plugin
 *
 * @author     Gunjan Patel
 */
class plgSystemredgoogleanalytics extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access    protected
	 *
	 * @param    object $subject The object to observe
	 * @param    array  $config  An array that holds the plugin configuration
	 */
	public function plgSystemredGoogle(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * This event is triggered after the framework has loaded and initialised and the router has route the client request.
	 * Routing is the process of examining the request environment to determine which component should receive the request.
	 * The component optional parameters are then set in the request object to be processed when the application is being dispatched.
	 * When this event triggers the router has parsed the route and pushed the request parameters into JRequest for retrieval by the application.
	 */
	public function onAfterRoute()
	{
		$configFile = JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';

		$googleFile = JPATH_SITE . '/components/com_redshop/helpers/google_analytics.php';

		$uri = JFactory::getURI();
		$requesturlBase = $uri->base();
		$view = JRequest::getVar('view');
		$format = JRequest::getWord('format', '');
		$layout = JRequest::getWord('layout', '');

		if (!strstr($requesturlBase, "administrator") && file_exists($configFile))
		{
			require_once $configFile;

			if (file_exists($googleFile))
			{
				if (GOOGLE_ANA_TRACKER_KEY != "" && $format != 'final' && $layout != 'receipt')
				{
					require_once $googleFile;
					$google_ana = new googleanalytics;
					$code = $google_ana->placeTrans();
				}
			}
		}
	}
}
