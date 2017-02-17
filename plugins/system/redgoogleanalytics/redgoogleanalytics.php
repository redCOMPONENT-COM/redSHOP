<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * redSHOP google Analytics System Plugin
 *
 * @since  1.0
 */
class PlgSystemRedGoogleAnalytics extends JPlugin
{
	/**
	 * This event is triggered after the framework has loaded and initialised and the router has route the client request.
	 * Routing is the process of examining the request environment to determine which component should receive the request.
	 * The component optional parameters are then set in the request object to be processed when the application is being dispatched.
	 * When this event triggers the router has parsed the route and pushed the request parameters into JRequest for retrieval by the application.
	 *
	 * @return  void
	 */
	public function onAfterRoute()
	{
		$app = JFactory::getApplication();

		if ($app->isSite() && Redshop::getConfig()->isExists())
		{
			$googleFile = JPATH_SITE . '/libraries/redshop/helper/googleanalytics.php';

			if (file_exists($googleFile))
			{
				if ("" != Redshop::getConfig()->get('GOOGLE_ANA_TRACKER_KEY')
					&& 'final' != $app->input->getWord('format', '')
					&& 'receipt' != $app->input->getWord('layout', ''))
				{
					require_once $googleFile;

					$ga = new RedshopHelperGoogleanalytics;
					$ga->placeTrans();
				}
			}
		}
	}
}
