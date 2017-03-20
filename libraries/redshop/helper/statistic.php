<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Statistic
 *
 * @since  2.0.3
 */
abstract class RedshopHelperStatistic
{
	/**
	 * Method for store view of page.
	 *
	 * @return  bool  True on success. False otherwise.
	 *
	 * @since  2.0.3
	 */
	public static function recordPage()
	{
		$input     = JFactory::getApplication()->input;
		$sessionId = JFactory::getSession()->getId();
		$user      = JFactory::getUser();

		$view      = $input->getCmd('view', '');
		$section   = '';
		$sectionId = 0;

		switch ($view)
		{
			case 'product':
				$section   = $view;
				$sectionId = $input->getInt('pid', 0);
				break;

			case 'category':
				$section   = $view;
				$sectionId = $input->getInt('cid', 0);
				break;

			case 'manufacturers':
				$section   = $view;
				$sectionId = $input->getInt('mid', 0);
				break;

			default:
				break;
		}

		if (empty($section))
		{
			return false;
		}

		$table = RedshopTable::getInstance('Page_Viewer', 'RedshopTable');

		if ($table->load(array('session_id' => $sessionId, 'section' => $section, 'section_id' => $sectionId)))
		{
			return true;
		}

		$table->id = null;
		$table->session_id   = $sessionId;
		$table->user_id      = $user->id;
		$table->section      = $section;
		$table->section_id   = $sectionId;
		$table->created_date = JFactory::getDate()->toUnix();

		return $table->store();
	}

	/**
	 * Method for store visitor.
	 *
	 * @return  bool  True on success. False otherwise.
	 *
	 * @since  2.0.3
	 */
	public static function recordVisitor()
	{
		$sessionId = JFactory::getSession()->getId();
		$user      = JFactory::getUser();

		$table = RedshopTable::getInstance('Site_Viewer', 'RedshopTable');

		if ($table->load(array('session_id' => $sessionId)))
		{
			return true;
		}

		$table->id = null;
		$table->user_id = $user->id;
		$table->session_id = $sessionId;
		$table->created_date = JFactory::getDate()->toUnix();

		return $table->store();
	}
}
