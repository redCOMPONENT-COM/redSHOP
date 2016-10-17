<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewAccessmanager extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$model = $this->getModel();
		$accessmanager = $model->getaccessmanager();

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

	public function formatGroup($groups)
	{
		$returnable = array();

		foreach ($groups as $val)
		{
			$returnable[$val->id] = str_repeat('<span class="gi">|&mdash;</span>', $val->level) . $val->title;
		}

		return $returnable;
	}

	protected function addToolbar ()
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_ACCESS_MANAGER') . ': <small><small>[ ' . JRequest::getVar('section') . ' ]</small></small>',
			'redshop_catalogmanagement48'
		);

		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();

	}

	public function getModel($name = 'Accessmanager', $prefix = 'RedshopModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}
