<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

/**
 * RedSHOP master display controller.
 *
 * @package  RedSHOP.Administrator
 * @since    1.3.3.1
 */
class RedshopController extends JControllerLegacy
{
	/**
	 * Method to show a newsfeeds view
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   boolean  $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @since    1.3.3.1
	 *
	 * @return  JController               This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$app = JFactory::getApplication();

		// Helper object
		$helper = new redhelper;

		// Include redCRM if required
		$helper->isredCRM();

		$print = JRequest::getCmd('print');

		// Adding Redshop CSS
		$doc = JFactory::getDocument();

		// Use diffrent CSS for print layout
		if (!$print)
		{
			JHTML::Stylesheet('redshop.css', 'components/com_redshop/assets/css/');
		}
		else
		{
			JHTML::Stylesheet('print.css', 'components/com_redshop/assets/css/');
		}

		JHTML::Stylesheet('style.css', 'components/com_redshop/assets/css/');

		// Set the default view name and format from the Request.
		$vName      = JRequest::getCmd('view', 'category');
		$task       = JRequest::getCmd('task');
		$format     = JRequest::getWord('format', '');
		$layout     = JRequest::getWord('layout', '');
		$params     = $app->getParams('com_redshop');
		$categoryid = JRequest::getInt('cid', $params->get('categoryid'));
		$productid  = JRequest::getInt('pid', 0);
		$sgportal   = $helper->getShopperGroupPortal();
		$user       = JFactory::getUser();
		$portal     = 0;

		// Add product in cart from db
		$helper->dbtocart();

		if (count($sgportal) > 0)
		{
			$portal = $sgportal->shopper_group_portal;
		}

		if ($task != 'loadProducts' && $task != "downloadProduct" && $task != "discountCalculator" && $task != "ajaxupload" && $task != 'getShippingrate' && $task != 'addtocompare' && $task != 'ajaxsearch' && $task != "Download" && $task != 'addtowishlist')
		{
			echo "<div id='redshopcomponent' class='redshop'>";

			if ($format != 'final' && $layout != 'receipt')
			{
				/*
				 * get redSHOP Google Analytics Plugin is Enable?
				 * If it is Disable than load Google Analytics From redSHOP
				 */
				$isredGoogleAnalytics = JPluginHelper::isEnabled('system', 'redgoogleanalytics');

				if (!$isredGoogleAnalytics && GOOGLE_ANA_TRACKER_KEY != "")
				{
					require_once JPATH_COMPONENT . '/helpers/google_analytics.php';

					$google_ana = new googleanalytics;

					$anacode = $google_ana->placeTrans();
				}
			}
		}

		if (PORTAL_SHOP == 1)
		{
			if ($vName == 'product' && $productid > 0 && $user->id > 0)
			{
				$checkcid = $helper->getShopperGroupProductCategory($productid);

				if ($checkcid == true)
				{
					$vName = 'login';
					JRequest::setVar('view', 'login');
					JRequest::setVar('layout', 'portal');
					$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
				}
			}
			elseif ($vName == 'category' && $categoryid > 0 && $user->id > 0)
			{
				$checkcid = $helper->getShopperGroupCategory($categoryid);

				if ($checkcid == "")
				{
					$vName = 'login';
					JRequest::setVar('view', 'login');
					JRequest::setVar('layout', 'portal');
					$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
				}
			}
			else
			{
				$vName = 'login';
				JRequest::setVar('view', 'login');
				JRequest::setVar('layout', 'portal');
			}
		}
		else
		{
			if ($vName == 'product' && $productid > 0 && $portal == 1)
			{
				$checkcid = $helper->getShopperGroupProductCategory($productid);

				if ($checkcid == true)
				{
					$vName = 'login';
					JRequest::setVar('view', 'login');
					JRequest::setVar('layout', 'portal');
					$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
				}
			}

			if ($vName == 'category' && $categoryid > 0 && $portal == 1)
			{
				$checkcid = $helper->getShopperGroupCategory($categoryid);

				if ($checkcid == "")
				{
					$vName = 'login';
					JRequest::setVar('view', 'login');
					JRequest::setVar('layout', 'portal');
					$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
				}
			}

			if ($vName == 'redshop')
			{
				$vName = 'category';
				JRequest::setVar('view', 'category');
			}
		}

		JRequest::setVar('view', $vName);

		parent::display($cachable, $urlparams);

		// End component DIV here
		echo "</div>";
	}
}
