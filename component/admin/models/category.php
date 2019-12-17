<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Redshop Category Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Category
 * @since       2.0.6
 */
class RedshopModelCategory extends RedshopModelForm
{
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   string $type   The table type to instantiate
	 * @param   string $prefix A prefix for the table class name. Optional.
	 * @param   array  $config Configuration array for model. Optional.
	 *
	 * @return  JTable           A database object
	 *
	 * @since   2.0.6
	 */
	public function getTable($type = 'Category', $prefix = 'RedshopTable', $config = array())
	{
		return RedshopTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array   $data     Data for the form. [optional]
	 * @param   boolean $loadData True if the form is to load its own data (default case), false if not. [optional]
	 *
	 * @return  mixed               A JForm object on success, false on failure
	 *
	 * @since   2.0.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		/** @scrutinizer ignore-call */
		$form = $this->loadForm(
			'com_redshop.category',
			'category',
			array(
				'control'   => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   2.0.6
	 * @throws  Exception
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.category.data', array());

		if (empty($data))
		{
			/** @scrutinizer ignore-call */
			$data = $this->getItem();
		}

		$this->preprocessData('com_redshop.category', $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
	 *
	 * @return  mixed         Object on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItem($pk = null)
	{
		/** @scrutinizer ignore-call */
		$item = parent::getItem($pk);

		if (!empty($item->id))
		{
			$item->more_template = explode(',', $item->more_template);

			return $item;
		}

		$item->template          = Redshop::getConfig()->get('CATEGORY_TEMPLATE', "");
		$item->products_per_page = 5;

		return $item;
	}

	/**
	 * Method to get extra fields to category.
	 *
	 * @param   integer $item The object category values.
	 *
	 * @return  mixed           Object on success, false on failure.
	 * @throws  Exception
	 *
	 * @since   2.0.6
	 */
	public function getExtraFields($item)
	{
		$templateDesc = RedshopHelperTemplate::getTemplate('category', $item->template, '');
		$template     = $templateDesc[0]->template_desc;
		$regex        = '/{rs_[\w]{1,}\}/';
		preg_match_all($regex, $template, $matches);

		if (empty($matches[0]))
		{
			return '';
		}

		$listField = array();

		$fieldName   = implode(',', $matches[0]);
		$fieldName   = str_replace(array('{', '}'), '', $fieldName);
		$listField[] = RedshopHelperExtrafields::listAllField(RedshopHelperExtrafields::SECTION_CATEGORY, $item->id, $fieldName);

		return implode('', $listField);
	}

	/**
	 * Method to store category.
	 *
	 * @param   array $data The object category data.
	 *
	 * @return  boolean
	 * @throws  Exception
	 *
	 * @since   2.0.6
	 */
	public function saveCategory(&$data)
	{
		JPluginHelper::importPlugin('redshop_category');

		/** @var RedshopTableCategory $row */
		$row = $this->getTable();
		$pk  = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');

		// Load the row if saving an existing record.
		if ($pk > 0)
		{
			$row->load($pk);
		}

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($row->parent_id != $data['parent_id'] || $data['id'] == 0)
		{
			$row->setLocation($data['parent_id'], 'last-child');
		}

		$data['product_filter_params'] = "";

		if ($data['product_filter']['enable'] == 1)
		{
			$registry = new Registry($data['product_filter']);
			$data['product_filter_params'] = (string) $registry;
		}

		if (!$row->bind($data))
		{
			/** @scrutinizer ignore-deprecated */
			$this->setError(/** @scrutinizer ignore-deprecated */ $row->getError());

			return false;
		}

		// Check the data.
		if (!$row->check())
		{
			/** @scrutinizer ignore-deprecated */
			$this->setError(/** @scrutinizer ignore-deprecated */ $row->getError());

			return false;
		}

		// Media store
		$dropzoneMedia = JFactory::getApplication()->input->get('dropzone', array(), 'ARRAY');

		if (!empty($dropzoneMedia))
		{
			$row->setOption('media', $dropzoneMedia);
		}

		if (!$row->store())
		{
			return false;
		}

		$data['id'] = $row->id;

		RedshopHelperUtility::getDispatcher()->trigger('onAfterCategorySave', array(&$row, $data['id']));

		if (isset($row->id))
		{
			$this->setState($this->getName() . '.id', $row->id);
		}

		// Sheking for the image at the updation time
		if (!empty($data['id']) && !empty($data['category_full_image']))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_image']);
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_image']);
		}

		// Extra Field Data Saved
		RedshopHelperExtrafields::extraFieldSave(JFactory::getApplication()->input->post->getArray(), 2, $row->id);

		// Start Accessory Product
		// @TODO Need to add an better solution.
		$this->productAccessoriesStore($row->id);

		return true;
	}

	/**
	 * Method to copy.
	 *
	 * @param   array $pks Category id list.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public function copy(&$pks)
	{
		if (!count($pks))
		{
			return false;
		}

		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_category'))
			->where($db->qn('id') . ' IN (' . implode(',', $pks) . ')');

		$copyData = $db->setQuery($query)->loadObjectList();

		for ($i = 0, $in = count($copyData); $i < $in; $i++)
		{
			$post                          = array();
			$post['id']                    = 0;
			$post['name']                  = $this->renameToUniqueValue('name', $copyData[$i]->name, '', 'Category');
			$post['short_description']     = $copyData[$i]->short_description;
			$post['description']           = $copyData[$i]->description;
			$post['template']              = $copyData[$i]->template;
			$post['more_template']         = $copyData[$i]->more_template;
			$post['products_per_page']     = $copyData[$i]->products_per_page;
			$post['metakey']               = $copyData[$i]->metakey;
			$post['metadesc']              = $copyData[$i]->metadesc;
			$post['metalanguage_setting']  = $copyData[$i]->metalanguage_setting;
			$post['metarobot_info']        = $copyData[$i]->metarobot_info;
			$post['pagetitle']             = $copyData[$i]->pagetitle;
			$post['pageheading']           = $copyData[$i]->pageheading;
			$post['sef_url']               = $copyData[$i]->sef_url;
			$post['published']             = $copyData[$i]->published;
			$post['category_pdate']        = date("Y-m-d h:i:s");
			$post['ordering']              = count($copyData) + $i + 1;
			$post['parent_id']             = $copyData[$i]->parent_id;
			$post['level']                 = $copyData[$i]->level;
			$post['product_filter_params'] = $copyData[$i]->product_filter_params;

			$this->/** @scrutinizer ignore-call */ saveCategory($post);

			/** @var RedshopEntityCategory $medias */
			$medias = RedshopEntityCategory::getInstance($copyData[$i]->id)->getMedia();

			foreach ($medias->/** @scrutinizer ignore-call */ getAll() as $media)
			{
				/** @var RedshopEntityMedia $media */
				if ($media->get('scope') == 'full')
				{
					$this->/** @scrutinizer ignore-call */ storeMediaCopy($copyData[$i]->id, $post['id'], $media, 'full');
				}
				elseif ($media->get('scope') == 'back')
				{
					$this->/** @scrutinizer ignore-call */ storeMediaCopy($copyData[$i]->id, $post['id'], $media, 'back');
				}
			}

		}

		return true;
	}

	/**
	 * Method for store media copy
	 *
	 * @param   integer  $copyCatId
	 * @param   integer  $catId
	 * @param   object   $media
	 * @param   string   $scope
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function storeMediaCopy($copyCatId, $catId, $media, $scope)
	{
		/** @var RedshopEntityMediaImage $fullImage */
		$fullImage     = RedshopEntityMediaImage::getInstance($media->getId());
		$fullImageName = $fullImage->get('media_name');
		$newFullImage  = time() . '_' . $fullImageName;

		$src  = REDSHOP_MEDIA_IMAGE_RELPATH . 'category/' . $copyCatId . '/' . $fullImageName;

		$dest = REDSHOP_MEDIA_IMAGE_RELPATH . 'category/' . $catId . '/' . $newFullImage;

		if (JFile::exists($src))
		{
			JFile::copy($src, $dest);
		}

		if (JFile::exists($dest))
		{
			$mediaFullImage                       = new stdClass;
			$mediaFullImage->media_name           = $newFullImage;
			$mediaFullImage->media_alternate_text = '';
			$mediaFullImage->media_section        = 'category';
			$mediaFullImage->section_id           = $catId;
			$mediaFullImage->media_type           = 'images';
			$mediaFullImage->published            = 1;
			$mediaFullImage->scope                = $scope;

			$this->_db->insertObject('#__redshop_media', $mediaFullImage);
		}
	}

