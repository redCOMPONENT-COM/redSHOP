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
 * Table Manufacturer
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.1.0
 */
class RedshopTableManufacturer extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_manufacturer';

	/**
	 * @var  integer
	 */
	public $id;

	/**
	 * @var  string
	 */
	public $name;

	/**
	 * @var  integer
	 */
	public $published = 1;

	/**
	 * @var  integer
	 */
	public $product_per_page;

	/**
	 * Delete one or more registers
	 *
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean            Deleted successfully?
	 */
	protected function doDelete($pk = null)
	{
		$manufacturerId = $this->id;
		$media          = RedshopEntityManufacturer::getInstance($manufacturerId)->getMedia();

		if (!parent::doDelete($pk))
		{
			return false;
		}

		// B/C for old plugin
		JPluginHelper::importPlugin('redshop_product');
		RedshopHelperUtility::getDispatcher()->trigger('onAfterManufacturerDelete', array($manufacturerId));

		// Delete associated media
		if ($media->isValid())
		{
			/** @var RedshopTableMedia $mediaTable */
			$mediaTable = RedshopTable::getInstance('Media', 'RedshopTable');

			if ($mediaTable->load($media->get('media_id')))
			{
				$mediaTable->delete();
			}
		}

		// Delete media folder
		JFolder::delete(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $manufacturerId);

		return true;
	}

	/**
	 * Do the database store.
	 *
	 * @param   boolean $updateNulls True to update null values as well.
	 *
	 * @return  boolean
	 * @throws  Exception
	 * @since   2.1.0
	 */
	protected function doStore($updateNulls = false)
	{
		JPluginHelper::importPlugin('redshop_product');

		// B/C for old plugin
		$isNew = $this->id > 0 ? false : true;
		RedshopHelperUtility::getDispatcher()->trigger('onBeforeManufacturerSave', array(&$this, $isNew));

		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		// Store fields data.
		$this->storeFields();

		// Store media
		$this->storeMedia();

		// B/C for old plugin
		RedshopHelperUtility::getDispatcher()->trigger('onAfterManufacturerSave', array(&$this, $isNew));

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
		if (!parent::doCheck())
		{
			return false;
		}

		// Check product per page
		if (!$this->product_per_page)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_MANUFACTURER_ERROR_PRODUCT_PER_PAGE'));

			return false;
		}

		return true;
	}

	/**
	 * Method for store fields data.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since  2.1.0
	 */
	protected function storeFields()
	{
		RedshopHelperExtrafields::extraFieldSave(
			JFactory::getApplication()->input->post->getArray(), RedshopHelperExtrafields::SECTION_MANUFACTURER, $this->id
		);
	}

	/**
	 * Method for store fields data.
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since  2.1.0
	 */
	protected function storeMedia()
	{
		$mediaField = 'manufacturer_image';

		// Prepare target folder.
		\Redshop\Helper\Media::createFolder(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $this->id);

		// Prepare target folder.
		\Redshop\Helper\Media::createFolder(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $this->id . '/thumb');

		$input = JFactory::getApplication()->input;

		$dropzone = $input->post->get('dropzone', array(), '');
		$dropzone = isset($dropzone[$mediaField]) ? $dropzone[$mediaField] : null;

		$dropzoneAlternateText = $input->post->get('dropzone_alternate_text', array(), '');
		$dropzoneAlternateText = isset($dropzoneAlternateText[$mediaField]) ? $dropzoneAlternateText[$mediaField] : null;

		if (null === $dropzone)
		{
			return;
		}

		foreach ($dropzone as $key => $value)
		{
			/** @var RedshopTableMedia $mediaTable */
			$mediaTable = JTable::getInstance('Media', 'RedshopTable');

			if (strpos($key, 'media-') !== false)
			{
				$mediaTable->load(str_replace('media-', '', $key));

				// Delete old image.
				$oldMediaFile = JPath::clean(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/'
					. $this->id . '/' . $mediaTable->media_name
				);

				if (JFile::exists($oldMediaFile))
				{
					JFile::delete($oldMediaFile);
				}

				if (empty($value))
				{
					$mediaTable->delete();

					continue;
				}
			}
			else
			{
				$mediaTable->set('section_id', $this->id);
				$mediaTable->set('media_section', 'manufacturer');
			}

			if (!JFile::exists(JPATH_ROOT . '/' . $value))
			{
				continue;
			}

			$alternateText = isset($dropzoneAlternateText[$key]) ? $dropzoneAlternateText[$key] : $this->name;

			$mediaTable->set('media_alternate_text', $alternateText);
			$mediaTable->set('media_type', 'images');
			$mediaTable->set('published', 1);

			// Copy new image for this media
			$fileName = md5($this->name) . '.' . JFile::getExt($value);
			$file     = REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $this->id . '/' . $fileName;

			JFile::move(JPATH_ROOT . '/' . $value, $file);

			$mediaTable->set('media_name', $fileName);
			$mediaTable->store();

			// Optimize image
			$factory   = new \ImageOptimizer\OptimizerFactory;
			$optimizer = $factory->get();
			$optimizer->optimize($file);
		}

		// Clear thumbnail folder
		\Redshop\Helper\Media::createFolder(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $this->id . '/thumb', true);
	}
}
