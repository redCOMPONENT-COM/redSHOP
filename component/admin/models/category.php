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
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
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
	 * @param   array    $data      Data for the form. [optional]
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
	 *
	 * @return  mixed  A JForm object on success, false on failure
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
				'control' => 'jform',
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
		$app = JFactory::getApplication();
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
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItem($pk = null)
	{
		$item  = parent::getItem($pk);

		if (!empty($item->id))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_category'))
				->where($db->qn('id') . ' = ' . $db->q((int) $item->id));
			$data = $db->setQuery($query)->loadObject();

			$data->more_template = explode(',', $data->more_template);

			return $data;
		}
		else
		{
			$item->template = Redshop::getConfig()->get('CATEGORY_TEMPLATE', "");
			$item->products_per_page = 5;

			return $item;
		}
	}

	/**
	 * Method to get extra fields to category.
	 *
	 * @param   integer  $item  The object category values.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since   2.0.6
	 */
	public function getExtraFields($item)
	{
		$redshopTemplate = Redtemplate::getInstance();
		$template_desc   = $redshopTemplate->getTemplate('category', $item->template, '', true);
		$template        = $template_desc[0]->template_desc;
		$regex           = '/{rs_[\w]{1,}\}/';
		preg_match_all($regex, $template, $matches);
		$listField = array();

		if (count($matches[0]) > 0)
		{
			$dbname = implode(',', $matches[0]);
			$dbname = str_replace(array('{', '}'), '', $dbname);
			$field = extra_field::getInstance();
			$listField[] = $field->list_all_field(2, $item->category_id, $dbname);
		}

		return implode('', $listField);
	}

	/**
	 * Method to store category.
	 *
	 * @param   object  $data  The object category data.
	 *
	 * @return  boolen
	 *
	 * @since   2.0.6
	 */
	public function save($data)
	{
		$db  = $this->getDbo();
		$row = $this->getTable();
		$pk  = (!empty($data['id'])) ? $data['id'] : (int) $this->getState($this->getName() . '.id');

		// Set the new parent id if parent id not matched OR while New/Save as Copy .
		if ($row->parent_id != $data['parent_id'] || $data['id'] == 0)
		{
			$row->setLocation($data['parent_id'], 'last-child');
		}

		// Load the row if saving an existing record.
		if ($pk > 0)
		{
			$row->load($pk);
		}

		if (!$row->bind($data))
		{
			$this->setError($row->getError());

			return false;
		}

		$fileName = "";

		if (isset($data['image_delete']))
		{
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_image']);
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_image']);

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

		if (!empty($data['category_full_image']))
		{
			// Make the filename unique
			$fileName = RedShopHelperImages::cleanFileName(basename($data['category_full_image']));

			$row->category_full_image  = $fileName;
			$row->category_thumb_image = $fileName;

			$src = JPATH_ROOT . '/' . $data['category_full_image'];

			// Specific path of the file
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $fileName;

			JFile::copy($src, $dest);
			unlink($src);
		}
		else
		{
			if (!empty($data['category_image']))
			{
				$imageSplit = explode('/', $data['category_image']);

				// Make the filename unique
				$fileName = RedShopHelperImages::cleanFileName($imageSplit[count($imageSplit) - 1]);
				$row->category_full_image  = $fileName;
				$row->category_thumb_image = $fileName;

				$src = JPATH_ROOT . '/' . $data['category_image'];
				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $fileName;

				JFile::copy($src, $dest);
			}
		}

		if (isset($data['image_back_delete']))
		{
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_back_image']);
			unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_back_image']);

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

		if (!empty($data['category_back_full_image']))
		{
			// Make the filename unique
			$fileName = RedShopHelperImages::cleanFileName(basename($data['category_back_full_image']));
			$row->category_back_full_image = $fileName;

			$src = JPATH_ROOT . '/' . $data['category_back_full_image'];

			// Specific path of the file
			$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $fileName;

			JFile::copy($src, $dest);
			unlink($src);
		}

		// Check the data.
		if (!$row->check())
		{
			$this->setError($row->getError());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($row->getError());

			return false;
		}

		if (isset($row->id))
		{
			$this->setState($this->getName() . '.id', $row->id);
		}

		// Sheking for the image at the updation time
		if (!empty($data['id']) && !empty($data['category_full_image']))
		{
			@unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $data['old_image']);
			@unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['old_image']);
		}

		// Extra Field Data Saved
		$field = extra_field::getInstance();
		$field->extra_field_save($data, 2, $row->id);

		// Start Accessory Product
		if (!empty($data['product_accessory']) && is_array($data['product_accessory']))
		{
			$data['product_accessory'] = array_merge(array(), $data['product_accessory']);

			$productCategory = new product_category;
			$productList = $productCategory->getCategoryProductList($row->id);

			for ($p = 0, $pn = count($productList); $p < $pn; $p++)
			{
				$productId = $productList[$p]->id;

				for ($a = 0; $a < count($data['product_accessory']); $a++)
				{
					$acc = $data['product_accessory'][$a];
					$accessoryId = $productCategory->CheckAccessoryExists($productId, $acc['child_product_id']);

					if ($productId != $acc['child_product_id'])
					{
						$accDetail = JTable::getInstance('Accessory_detail', 'Table');

						$accDetail->accessory_id        = $accessoryId;
						$accDetail->category_id         = $row->id;
						$accDetail->product_id          = $productId;
						$accDetail->child_product_id    = $acc['child_product_id'];
						$accDetail->accessory_price     = $acc['accessory_price'];
						$accDetail->oprand              = $acc['oprand'];
						$accDetail->ordering            = $acc['ordering'];
						$accDetail->setdefault_selected = (isset($acc['setdefault_selected']) && $acc['setdefault_selected'] == 1) ? 1 : 0;

						if (!$accDetail->store())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}
				}
			}
		}

		// End Accessory Product insert
		return $row;
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  $pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   2.0.6
	 */
	public function delete(&$pks)
	{
		$noError = true;
		$cid = $pks;
		$db = $this->getDbo();

		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$query = $db->getQuery(true)
				->select('COUNT(*) AS ctotal')
				->select($db->qn('name'))
				->from($db->qn('#__redshop_category'))
				->where($db->qn('parent_id') . ' = ' . $db->q((int) $cid[$i]));

			$childs = $db->setQuery($query)->loadObject();

			if ($childs->ctotal > 0)
			{
				$noError = false;
				$errorMSG = JText::sprintf('COM_REDSHOP_CATEGORY_PARENT_ERROR_MSG', $childs->name, $cid[$i]);
				$this->setError($errorMSG);
				break;
			}

			$productCount = RedshopEntityCategory::getInstance($cid[$i])->productCount();

			if ($productCount > 0)
			{
				$noError = false;
				$errorMSG = JText::sprintf('COM_REDSHOP_CATEGORY_EXIST_PRODUCT', $cid[$i]);
				$this->setError($errorMSG);
				break;
			}

			$query = $db->getQuery(true)
				->select($db->qn('category_thumb_image'))
				->select($db->qn('category_full_image'))
				->from($db->qn('#__redshop_category'))
				->where($db->qn('id') . ' = ' . $db->q((int) $cid[$i]));

			$catImages = $db->setQuery($query)->loadObject();

			$catThumbImage = $catImages->category_thumb_image;
			$catFullImage  = $catImages->category_full_image;

			$thumbPath = REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $catThumbImage;
			$fullImagePath = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $catFullImage;

			if (file_exists($thumbPath))
			{
				@unlink($thumbPath);
			}

			if (file_exists($fullImagePath))
			{
				@unlink($fullImagePath);
			}

			$conditions = array(
				$db->qn('id') . ' = ' . $db->q((int) $cid[$i])
			);

			$conditionProduct = array(
				$db->qn('category_id') . ' = ' . $db->q((int) $cid[$i])
			);

			$query = $db->getQuery(true)
				->delete($db->qn('#__redshop_product_category_xref'))
				->where($conditionProduct);
			$db->setQuery($query)->execute();

			$query = $db->getQuery(true)
				->delete($db->qn('#__redshop_category'))
				->where($conditions);
			$db->setQuery($query)->execute();
		}

		return $noError;
	}

	/**
	 * Method to copy.
	 *
	 * @param   array  $cid  category id list.
	 *
	 * @return  boolen
	 *
	 * @since   2.0.6
	 */
	public function copy($cid = array())
	{
		if (!count($cid))
		{
			return false;
		}

		$db = $this->getDBO();
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
				$post['category_thumb_image'] = $this->renameToUniqueValue('category_thumb_image', $copyData[$i]->category_thumb_image, 'dash', 'Category');
			}

			if (!empty($copyData[$i]->category_full_image))
			{
				$post['category_full_image']  = $this->renameToUniqueValue('category_full_image', $copyData[$i]->category_full_image, 'dash', 'Category');
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
}
