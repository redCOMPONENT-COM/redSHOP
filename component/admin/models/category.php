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
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_redshop.edit.category.data', array());

		if (empty($data))
		{
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
		$item = parent::getItem($pk);

		if (!empty($item->id))
		{
			$item->more_template = explode(',', $item->more_template);

			return $item;
		}

		$item->template = Redshop::getConfig()->get('CATEGORY_TEMPLATE', "");

		$item->products_per_page = 5;

		return $item;
	}

	/**
	 * Method to get extra fields to category.
	 *
	 * @param   integer $item The object category values.
	 *
	 * @return  mixed           Object on success, false on failure.
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
	 *
	 * @since   2.0.6
	 */
	public function save($data)
	{
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('redshop_category');
		$db  = $this->getDbo();
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

		if (!$row->bind($data))
		{
			$this->setError($row->getError());

			return false;
		}

		if (isset($data['image_delete']))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_image']);
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_image']);

			$fields = array(
				$db->qn('category_thumb_image') . ' = ""',
				$db->qn('category_full_image') . ' = ""'
			);

			$conditions = array(
				$db->qn('id') . ' = ' . $db->q((int) $row->id)
			);

			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_category'))
				->set($fields)
				->where($conditions);
			$db->setQuery($query)->execute();
		}

		// Category full images
		if (empty($data['category_full_image']))
		{
			// Dropzone support.
			$categoryFullImage = JFactory::getApplication()->input->getRaw('category_full_image');

			if (!empty($categoryFullImage))
			{
				// Make the filename unique
				$fileName                  = RedshopHelperMedia::cleanFileName(basename($categoryFullImage));
				$row->category_full_image  = $fileName;
				$row->category_thumb_image = $fileName;

				$src  = JPATH_ROOT . '/' . $categoryFullImage;
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $fileName;

				JFile::move($src, $dest);
			}
			// Delete image
			else
			{
				$path = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $row->category_full_image;

				if (JFile::exists($path))
				{
					JFile::delete($path);
				}

				$path = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $row->category_thumb_image;

				if (JFile::exists($path))
				{
					JFile::delete($path);
				}

				$row->category_full_image  = '';
				$row->category_thumb_image = '';
			}
		}

		if (isset($data['image_back_delete']))
		{
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_back_image']);
			JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_back_image']);

			$fields = array(
				$db->qn('category_back_full_image') . ' = ""'
			);

			$conditions = array(
				$db->qn('id') . ' = ' . $db->q((int) $row->id)
			);

			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_category'))
				->set($fields)
				->where($conditions);
			$db->setQuery($query)->execute();
		}

		// Category Back Full images
		if (empty($data['category_back_full_image']))
		{
			// Dropzone support.
			$categoryBackFullImage = JFactory::getApplication()->input->getRaw('category_back_full_image');

			if (!empty($categoryBackFullImage))
			{
				// Make the filename unique
				$fileName                      = RedshopHelperMedia::cleanFileName(basename($categoryBackFullImage));
				$row->category_back_full_image = $fileName;

				$src = JPATH_ROOT . '/' . $categoryBackFullImage;

				// Specific path of the file
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $fileName;

				JFile::move($src, $dest);
			}
			// Delete image
			else
			{
				$path = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $row->category_back_full_image;

				if (JFile::exists($path))
				{
					JFile::delete($path);
				}

				$row->category_back_full_image = '';
			}
		}

		// Check the data.
		if (!$row->check())
		{
			$this->setError($row->getError());

			return false;
		}

		if (!$row->store())
		{
			return false;
		}

		$dispatcher->trigger('onAfterCategorySave', array(&$row));

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
		RedshopHelperExtrafields::extraFieldSave($data, 2, $row->id);

		// Start Accessory Product
		// @TODO Need to add an better solution.
		$this->productAccessoriesStore($row->id);

		return true;
	}

	/**
	 * Method to copy.
	 *
	 * @param   array $cid Category id list.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public function copy($cid = array())
	{
		if (!count($cid))
		{
			return false;
		}

		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_category'))
			->where($db->qn('id') . ' IN (' . implode(',', $cid) . ')');

		$copyData = $db->setQuery($query)->loadObjectList();

		for ($i = 0, $in = count($copyData); $i < $in; $i++)
		{
			$post                         = array();
			$post['id']                   = 0;
			$post['name']                 = $this->renameToUniqueValue('name', $copyData[$i]->name, '', 'Category');
			$post['short_description']    = $copyData[$i]->short_description;
			$post['description']          = $copyData[$i]->description;
			$post['template']             = $copyData[$i]->template;
			$post['more_template']        = $copyData[$i]->more_template;
			$post['products_per_page']    = $copyData[$i]->products_per_page;
			$post['metakey']              = $copyData[$i]->metakey;
			$post['metadesc']             = $copyData[$i]->metadesc;
			$post['metalanguage_setting'] = $copyData[$i]->metalanguage_setting;
			$post['metarobot_info']       = $copyData[$i]->metarobot_info;
			$post['pagetitle']            = $copyData[$i]->pagetitle;
			$post['pageheading']          = $copyData[$i]->pageheading;
			$post['sef_url']              = $copyData[$i]->sef_url;
			$post['published']            = $copyData[$i]->published;
			$post['category_pdate']       = date("Y-m-d h:i:s");
			$post['ordering']             = count($copyData) + $i + 1;
			$post['parent_id']            = $copyData[$i]->parent_id;
			$post['level']                = $copyData[$i]->level;

			if (!empty($copyData[$i]->category_thumb_image))
			{
				$post['category_thumb_image'] = $this->renameToUniqueValue(
					'category_thumb_image', $copyData[$i]->category_thumb_image, 'dash', 'Category'
				);
			}

			if (!empty($copyData[$i]->category_full_image))
			{
				$post['category_full_image'] = $this->renameToUniqueValue(
					'category_full_image', $copyData[$i]->category_full_image, 'dash', 'Category'
				);

				$src  = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $copyData[$i]->category_full_image;
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $post['category_full_image'];

				if (JFile::exists($src))
				{
					JFile::copy($src, $dest);
				}
			}

			$this->save($post);
		}

		return true;
	}

	/**
	 * Process for store product accessories
	 *
	 * @param   integer $categoryId ID of category
	 *
	 * @since   2.0.6
	 *
	 * @return  void
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

		if (empty($productList))
		{
			return true;
		}

		foreach ($productList as $product)
		{
			$productId = $product->id;

			foreach ($productAccessories as $productAccessory)
			{
				$accessoryId = RedshopHelperAccessory::checkAccessoryExists($productId, $productAccessory['child_product_id']);

				if ($productId == $productAccessory['child_product_id'])
				{
					continue;
				}

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
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}
	}
}
