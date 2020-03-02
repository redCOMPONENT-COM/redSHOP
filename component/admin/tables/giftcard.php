<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Economic\RedshopEconomic;

/**
 * Giftcard table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.Giftcard
 * @since       1.6
 */
class RedshopTableGiftcard extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_giftcard';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'giftcard_id';

	/**
	 * Delete one or more registers
	 *
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfuly?
	 */
	protected function doDelete($pk = null)
	{
		if ($this->giftcard_image != '' && file(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_image))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_image);
		}

		if ($this->giftcard_bgimage != '' && file(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_bgimage))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_bgimage);
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

		$giftCardFile = $input->files->get('jform');
		$image        = $giftCardFile['giftcard_image_file'];

		if ($image['name'] != '' && $this->giftcard_image != '')
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_image);
			$this->giftcard_image = '';
		}

		if ($image['name'] != '')
		{
			$image['name']        = RedshopHelperMedia::cleanFileName($image['name']);
			$this->giftcard_image = $image['name'];
			JFile::upload($image['tmp_name'], REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $image['name']);
		}

		// Get background image file
		$bgImage = $giftCardFile['giftcard_bgimage_file'];

		if (($bgImage['name'] != '' && $this->giftcard_bgimage != ''))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $this->giftcard_bgimage);
			$this->giftcard_bgimage = '';
		}

		if ($bgImage['name'] != '')
		{
			$bgImage['name']        = RedshopHelperMedia::cleanFileName($bgImage['name']);
			$this->giftcard_bgimage = $bgImage['name'];
			JFile::upload($bgImage['tmp_name'], REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $bgImage['name']);
		}

		$this->giftcard_price = RedshopHelperProduct::redpriceDecimal($this->giftcard_price);
		$this->giftcard_value = RedshopHelperProduct::redpriceDecimal($this->giftcard_value);

		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$giftData                  = new stdClass;
			$giftData->product_id      = $this->giftcard_id;
			$giftData->product_number  = "gift_" . $this->giftcard_id . "_" . $this->giftcard_name;
			$giftData->product_name    = $this->giftcard_name;
			$giftData->product_price   = $this->giftcard_price;
			$giftData->accountgroup_id = $this->accountgroup_id;
			$giftData->product_volume  = 0;

			RedshopEconomic::createProductInEconomic($giftData);
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
		if (empty($this->giftcard_name))
		{
			return false;
		}

		if (empty($this->giftcard_price))
		{
			return false;
		}

		if (empty($this->giftcard_value))
		{
			return false;
		}

		if (empty($this->giftcard_validity))
		{
			return false;
		}

		return parent::doCheck();
	}
}