	/**
	 * Process for store product accessories
	 *
	 * @param   integer $categoryId ID of category
	 *
	 * @since   2.0.6
	 * @throws  Exception
	 *
	 * @return  boolean
	 */
	public function productAccessoriesStore($categoryId)
	{
		$productAccessories = JFactory::getApplication()->input->get('product_accessory', array(), 'array');

		if (empty($productAccessories) || !is_array($productAccessories))
		{
			return true;
		}

		$productAccessories = array_merge(array(), $productAccessories);
		$productList        = RedshopEntityCategory::getInstance($categoryId)->getProducts();
		$productIds         = array_column($productList, 'id');

		if (empty($productList))
		{
			return true;
		}

		foreach ($productList as $product)
		{
			$productId = $product->id;

			foreach ($productAccessories as $productAccessory)
			{
				if (in_array($productAccessory['child_product_id'], $productIds))
				{
					continue;
				}

				$accessoryId    = RedshopHelperAccessory::checkAccessoryExists($productId, $productAccessory['child_product_id']);
				$accessoryTable = JTable::getInstance('Accessory_detail', 'Table');

				$accessoryTable->accessory_id        = $accessoryId;
				$accessoryTable->category_id         = $categoryId;
				$accessoryTable->product_id          = $productId;
				$accessoryTable->child_product_id    = $productAccessory['child_product_id'];
				$accessoryTable->accessory_price     = $productAccessory['accessory_price'];
				$accessoryTable->oprand              = $productAccessory['oprand'];
				$accessoryTable->ordering            = $productAccessory['ordering'];
				$accessoryTable->setdefault_selected = 0;

				if (isset($productAccessory['setdefault_selected']) && $productAccessory['setdefault_selected'] == 1)
				{
					$accessoryTable->setdefault_selected = 1;
				}

				if (!$accessoryTable->store())
				{
					/** @scrutinizer ignore-deprecated */
					$this->setError(/** @scrutinizer ignore-deprecated */ $this->_db->getErrorMsg());

					return false;
				}
			}
		}
	}

