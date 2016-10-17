<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Access manager detail model
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model.Accessmanager
 * @since       2.0
 */
class RedshopModelAccessmanager extends RedshopModel
{

	/**
	 * Get access manager list
	 *
	 * @return  array|bool
	 */
	public function getAccessmanager()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->quoteName('#__redshop_accessmanager'))
			->where($db->quoteName('section_name') . ' = ' . $db->quote(JFactory::getApplication()->input->get('section')));

		$db->setQuery($query);
		$this->data = $db->loadObjectList();

		return $this->data;
	}

	/**
	 * Method to store the information
	 *
	 * @param   array  $data  Data to store
	 *
	 * @return  JTable|stdClass|bool
	 */
	public function store($data)
	{
		$db = JFactory::getDbo();

		/**
		 * get groups
		 */
		$group = $this->getGroup();

		/**
		 * format groups
		 */
		$groups = $this->formatGroup($group);

		unset($groups ['30']);
		unset($groups ['29']);

		if ($this->checksection($data['section']) == 0)
		{
			if (count($groups))
			{
				foreach ($groups as $groupValue => $groupName)
				{
					$row               = $this->getTable();
					$row->gid          = $groupValue;
					$row->section_name = $data['section'];
					$row->view         = $data['groupaccess_' . $groupValue]['view'];
					$row->add          = $data['groupaccess_' . $groupValue]['add'];
					$row->edit         = $data['groupaccess_' . $groupValue]['edit'];
					$row->delete       = $data['groupaccess_' . $groupValue]['delete'];

					if ($row->check())
					{
						if (!$row->store())
						{
							$this->setError($db->getErrorMsg());

							return false;
						}
					}
					else
					{
						$this->setError($db->getErrorMsg());

						return false;
					}

					// Added for stock room
					if ($row->section_name == 'stockroom')
					{
						$row1               = $this->getTable();
						$row1->gid          = $groupValue;
						$row1->section_name = "stockroom_detail";
						$row1->view         = $data['groupaccess_' . $groupValue]['view'];
						$row1->add          = $data['groupaccess_' . $groupValue]['add'];
						$row1->edit         = $data['groupaccess_' . $groupValue]['edit'];
						$row1->delete       = $data['groupaccess_' . $groupValue]['delete'];

						if ($row->view == 1 && $row->add == 1)
						{
							if ($row1->check())
							{
								if (!$row1->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}
						else
						{
							$row1->view = null;

							if ($row1->check())
							{
								if (!$row1->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}

						$row_amt               = $this->getTable();
						$row_amt->gid          = $groupValue;
						$row_amt->section_name = 'stockroom_listing';
						$row_amt->view         = $data['groupaccess_' . $groupValue]['view'];
						$row_amt->add          = $data['groupaccess_' . $groupValue]['add'];
						$row_amt->edit         = $data['groupaccess_' . $groupValue]['edit'];
						$row_amt->delete       = $data['groupaccess_' . $groupValue]['delete'];

						if ($row->view == 1 && $row->edit == 1)
						{
							if ($row_amt->check())
							{
								if (!$row_amt->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}
						else
						{
							$row_amt->view = null;

							if ($row_amt->check())
							{
								if (!$row_amt->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}

						// Stockrrom image

						$row_img               = $this->getTable();
						$row_img->gid          = $groupValue;
						$row_img->section_name = "stockimage";
						$row_img->view         = $data['groupaccess_' . $groupValue]['view'];
						$row_img->add          = $data['groupaccess_' . $groupValue]['add'];
						$row_img->edit         = $data['groupaccess_' . $groupValue]['edit'];
						$row_img->delete       = $data['groupaccess_' . $groupValue]['delete'];

						if ($row->view == 1 && $row->edit == 1)
						{
							if ($row_img->check())
							{
								if (!$row_img->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}
						else
						{
							$row_img->view = null;
							$row_img->add  = null;

							if ($row_img->check())
							{
								if (!$row_img->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}

						$row_imgd               = $this->getTable();
						$row_imgd->gid          = $groupValue;
						$row_imgd->section_name = "stockimage_detail";
						$row_imgd->view         = $data['groupaccess_' . $groupValue]['view'];
						$row_imgd->add          = $data['groupaccess_' . $groupValue]['add'];
						$row_imgd->edit         = $data['groupaccess_' . $groupValue]['edit'];
						$row_imgd->delete       = $data['groupaccess_' . $groupValue]['delete'];

						if ($row_img->view == 1 && $row_img->add == 1)
						{
							if ($row_imgd->check())
							{
								if (!$row_imgd->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}
						else
						{
							$row_imgd->view = null;
							$row_imgd->add  = null;

							if ($row_imgd->check())
							{
								if (!$row_imgd->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}
					}
				}
			}
		}
		else
		{
			foreach ($groups as $groupValue => $groupName)
			{
				$row               = new stdClass;
				$row->gid          = $groupValue;
				$row->section_name = $data['section'];
				$row->view         = $data['groupaccess_' . $groupValue]['view'];
				$row->add          = $data['groupaccess_' . $groupValue]['add'];
				$row->edit         = $data['groupaccess_' . $groupValue]['edit'];
				$row->delete       = $data['groupaccess_' . $groupValue]['delete'];

				if ($row->section_name == 'stockroom')
				{
					$child_section = "stockroom_detail";

					if ($row->view == 1 && $row->add == 1)
					{
						$return = $this->_updateBySectionName($row->view, $row->add, $row->edit, $row->delete, $child_section, $row->gid);
					}
					else
					{
						$return = $this->_updateBySectionName(null, $row->add, $row->edit, $row->delete, $child_section, $row->gid);
					}

					$child_section1 = "stockroom_listing";

					if ($row->view == 1 && $row->edit == 1)
					{
						$return = $this->_updateBySectionName($row->view, $row->add, $row->edit, $row->delete, $child_section1, $row->gid);
					}
					else
					{
						$return = $this->_updateBySectionName(null, $row->add, $row->edit, $row->delete, $child_section1, $row->gid);
					}

					$child_section2 = "stockimage";

					if ($row->view == 1 && $row->edit == 1)
					{
						$return = $this->_updateBySectionName($row->view, $row->add, $row->edit, $row->delete, $child_section2, $row->gid);
					}
					else
					{
						$return = $this->_updateBySectionName(null, null, $row->edit, $row->delete, $child_section2, $row->gid);
					}

					$child_section3 = "stockimage_detail";

					if ($row->view == 1 && $row->edit == 1)
					{
						$return = $this->_updateBySectionName($row->view, $row->add, $row->edit, $row->delete, $child_section3, $row->gid);
					}
					else
					{
						$return = $this->_updateBySectionName(null, $row->add, $row->edit, $row->delete, $child_section3, $row->gid);
					}
				}

				$return = $this->_updateBySectionName($row->view, $row->add, $row->edit, $row->delete, $row->section_name, $row->gid);
			}
		}

		return $row;
	}

	/**
	 * Update record
	 *
	 * @param   int     $view     View
	 * @param   int     $add      Add
	 * @param   int     $edit     Edit
	 * @param   int     $delete   Delete
	 * @param   string  $section  Section name
	 * @param   int     $gid      Gid
	 *
	 * @return mixed
	 */
	protected function _updateBySectionName ($view, $add, $edit, $delete, $section, $gid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$fields = array (
			$db->quoteName('view') . ' = ' . (int) $view,
			$db->quoteName('add') . ' = ' . (int) $add,
			$db->quoteName('edit') . ' = ' . (int) $edit,
			$db->quoteName('delete') . ' = ' . (int) $delete,
		);

		$conditions = array (
			$db->quoteName('section_name') . ' = ' . $db->quote($section),
			$db->quoteName('gid') . ' = ' . (int) $gid,
		);

		$query->update($db->quoteName('#__redshop_accessmanager'))->set($fields)->where($conditions);
		$db->setQuery($query);

		return $db->execute();
	}

	/**
	 * Method to get section
	 *
	 * @param   string  $section  Section name
	 *
	 * @return  mixed
	 */
	public function checkSection($section)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_accessmanager'))
			->where($db->quoteName('section_name') . ' = ', $db->quote($section));
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Get group
	 *
	 * @return  mixed|null
	 */
	public function getGroup()
	{
		// Compute usergroups
		$db    = JFactory::getDbo();
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

		return $groups;
	}

	/**
	 * Format group
	 *
	 * @param   array  $groups  Group list
	 *
	 * @return array
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
	 * Proxy method to get model
	 *
	 * @param   string  $name     Model name
	 * @param   string  $prefix   Classname prefix
	 * @param   array   $options  Configuration
	 *
	 * @return JTable
	 */
	public function getTable($name = 'Accessmanager', $prefix = 'RedshopTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}
