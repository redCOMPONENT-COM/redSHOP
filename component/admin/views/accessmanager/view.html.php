<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Access manager detail view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View.Accessmanager
 * @since       2.0
 */
class RedshopViewAccessmanager extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * Display
	 *
	 * @param   string  $tpl  Tpl
	 *
	 * @return  JViewLegacy
	 */
	public function display($tpl = null)
	{
		$model = $this->getModel();
		$accessmanager = $model->getAccessmanager();

		/**
		 * get groups
		 */
		$groups = $this->getGroup();

		$this->addToolbar();

		/**
		 * format groups
		 */
		$groups = $this->formatGroup($groups);

		$this->groups = $groups;

		$this->accessmanager = $accessmanager;

		parent::display($tpl);
	}

	/**
	 * Get group
	 *
	 * @return  array|bool
	 */
	public function getGroup()
	{
		// Compute usergroups
		$db = JFactory::getDbo();
		$query = "SELECT a.*,COUNT(DISTINCT c2.id) AS level
FROM `#__usergroups` AS a  LEFT  OUTER JOIN `#__usergroups` AS c2  ON a.lft > c2.lft  AND a.rgt < c2.rgt  GROUP BY a.id
  ORDER BY a.lft asc";

		$db->setQuery($query);

		$groups = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());

			return null;
		}

		return ($groups);
	}

	/**
	 * Format group
	 *
	 * @param   array  $groups  Array of group
	 *
	 * @return array
	 *
	 * @since version
	 */
	public function formatGroup($groups)
	{
		$returnable = array();

		foreach ($groups as $val)
		{
			$returnable[$val->id] = str_repeat('<span class="gi">|&mdash;</span>', $val->level) . $val->title;
		}

		return $returnable;
	}

	/**
	 * Method to add toolbar
	 *
	 * @return  void
	 */
	protected function addToolbar ()
	{
		JToolBarHelper::title(
			JText::_('COM_REDSHOP_ACCESS_MANAGER') . ': <small><small>[ ' . JFactory::getApplication()->input->get('section') . ' ]</small></small>',
			'redshop_catalogmanagement48'
		);

		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
	}

	/**
	 * Proxy to get RedshopModelAccessmanager
	 *
	 * @param   string  $name    Model name
	 * @param   string  $prefix  Model prefix
	 * @param   array   $config  Configuration
	 *
	 * @return  object
	 */
	public function getModel($name = 'Accessmanager', $prefix = 'RedshopModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}
