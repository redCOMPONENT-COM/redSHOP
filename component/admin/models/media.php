<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class RedshopModelMedia extends RedshopModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$jInput = JFactory::getApplication()->input;
		$this->context .= '.' . $jInput->getCmd('media_section', 'none') . '.' . $jInput->getInt('section_id', 0);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter_media_section');
		$id .= ':' . $this->getState('media_type');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'media_id', $direction = 'desc')
	{
		$filter_media_section = $this->getUserStateFromRequest($this->context . '.filter_media_section', 'filter_media_section', 0);
		$this->setState('filter_media_section', $filter_media_section);

		$media_type = $this->getUserStateFromRequest($this->context . '.media_type', 'media_type', '');
		$this->setState('media_type', $media_type);

		$folder = JRequest::getVar('folder', '', '', 'path');
		$this->setState('folder', $folder);

		$parent = str_replace("\\", "/", dirname($folder));
		$parent = ($parent == '.') ? null : $parent;
		$this->setState('parent', $parent);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('m.*')
			->from($db->qn('#__redshop_media', 'm'));

		if ($filterMediaSection = $this->getState('filter_media_section'))
		{
			$query->where('media_section = ' . $db->q($filterMediaSection));
		}
		elseif ($mediaSection = $app->input->getCmd('media_section', ''))
		{
			$query->where('media_section = ' . $db->q($mediaSection));

			if ($section_id = $app->input->getInt('section_id', 0))
			{
				$query->where('section_id = ' . (int) $section_id);
			}
		}

		if ($media_type = $this->getState('media_type'))
		{
			$query->where('media_type = ' . $db->q($media_type));
		}

		$filterOrderDir = $this->getState('list.direction');
		$filterOrder = $this->getState('list.ordering');
		$query->order($db->escape($filterOrder . ' ' . $filterOrderDir));

		return $query;
	}

	public function getImages()
	{
		$list = $this->getList();

		return $list['images'];
	}

	public function getFolders()
	{
		$list = $this->getList();

		return $list['folders'];
	}

	public function getDocuments()
	{
		$list = $this->getList();

		return $list['docs'];
	}

	/**
	 * Build imagelist
	 *
	 * @param string $listFolder The image directory to display
	 *
	 * @since 1.5
	 */
	public function getList()
	{
		static $list;

		// Only process the list once per request
		if (is_array($list))
		{
			return $list;
		}

		// Get current path from request
		$current = $this->getState('folder');

		// If undefined, set to empty
		if ($current == 'undefined')
		{
			$current = '';
		}

		$fdownload = JRequest::getInt('fdownload');

		if ($fdownload != 1)
		{
			// Initialize variables
			if (strlen($current) > 0)
			{
				$basePath = REDSHOP_FRONT_IMAGES_RELPATH . $current;
			}
			else
			{
				$basePath = REDSHOP_FRONT_IMAGES_RELPATH;
			}

			$mediaBase = str_replace(DIRECTORY_SEPARATOR, '/', REDSHOP_FRONT_IMAGES_RELPATH);
		}
		else
		{
			if (strlen($current) > 0)
			{
				$basePath = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT') . '/' . $current;
			}
			else
			{
				$basePath = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT');
			}

			$mediaBase = str_replace(DIRECTORY_SEPARATOR, '/', Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT') . '/');
		}

		$images = array();
		$folders = array();
		$docs = array();

		// Get the list of files and folders from the given folder
		$fileList = JFolder::files($basePath);
		$folderList = JFolder::folders($basePath);

		// Iterate over the files if they exist
		if ($fileList !== false)
		{
			foreach ($fileList as $file)
			{
				if (file_exists($basePath . '/' . $file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html')
				{
					$tmp = new JObject;
					$tmp->name = $file;
					$tmp->path = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($basePath . '/' . $file));
					$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
					$tmp->size = filesize($tmp->path);

					$ext = strtolower(JFile::getExt($file));

					switch ($ext)
					{
						// Image
						case 'jpg':
						case 'png':
						case 'gif':
						case 'xcf':
						case 'odg':
						case 'bmp':
						case 'jpeg':
							$info = @getimagesize($tmp->path);
							$tmp->width = @$info[0];
							$tmp->height = @$info[1];
							$tmp->type = @$info[2];
							$tmp->mime = @$info['mime'];

							if (($info[0] > 60) || ($info[1] > 60))
							{
								$dimensions = RedshopHelperMedia::imageResize($info[0], $info[1], 60);
								$tmp->width_60 = $dimensions[0];
								$tmp->height_60 = $dimensions[1];
							}
							else
							{
								$tmp->width_60 = $tmp->width;
								$tmp->height_60 = $tmp->height;
							}

							if (($info[0] > 16) || ($info[1] > 16))
							{
								$dimensions = RedshopHelperMedia::imageResize($info[0], $info[1], 16);
								$tmp->width_16 = $dimensions[0];
								$tmp->height_16 = $dimensions[1];
							}
							else
							{
								$tmp->width_16 = $tmp->width;
								$tmp->height_16 = $tmp->height;
							}

							$images[] = $tmp;
							break;

						// Non-image document
						default:
							$iconfile_32 = JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/mime-icon-32/' . $ext . '.png';

							if (file_exists($iconfile_32))
							{
								$tmp->icon_32 = 'components/com_redshop/assets/images/mime-icon-32/' . $ext . '.png';
							}
							else
							{
								$tmp->icon_32 = 'components/com_redshop/assets/images/con_info.png';
							}

							$iconfile_16 = JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/mime-icon-16/' . $ext . '.png';

							if (file_exists($iconfile_16))
							{
								$tmp->icon_16 = 'components/com_redshop/assets/images/mime-icon-16/' . $ext . '.png';
							}
							else
							{
								$tmp->icon_16 = 'components/com_redshop/assets/images/con_info.png';
							}

							$docs[] = $tmp;
							break;
					}
				}
			}
		}

		// Iterate over the folders if they exist
		if ($folderList !== false)
		{
			foreach ($folderList as $folder)
			{
				$tmp = new JObject;
				$tmp->name = basename($folder);
				$tmp->path = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($basePath . '/' . $folder));
				$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
				$count = RedshopHelperMedia::countFiles($tmp->path);
				$tmp->files = $count[0];
				$tmp->folders = $count[1];

				$folders[] = $tmp;
			}
		}

		$list = array('folders' => $folders, 'docs' => $docs, 'images' => $images);

		return $list;
	}

	public function store($data)
	{
		$row = $this->getTable('media_download');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function getAdditionalFiles($media_id)
	{
		$query = "SELECT * FROM `#__redshop_media_download` "
			. "WHERE `media_id`='" . $media_id . "' ";

		return $this->_getList($query);
	}

	public function deleteAddtionalFiles($fileId)
	{
		$query = "SELECT name FROM `#__redshop_media_download` "
			. "WHERE `id`='" . $fileId . "' ";
		$this->_db->setQuery($query);
		$filename = $this->_db->loadResult();
		$path = JPATH_ROOT . '/components/com_redshop/assets/download/product/' . $filename;

		if (JFile::exists($path))
		{
			JFile::delete($path);
		}

		$query = "DELETE FROM `#__redshop_media_download` WHERE `id`='" . $fileId . "' ";
		$this->_db->setQuery($query);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	public function saveorder($cid = array(), $order)
	{
		$row = $this->getTable('media_detail');
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		$conditions = array();

		// Update ordering values
		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}

				// Remember to updateOrder this group
				$condition = 'section_id = ' . (int) $row->section_id . ' AND media_section = "' . $row->media_section . '"';
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$conditions[] = array($row->media_id, $condition);
				}
			}
		}

		// Execute updateOrder for each group
		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}
	}

	/**
	 * Get all media items
	 *
	 * @return  array
	 */
	public function all()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn("#__redshop_media"));

		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	/**
	 * Delete media item by ID
	 *
	 * @param   integer  $id  [description]
	 *
	 * @return  boolean
	 */
	public function deleteFile($id)
	{
		$db = JFactory::getDbo();

		// Check item is existed
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn("#__redshop_media"))
					->where($db->qn('media_id') . ' = ' . $id);
		$db->setQuery($query);
		$file = $db->loadObject();

		if ($file)
		{
			$path = JPATH_ROOT . '/components/com_redshop/assets/images/' . $file->media_section . '/' . $file->media_name;

			if (JFile::exists($path))
			{
				JFile::delete($path);
			}
		}

		$query = $db->getQuery(true)
					->delete($db->qn('#__redshop_media'))
					->where($db->qn('media_id') . ' = ' . $id);
		$db->setQuery($query);

		if (!$db->execute())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Create a media item by ID
	 *
	 * @param   array  $file  File array data
	 *
	 * @return  boolean
	 */
	public function newFile($file)
	{
		$db = JFactory::getDbo();
		$fileObj = new stdClass;

		$fileObj->media_name     = $file['media_name'];
		$fileObj->media_section  = $file['media_section'];
		$fileObj->media_type     = $file['media_type'];
		$fileObj->media_mimetype = $file['media_mimetype'];
		$fileObj->published      = 1;

		if (!$db->insertObject('#__redshop_media', $fileObj))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return $db->insertid();
	}
}
