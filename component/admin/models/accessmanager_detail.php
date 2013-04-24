<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';

class accessmanager_detailModelaccessmanager_detail extends JModel
{
	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function getaccessmanager()
	{
		$section = JRequest::getVar('section');
		$query = "SELECT a.* FROM " . $this->_table_prefix . "accessmanager AS a "
			. "WHERE a.section_name='" . $section . "'";
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObjectList();

		return $this->_data;
	}

	/**
	 * Method to store the information
	 *
	 * @return boolean
	 */
	public function store($data)
	{
		/**
		 * get groups
		 */
		$group = $this->getGroup();

		/**
		 * format groups
		 */
		$groups = $this->formatGroup($group);
		$check_section = $this->checksection($data['section']);

		unset($groups ['30']);
		unset($groups ['29']);

		if ($check_section == 0)
		{
			if (count($groups))
			{
				foreach ($groups as $groupValue => $groupName)
				{
					$row = $this->getTable('accessmanager_detail');
					$row->gid = $groupValue;
					$row->section_name = $data['section'];
					$row->view = $data['groupaccess_' . $groupValue]['view'];
					$row->add = $data['groupaccess_' . $groupValue]['add'];
					$row->edit = $data['groupaccess_' . $groupValue]['edit'];
					$row->delete = $data['groupaccess_' . $groupValue]['delete'];

					if ($row->check())
					{
						if (!$row->store())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}
					else
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}

					// Added for stock room
					if ($row->section_name == 'stockroom')
					{
						$row1 =& $this->getTable('accessmanager_detail');
						$row1->gid = $groupValue;
						$row1->section_name = "stockroom_detail";
						$row1->view = $data['groupaccess_' . $groupValue]['view'];
						$row1->add = $data['groupaccess_' . $groupValue]['add'];
						$row1->edit = $data['groupaccess_' . $groupValue]['edit'];
						$row1->delete = $data['groupaccess_' . $groupValue]['delete'];

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

						$row_amt =& $this->getTable('accessmanager_detail');
						$row_amt->gid = $groupValue;
						$row_amt->section_name = "stockroom_listing";
						$row_amt->view = $data['groupaccess_' . $groupValue]['view'];
						$row_amt->add = $data['groupaccess_' . $groupValue]['add'];
						$row_amt->edit = $data['groupaccess_' . $groupValue]['edit'];
						$row_amt->delete = $data['groupaccess_' . $groupValue]['delete'];

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

						$row_img =& $this->getTable('accessmanager_detail');
						$row_img->gid = $groupValue;
						$row_img->section_name = "stockimage";
						$row_img->view = $data['groupaccess_' . $groupValue]['view'];
						$row_img->add = $data['groupaccess_' . $groupValue]['add'];
						$row_img->edit = $data['groupaccess_' . $groupValue]['edit'];
						$row_img->delete = $data['groupaccess_' . $groupValue]['delete'];

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
							$row_img->add = null;

							if ($row_img->check())
							{
								if (!$row_img->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}

						$row_imgd =& $this->getTable('accessmanager_detail');
						$row_imgd->gid = $groupValue;
						$row_imgd->section_name = "stockimage_detail";
						$row_imgd->view = $data['groupaccess_' . $groupValue]['view'];
						$row_imgd->add = $data['groupaccess_' . $groupValue]['add'];
						$row_imgd->edit = $data['groupaccess_' . $groupValue]['edit'];
						$row_imgd->delete = $data['groupaccess_' . $groupValue]['delete'];

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
							$row_imgd->add = null;

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
				$row->gid = $groupValue;
				$row->section_name = $data['section'];
				$row->view = $data['groupaccess_' . $groupValue]['view'];
				$row->add = $data['groupaccess_' . $groupValue]['add'];
				$row->edit = $data['groupaccess_' . $groupValue]['edit'];
				$row->delete = $data['groupaccess_' . $groupValue]['delete'];

				if ($row->section_name == 'stockroom')
				{
					$child_section = "stockroom_detail";

					if ($row->view == 1 && $row->add == 1)
					{
						$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
							. $row->view . "',`add` = '" . $row->add . "',`edit` = '"
							. $row->edit . "',`delete` = '" . $row->delete . "'"
							. " WHERE `section_name` = '" . $child_section . "' AND `gid` = '" . $row->gid . "'";

						$this->_db->setQuery($query);
						$this->_db->Query();
					}
					else
					{
						$child_view = null;
						$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
							. $child_view . "',`add` = '" . $row->add . "',`edit` = '"
							. $row->edit . "',`delete` = '" . $row->delete . "'"
							. " WHERE `section_name` = '" . $child_section . "' AND `gid` = '" . $row->gid . "'";

						$this->_db->setQuery($query);
						$this->_db->Query();
					}

					$child_section1 = "stockroom_listing";

					if ($row->view == 1 && $row->edit == 1)
					{
						$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
							. $row->view . "',`add` = '" . $row->add . "',`edit` = '"
							. $row->edit . "',`delete` = '" . $row->delete . "'"
							. " WHERE `section_name` = '" . $child_section1 . "' AND `gid` = '" . $row->gid . "'";

						$this->_db->setQuery($query);
						$this->_db->Query();
					}
					else
					{
						$child_view1 = null;
						$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
							. $child_view1 . "',`add` = '" . $row->add . "',`edit` = '"
							. $row->edit . "',`delete` = '" . $row->delete . "'"
							. " WHERE `section_name` = '" . $child_section1 . "' AND `gid` = '" . $row->gid . "'";

						$this->_db->setQuery($query);
						$this->_db->Query();
					}

					$child_section2 = "stockimage";

					if ($row->view == 1 && $row->edit == 1)
					{
						$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
							. $row->view . "',`add` = '" . $row->add . "',`edit` = '"
							. $row->edit . "',`delete` = '" . $row->delete . "'"
							. " WHERE `section_name` = '" . $child_section2 . "' AND `gid` = '" . $row->gid . "'";

						$this->_db->setQuery($query);
						$this->_db->Query();
					}
					else
					{
						$child_view2 = null;
						$child_add2 = null;
						$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
							. $child_view2 . "',`add` = '" . $child_add2 . "',`edit` = '"
							. $row->edit . "',`delete` = '" . $row->delete . "'"
							. " WHERE `section_name` = '" . $child_section2 . "' AND `gid` = '" . $row->gid . "'";

						$this->_db->setQuery($query);
						$this->_db->Query();
					}

					$child_section3 = "stockimage_detail";

					if ($row->view == 1 && $row->edit == 1)
					{
						$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
							. $row->view . "',`add` = '" . $row->add . "',`edit` = '"
							. $row->edit . "',`delete` = '" . $row->delete . "'"
							. " WHERE `section_name` = '" . $child_section3 . "' AND `gid` = '" . $row->gid . "'";

						$this->_db->setQuery($query);
						$this->_db->Query();
					}
					else
					{
						$child_view1 = null;
						$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
							. $child_view1 . "',`add` = '" . $row->add . "',`edit` = '"
							. $row->edit . "',`delete` = '" . $row->delete . "'"
							. " WHERE `section_name` = '" . $child_section3 . "' AND `gid` = '" . $row->gid . "'";

						$this->_db->setQuery($query);
						$this->_db->Query();
					}
				}

				$query = "UPDATE " . $this->_table_prefix . "accessmanager SET `view` = '"
					. $row->view . "',`add` = '" . $row->add . "',`edit` = '"
					. $row->edit . "',`delete` = '" . $row->delete . "'"
					. " WHERE `section_name` = '" . $row->section_name . "' AND `gid` = '" . $row->gid . "'";

				$this->_db->setQuery($query);
				$this->_db->Query();
			}
		}

		return $row;
	}

	/**
	 * Method to get section
	 *
	 * @access public
	 * @return boolean
	 */
	public function checksection($section)
	{
		$db = JFactory::getDBO();
		$query = " SELECT count(*) FROM " . $this->_table_prefix . "accessmanager "
			. "WHERE `section_name` = '" . $section . "'";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
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

		foreach ($groups as $key => $val)
		{
			$returnable[$val->id] = str_repeat('<span class="gi">|&mdash;</span>', $val->level) . $val->title;
		}

		return $returnable;
	}
}
