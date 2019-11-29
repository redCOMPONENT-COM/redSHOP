<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Media
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.1.0
 */
class RedshopTableMedia extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_media';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'media_id';

	/**
	 * @var integer
	 */
	public $media_id;

	/**
	 * @var string
	 */
	public $media_name = '';

	/**
	 * @var string
	 */
	public $media_alternate_text = '';

	/**
	 * @var string
	 */
	public $media_section = '';

	/**
	 * @var integer
	 */
	public $section_id = '';

	/**
	 * @var string
	 */
	public $media_type = '';

	/**
	 * @var string
	 */
	public $media_mimetype = '';

	/**
	 * @var integer
	 */
	public $published = '';

	/**
	 * @var integer
	 */
	public $ordering = '';

	/**
	 * @var string
	 */
	public $scope = '';

	/**
	 * Delete one or more registers
	 *
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfuly?
	 */
	protected function doDelete($pk = null)
	{
		if ($this->media_section == 'images'
			&& ($this->media_section == 'manufacturer' || $this->media_section == 'category'))
		{
			// New folder structure
			$folder = JPath::clean(REDSHOP_MEDIA_IMAGE_RELPATH . $this->media_section . '/' . $this->section_id . '/thumb');

			if (JFolder::exists($folder))
			{
				JFolder::delete($folder);
			}

			$file = JPath::clean(REDSHOP_MEDIA_IMAGE_RELPATH . $this->media_section . '/' . $this->section_id . '/' . $this->media_name);

			if (JFile::exists($file))
			{
				JFile::delete($file);
			}
		}

		return parent::doDelete($pk);
	}
}
