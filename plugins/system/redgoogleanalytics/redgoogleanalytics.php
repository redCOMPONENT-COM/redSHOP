<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * redSHOP google Analytics System Plugin
 *
 * @since  2.0
 */
class PlgSystemRedGoogleAnalytics extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

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

		if (!$app->isSite())
		{
			return;
		}

		$trackerKey = $this->params->get('tracker_key', '');

		if (!empty($trackerKey) && 'final' != $app->input->getWord('format', '') && 'receipt' != $app->input->getWord('layout', ''))
		{
			require_once __DIR__ . '/helper/google_analytics.php';

			$googleAnalyticsHelper = new RedSHOPGoogle_AnalyticsHelper($trackerKey);
			$googleAnalyticsHelper->placeTrans();
		}
	}
}
