<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The Wrapper table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Catalog
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableWrapper extends RedshopTable
{
	/**
	 * The table name without prefix.
	 *
	 * @var string
	 */
	protected $_tableName = 'redshop_wrapper';

	/**
	 * Delete one or more registers
	 *
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfuly?
	 */
	protected function doDelete($pk = null)
	{
		if ($this->image != '' && file(REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/' . $this->image)) {
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/' . $this->image);
		}

		return parent::doDelete($pk);
	}

	/**
	 * Do the database store.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean
	 */
	protected function doStore($updateNulls = false)
	{
		// Get input
		$app   = JFactory::getApplication();
		$input = $app->input;

		$wrapperFile = $input->files->get('jform');
		$image        = $wrapperFile['image_file'];

		if ($image['name'] != '' && $this->image != '') {
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/' . $this->image);
			$this->image = '';
		}

		if ($image['name'] != '') {
			$image['name']        = RedshopHelperMedia::cleanFileName($image['name']);
			$this->image = $image['name'];
			JFile::upload($image['tmp_name'], REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/' . $image['name']);
		}

		if (!parent::doStore($updateNulls)) {
			return false;
		}

		return true;
	}

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		if (empty($this->name)) {
			return false;
		}

		return parent::doCheck();
	}
}