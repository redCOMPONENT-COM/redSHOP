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
     * @var string
     */
    public $image;

	/**
	 * Delete one or more registers
	 *
	 * @param   mixed $pk The string/array of id's to delete
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
		$app        = JFactory::getApplication();
        $data       = $app->input->post->get('jform', array(), 'array');
		$image      = $app->input->files->get('image');
        $wrapperDir = REDSHOP_FRONT_IMAGES_RELPATH . 'wrapper/';
		$productIds = $data['product_id'];

		if (is_array($productIds)) {
		    $this->product_id = implode(',', $productIds);
        }

        if ( ! empty($image['name']) || ! empty($image['image_tmp'])) {
            $imagePath = $wrapperDir . $data['image'];

            if (JFile::exists($imagePath)) {
                JFile::delete($imagePath);
            }
        }

        if ( ! empty($image['name'])) {
            $imageName = RedshopHelperMedia::cleanFileName($image['name']);

            // Image Upload
            $imageType = JFile::getExt($image['name']);

            $src  = $image['tmp_name'];
            $dest = $wrapperDir . $imageName;

            if ($imageType == 'jpg' || $imageType == 'jpeg' || $imageType == 'gif' || $imageType == 'png') {
                JFile::upload($src, $dest);
                $this->image = $imageName;
            }
        } elseif ( ! empty($data['image_tmp'])) {
            $imageSplit = explode('/', $data['image_tmp']);
            $imageName   = RedshopHelperMedia::cleanFileName($imageSplit[count($imageSplit) - 1]);
            $this->image = $imageName;

            // Image copy
            $src  = JPATH_ROOT . '/' . $data['image_tmp'];
            $dest = $wrapperDir . $imageName;

            copy($src, $dest);
        }

        if (empty($data['jform']['id']) && Redshop::getConfig()->get('NEW_WRAPPER_GET_VALUE_FROM')) {
            $destName     = time() . $data['image'];
            $imagePath     = $wrapperDir . $data['image'];
            $copyImagePath = $wrapperDir . $destName;

            if (JFile::exists($imagePath)) {
                JFile::copy($imagePath, $copyImagePath);
            }

            $this->image = $destName;
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