	/**
	 * Saves the manually set order of records.
	 *
	 * @param   array    $pks    An array of primary key ids.
	 * @param   integer  $order  +1 or -1
	 *
	 * @return  boolean|JException  Boolean true on success, false on failure, or JException if no items are selected
	 *
	 * @since   1.6
	 */
	public function saveorder($pks = array(), $order = null)
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->saveorder($pks, $order))
		{
			/** @scrutinizer ignore-deprecated */
			$this->setError(/** @scrutinizer ignore-deprecated */ $table->getError());

			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method for store media
	 *
	 * @param   object  $category  Category data
	 * @param   array   $data      File name
	 * @param   string  $scope     Scope of media.
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public function storeMedia($category, $data, $scope)
	{
		if (empty($data))
		{
			return;
		}

		/** @var RedshopTableMedia $table */
		$table = RedshopTable::getAdminInstance('Media', array('ignore_request' => true), 'com_redshop');

		foreach ($data as $key => $file)
		{
			if (strpos($key, 'media-') !== false)
			{
				$table->load(str_replace('media-', '', $key));

				// Delete old image.
				$oldMediaFile = JPath::clean(REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/'
					. $category->id . '/' . $table->media_name
				);

				if (JFile::exists($oldMediaFile))
				{
					JFile::delete($oldMediaFile);
				}

				if (empty($file))
				{
					$table->delete();

					continue;
				}
			}
			else
			{
				$table->set('section_id', $category->id);
				$table->set('media_section', 'category');
				$table->set('ordering', 0);
				$table->set('scope', $scope);
				$table->set('media_type', 'images');
				$table->set('published', 1);
				$table->set('media_alternate_text', $category->name);
			}

			$file = JPath::clean(JPATH_ROOT . '/' . $file);

			// Check old image exist.
			if (!JFile::exists($file))
			{
				continue;
			}

			// Generate new image using MD5
			$newFileName = md5(basename($category->name) . $scope) . '.' . JFile::getExt($file);

			if (!JFile::move(
				$file,
				JPath::clean(REDSHOP_MEDIA_IMAGE_RELPATH . 'category/' . $category->id . '/' . $newFileName)
			))
			{
				continue;
			}

			if (JFolder::exists(REDSHOP_MEDIA_IMAGE_RELPATH . 'category/' . $category->id . '/thumb'))
			{
				JFolder::delete(REDSHOP_MEDIA_IMAGE_RELPATH . 'category/' . $category->id . '/thumb');
			}

			// Update media data with new file name.
			$table->media_name = $newFileName;
			$table->store();
		}
	}
}
