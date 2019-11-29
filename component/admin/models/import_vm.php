<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Import VirtueMart model
 *
 * @since  2.1.0
 */
class RedshopModelImport_Vm extends RedshopModel
{
	/**
	 * @var string
	 */
	protected $logName = 'vm_sync.name';

	/**
	 * Method for count Virtuemart's Products
	 *
	 * @return  integer
	 *
	 * @since   2.1.0s
	 */
	public function countProducts()
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('virtuemart_product_id') . ')')
			->from($db->qn('#__virtuemart_products'));

		return (int) $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for count Virtuemart's Categories
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public function countCategories()
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('virtuemart_category_id') . ')')
			->from($db->qn('#__virtuemart_categories'));

		return (int) $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for count Virtuemart's Shopper Groups
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public function countShopperGroups()
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('virtuemart_shoppergroup_id') . ')')
			->from($db->qn('#__virtuemart_shoppergroups'));

		return (int) $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for count Virtuemart's Users
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public function countUsers()
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('virtuemart_userinfo_id') . ')')
			->from($db->qn('#__virtuemart_userinfos'));

		return (int) $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for count Virtuemart's Order Statuses
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public function countOrderStatuses()
	{
		$db    = $this->_db;
		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('virtuemart_orderstate_id') . ')')
			->from($db->qn('#__virtuemart_orderstates'));

		return (int) $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for count Virtuemart's Manufacturers
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public function countManufacturers()
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('virtuemart_manufacturer_id') . ')')
			->from($db->qn('#__virtuemart_manufacturers'));

		return (int) $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for count Virtuemart's Orders
	 *
	 * @return  integer
	 *
	 * @since   2.1.0
	 */
	public function countOrders()
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('COUNT(' . $db->qn('virtuemart_order_id') . ')')
			->from($db->qn('#__virtuemart_orders'));

		return (int) $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for sync category
	 *
	 * @param   int $index Index
	 *
	 * @return  bool
	 *
	 * @since   2.1.0
	 */
	public function syncCategory($index)
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('vmc.*')
			->select($db->qn('vm.category_name'))
			->select($db->qn('vm.category_description'))
			->select($db->qn('vm.metadesc'))
			->select($db->qn('vm.metakey'))
			->select($db->qn('m.file_url', 'file_name'))
			->select($db->qn('m.file_mimetype', 'file_mimetype'))
			->select($db->qn('vmc2.category_parent_id', 'parent_id'))
			->from($db->qn('#__virtuemart_categories', 'vmc'))
			->leftJoin(
				$db->qn('#__virtuemart_categories_en_gb', 'vm') . ' ON '
				. $db->qn('vmc.virtuemart_category_id') . ' = ' . $db->qn('vm.virtuemart_category_id')
			)
			->leftJoin(
				$db->qn('#__virtuemart_category_medias', 'cm') . ' ON '
				. $db->qn('vmc.virtuemart_category_id') . ' = ' . $db->qn('cm.virtuemart_category_id')
			)
			->leftJoin(
				$db->qn('#__virtuemart_medias', 'm') . ' ON '
				. $db->qn('cm.virtuemart_media_id') . ' = ' . $db->qn('m.virtuemart_media_id')
			)
			->leftJoin(
				$db->qn('#__virtuemart_category_categories', 'vmc2') . ' ON '
				. $db->qn('vmc.virtuemart_category_id') . ' = ' . $db->qn('vmc2.category_child_id')
			)
			->order($db->qn('parent_id') . ' ASC,' . $db->qn('vmc.virtuemart_category_id') . ' ASC');

		$db->setQuery($query, $index, 1);

		$categoryVM = $db->setQuery($query)->loadObject();

		if (empty($categoryVM))
		{
			$this->setState($this->logName, null);

			return false;
		}

		$this->setState($this->logName, $categoryVM->category_name);

		// Load redshop category
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('name') . ' = ' . $db->quote((string) $categoryVM->category_name));
		$rsCategoryId = $db->setQuery($query)->loadResult();

		/** @var RedshopTableCategory $table */
		$table = RedshopTable::getInstance('Category', 'RedshopTable');

		if ($rsCategoryId)
		{
			$table->load($rsCategoryId);
		}

		$table->name                = addslashes($categoryVM->category_name);
		$table->description         = $categoryVM->category_description;
		$table->category_full_image = !empty($categoryVM->file_name) ? basename($categoryVM->file_name) : null;
		$table->published           = $categoryVM->published;
		$table->category_pdate      = $categoryVM->created_on;
		$table->products_per_page   = $categoryVM->products_per_row;
		$table->metakey             = $categoryVM->metakey;
		$table->metadesc            = $categoryVM->metadesc;
		$table->ordering            = $categoryVM->ordering;
		$table->template            = Redshop::getConfig()->get('CATEGORY_TEMPLATE');

		$parentId = $categoryVM->parent_id ? $this->getCategoryIdSynced($categoryVM->parent_id) : $table->getRootId();

		$table->setLocation((int) $parentId, 'last-child');

		if (!$table->store())
		{
			return false;
		}

		// Copy image
		if (!empty($categoryVM->file_name))
		{
			JFile::copy(JPATH_ROOT . '/' . $categoryVM->file_name, REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . basename($categoryVM->file_name));
		}

		return true;
	}

	/**
	 * Method for get category id of given virtuemart category id
	 *
	 * @param   int $vmCategoryId Virtuemart category ID
	 *
	 * @return  int                 Redshop category id
	 *
	 * @since   2.1.0
	 */
	protected function getCategoryIdSynced($vmCategoryId)
	{
		if (!$vmCategoryId)
		{
			return 0;
		}

		$db    = $this->_db;
		$query = $db->getQuery(true)
			->select($db->qn('c.id'))
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__virtuemart_categories_en_gb', 'vmc') . ' ON ' . $db->qn('vmc.category_name') . ' = ' . $db->qn('c.name'))
			->where($db->qn('vmc.virtuemart_category_id') . ' = ' . $db->quote($vmCategoryId));

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for sync shopper group
	 *
	 * @param   int $index Index
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function syncShopperGroup($index)
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('vms.*')
			->from($db->qn('#__virtuemart_shoppergroups', 'vms'))
			->order($db->qn('vms.virtuemart_shoppergroup_id') . ' ASC');

		$db->setQuery($query, $index, 1);

		$shopperGroupVM = $db->setQuery($query)->loadObject();

		if (empty($shopperGroupVM))
		{
			$this->setState($this->logName, null);

			return false;
		}

		if ($shopperGroupVM->shopper_group_name == 'COM_VIRTUEMART_SHOPPERGROUP_DEFAULT')
		{
			$shopperGroupName = JText::_('COM_REDSHOP_IMPORT_VM_SHOPPERGROUP_DEFAULT');
		}
		elseif ($shopperGroupVM->shopper_group_name == 'COM_VIRTUEMART_SHOPPERGROUP_GUEST')
		{
			$shopperGroupName = JText::_('COM_REDSHOP_IMPORT_VM_SHOPPERGROUP_GUEST');
		}
		else
		{
			$shopperGroupName = addslashes($shopperGroupVM->shopper_group_name);
		}

		$this->setState($this->logName, $shopperGroupName);

		if ($shopperGroupVM->shopper_group_desc == 'COM_VIRTUEMART_SHOPPERGROUP_DEFAULT_TIP')
		{
			$shopperGroupDescription = JText::_('COM_REDSHOP_IMPORT_VM_SHOPPERGROUP_DEFAULT_TIP');
		}
		elseif ($shopperGroupVM->shopper_group_desc == 'COM_VIRTUEMART_SHOPPERGROUP_GUEST_TIP')
		{
			$shopperGroupDescription = JText::_('COM_REDSHOP_IMPORT_VM_SHOPPERGROUP_GUEST_TIP');
		}
		else
		{
			$shopperGroupDescription = $shopperGroupVM->shopper_group_desc;
		}

		// Load redshop manufacturer
		$query->clear()
			->select($db->qn('shopper_group_id'))
			->from($db->qn('#__redshop_shopper_group'))
			->where($db->qn('shopper_group_name') . ' = ' . $db->quote($shopperGroupName));
		$rsShopperGroupId = $db->setQuery($query)->loadResult();

		/** @var \TableShopper_Group_Detail $table */
		$table = JTable::getInstance('Shopper_Group_Detail', 'Table');

		if ($rsShopperGroupId)
		{
			$table->load($rsShopperGroupId);
		}

		$table->shopper_group_name = $shopperGroupName;
		$table->shopper_group_desc = $shopperGroupDescription;
		$table->published          = $shopperGroupVM->published;

		return $table->store();
	}

	/**
	 * Method for sync shopper group
	 *
	 * @param   int $index Index
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function syncUser($index)
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('u.*')
			->select($db->qn('ref.virtuemart_shoppergroup_id'))
			->select($db->qn('c.country_3_code'))
			->select($db->qn('s.state_3_code'))
			->from($db->qn('#__virtuemart_userinfos', 'u'))
			->leftJoin(
				$db->qn('#__virtuemart_vmuser_shoppergroups', 'ref') . ' ON '
				. $db->qn('u.virtuemart_user_id') . ' = ' . $db->qn('ref.virtuemart_user_id')
			)
			->leftJoin(
				$db->qn('#__virtuemart_countries', 'c') . ' ON '
				. $db->qn('c.virtuemart_country_id') . ' = ' . $db->qn('u.virtuemart_country_id')
			)
			->leftJoin(
				$db->qn('#__virtuemart_states', 's') . ' ON '
				. $db->qn('s.virtuemart_state_id') . ' = ' . $db->qn('u.virtuemart_state_id')
			)
			->order($db->qn('u.virtuemart_userinfo_id') . ' ASC')
			->group($db->qn('u.virtuemart_user_id'));

		$db->setQuery($query, $index, 1);

		$userVM = $db->setQuery($query)->loadObject();

		if (empty($userVM))
		{
			$this->setState($this->logName, null);

			return false;
		}

		$this->setState($this->logName, $userVM->first_name . ' ' . $userVM->last_name);

		$userTable = JTable::getInstance('user_detail', 'Table');
		$isPrivate = (boolean) $userVM->address_type == 'BT';

		if ($isPrivate)
		{
			$redshopUser = RedshopHelperOrder::getBillingAddress($userVM->virtuemart_user_id);

			if ($redshopUser)
			{
				$userTable->load($redshopUser->users_info_id);

				$userTable->firstname    = $userVM->first_name;
				$userTable->lastname     = $userVM->last_name;
				$userTable->company_name = $userVM->company;
				$userTable->address      = $userVM->address_1;
				$userTable->city         = $userVM->city;
				$userTable->country_code = $userVM->country_3_code;
				$userTable->state_code   = $userVM->state_3_code;
				$userTable->zipcode      = $userVM->zip;
				$userTable->phone        = $userVM->phone_1;
			}
			else
			{
				$user = JFactory::getUser($userVM->virtuemart_user_id);

				$userTable->reset();

				$userTable->user_email   = $user->email;
				$userTable->user_id      = $user->id;
				$userTable->firstname    = $userVM->first_name;
				$userTable->lastname     = $userVM->last_name;
				$userTable->company_name = $userVM->company;
				$userTable->address      = $userVM->address_1;
				$userTable->city         = $userVM->city;
				$userTable->country_code = $userVM->country_3_code;
				$userTable->state_code   = $userVM->state_3_code;
				$userTable->zipcode      = $userVM->zip;
				$userTable->phone        = $userVM->phone_1;
				$userTable->address_type = $userVM->address_type;
			}
		}
		else
		{
			$user = JFactory::getUser($userVM->virtuemart_user_id);

			$userTable->reset();

			$userTable->user_email   = $user->email;
			$userTable->user_id      = $user->id;
			$userTable->firstname    = $userVM->first_name;
			$userTable->lastname     = $userVM->last_name;
			$userTable->company_name = $userVM->company;
			$userTable->address      = $userVM->address_1;
			$userTable->city         = $userVM->city;
			$userTable->country_code = $userVM->country_3_code;
			$userTable->state_code   = $userVM->state_3_code;
			$userTable->zipcode      = $userVM->zip;
			$userTable->phone        = $userVM->phone_1;
			$userTable->address_type = $userVM->address_type;
		}

		$useDefault = true;

		if (!empty($userVM->virtuemart_shoppergroup_id))
		{
			$groupName = RedshopHelperVirtuemart::getVirtuemartShopperGroups($userVM->virtuemart_shoppergroup_id);

			if (!empty($groupName))
			{
				$userTable->shopper_group_id = RedshopHelperVirtuemart::getRedshopShopperGroups($groupName);

				$useDefault = false;
			}
		}

		if ($useDefault)
		{
			$userTable->shopper_group_id = $isPrivate ?
				Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE') : Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_COMPANY');
		}

		return $userTable->store();
	}

	/**
	 * Method for sync shopper group
	 *
	 * @param   int $index Index
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function syncOrderStatus($index)
	{
		$db    = $this->_db;
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__virtuemart_orderstates'))
			->order($db->qn('virtuemart_orderstate_id'));

		$db->setQuery($query, $index, 1);

		$orderStatusVM = $db->setQuery($query)->loadObject();

		if (empty($orderStatusVM))
		{
			$this->setState($this->logName, null);

			return true;
		}

		JFactory::getLanguage()->load('com_virtuemart_orders', JPATH_SITE . '/components/com_virtuemart');

		$this->setState($this->logName, JText::_($orderStatusVM->order_status_name));

		/** @var \RedshopTableOrder_Status $table */
		$table = JTable::getInstance('Order_Status', 'RedshopTable');

		if (!$table->load(array('order_status_code' => $orderStatusVM->order_status_code)))
		{
			$table->order_status_id = 0;
		}

		$table->order_status_name = JText::_($orderStatusVM->order_status_name);
		$table->order_status_code = $orderStatusVM->order_status_code;
		$table->published         = (int) $orderStatusVM->published;

		return $table->store();
	}

	/**
	 * Method for sync manufacturer
	 *
	 * @param   int $index Index
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function syncManufacturer($index)
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('vmm.*')
			->select($db->qn('vmd.mf_name'))
			->select($db->qn('vmd.mf_email'))
			->select($db->qn('vmd.mf_desc'))
			->select($db->qn('vmd.mf_url'))
			->select($db->qn('vmd.metadesc'))
			->select($db->qn('vmd.metakey'))
			->select($db->qn('m.file_url', 'file_name'))
			->select($db->qn('m.file_mimetype', 'file_mime'))
			->select($db->qn('m.file_description', 'file_description'))
			->select($db->qn('m.published', 'file_published'))
			->from($db->qn('#__virtuemart_manufacturers', 'vmm'))
			->leftJoin(
				$db->qn('#__virtuemart_manufacturers_en_gb', 'vmd')
				. ' ON ' . $db->qn('vmm.virtuemart_manufacturer_id') . ' = ' . $db->qn('vmd.virtuemart_manufacturer_id')
			)
			->leftJoin(
				$db->qn('#__virtuemart_manufacturer_medias', 'cm')
				. ' ON ' . $db->qn('vmm.virtuemart_manufacturer_id') . ' = ' . $db->qn('cm.virtuemart_manufacturer_id')
			)
			->leftJoin($db->qn('#__virtuemart_medias', 'm') . ' ON ' . $db->qn('cm.virtuemart_media_id') . ' = ' . $db->qn('m.virtuemart_media_id'))
			->order($db->qn('vmm.virtuemart_manufacturer_id') . ' ASC');

		$db->setQuery($query, $index, 1);

		$manufacturerVM = $db->setQuery($query)->loadObject();

		if (empty($manufacturerVM))
		{
			$this->setState($this->logName, null);

			return false;
		}

		$this->setState($this->logName, $manufacturerVM->mf_name);

		// Load redshop manufacturer
		$query->clear()
			->select($db->qn('manufacturer_id'))
			->from($db->qn('#__redshop_manufacturer'))
			->where($db->qn('manufacturer_name') . ' = ' . $db->quote((string) $manufacturerVM->mf_name));
		$rsManufacturerId = $db->setQuery($query)->loadResult();

		/** @var TableManufacturer_Detail $table */
		$table = JTable::getInstance('Manufacturer_Detail', 'Table');

		if ($rsManufacturerId)
		{
			$table->load($rsManufacturerId);
		}

		$table->manufacturer_name  = addslashes($manufacturerVM->mf_name);
		$table->manufacturer_desc  = $manufacturerVM->mf_desc;
		$table->manufacturer_email = $manufacturerVM->mf_email;
		$table->published          = $manufacturerVM->published;
		$table->metakey            = $manufacturerVM->metakey;
		$table->metadesc           = $manufacturerVM->metadesc;
		$table->manufacturer_url   = $manufacturerVM->mf_url;
		$table->template_id        = Redshop::getConfig()->get('MANUFACTURER_TEMPLATE');

		if (!$table->store())
		{
			return false;
		}

		// Copy image
		if (!empty($manufacturerVM->file_name))
		{
			$mediaFile = REDSHOP_FRONT_IMAGES_RELPATH . 'manufacturer/' . basename($manufacturerVM->file_name);

			if (!JFile::exists($mediaFile))
			{
				JFile::copy(JPATH_ROOT . '/' . $manufacturerVM->file_name, $mediaFile);
			}

			/** @var Tablemedia_detail $mediaTable */
			$mediaTable = JTable::getInstance('Media_Detail', 'Table');

			if (!$mediaTable->load(
				array('media_section' => 'manufacturer', 'media_type' => 'images', 'section_id' => $table->manufacturer_id)
			))
			{
				$mediaTable->media_name           = basename($manufacturerVM->file_name);
				$mediaTable->media_alternate_text = $manufacturerVM->file_description;
				$mediaTable->media_section        = 'manufacturer';
				$mediaTable->media_type           = 'images';
				$mediaTable->media_mimetype       = $manufacturerVM->file_mime;
				$mediaTable->published            = $manufacturerVM->file_published;
				$mediaTable->section_id           = $table->manufacturer_id;

				$mediaTable->store();
			}
		}

		return true;
	}

	public function getProductIdByNumber($product_number)
	{

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName(array('product_id')))
			->from($db->quoteName('#__redshop_product'))
			->where($db->quoteName('product_number') . ' = ' . $db->quote($product_number));

		$db->setQuery($query);

		$product_id = $db->loadResult();

		return $product_id;
	}

	/**
	 * Get Extra Field Names
	 *
	 * @param   array $keyProducts Array key products
	 *
	 * @return  array
	 */
	public function getExtraFieldNames($keyProducts)
	{
		$extraFieldNames = array();

		if (is_array($keyProducts))
		{
			$pattern = '/rs_/';

			foreach ($keyProducts as $key => $value)
			{
				if (preg_match($pattern, $key))
				{
					$extraFieldNames[] = $key;
				}
			}
		}

		return $extraFieldNames;
	}

	/**
	 * Method for sync product
	 *
	 * @param   int $index Index
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function syncProduct($index)
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('vmp.*')
			->select($db->qn('rp.product_id', 'redshop_product_id'))
			->select($db->qn('vmprice.product_price', 'product_price'))
			->select($db->qn('vmdata.product_s_desc', 'product_s_desc'))
			->select($db->qn('vmdata.product_desc', 'product_desc'))
			->select($db->qn('vmdata.product_name', 'product_name'))
			->select($db->qn('vmdata.metadesc', 'metadesc'))
			->select($db->qn('vmdata.metakey', 'metakey'))
			->select($db->qn('vmdata.customtitle', 'customtitle'))
			->select($db->qn('vmdata.slug', 'product_slug'))
			->select($db->qn('vmmanu.virtuemart_manufacturer_id', 'virtuemart_manufacturer_id'))
			->from($db->qn('#__virtuemart_products', 'vmp'))
			->leftJoin(
				$db->qn('#__virtuemart_products_en_gb', 'vmdata')
				. ' ON ' . $db->qn('vmp.virtuemart_product_id') . ' = ' . $db->qn('vmdata.virtuemart_product_id')
			)
			->leftJoin(
				$db->qn('#__redshop_product', 'rp') . ' ON '
				. '((' . $db->qn('vmp.product_sku') . ' IS NOT NULL'
				. ' OR ' . $db->qn('vmp.product_sku') . ' != ' . $db->quote('') . ') AND '
				. $db->qn('rp.product_number') . ' = ' . $db->qn('vmp.product_sku') . ')'
				. ' OR ((' . $db->qn('vmp.product_sku') . ' IS NULL OR '
				. $db->qn('vmp.product_sku') . ' = ' . $db->quote('') . ') AND '
				. $db->qn('rp.product_number') . ' = ' . $db->qn('vmdata.slug') . ')'
			)
			->leftJoin(
				$db->qn('#__virtuemart_product_prices', 'vmprice')
				. ' ON ' . $db->qn('vmp.virtuemart_product_id') . ' = ' . $db->qn('vmprice.virtuemart_product_id')
				. ' AND ' . $db->qn('vmprice.price_quantity_start') . ' = ' . $db->quote(0)
			)
			->leftJoin(
				$db->qn('#__virtuemart_product_manufacturers', 'vmmanu')
				. ' ON ' . $db->qn('vmp.virtuemart_product_id') . ' = ' . $db->qn('vmmanu.virtuemart_product_id')
			)
			->order($db->qn('vmp.product_parent_id') . ' ASC, ' . $db->qn('vmp.virtuemart_product_id') . ' ASC');

		$db->setQuery($query, $index, 1);

		$productVM = $db->setQuery($query)->loadObject();

		if (empty($productVM))
		{
			$this->setState($this->logName, null);

			return false;
		}

		$this->setState($this->logName, $productVM->product_name);

		$productInStock = (int) $productVM->product_in_stock;

		/** @var TableProduct_Detail $table */
		$table = JTable::getInstance('Product_Detail', 'Table');

		if ($productVM->redshop_product_id)
		{
			$table->load($productVM->redshop_product_id);
		}

		// Product length
		switch ($productVM->product_weight_uom)
		{
			// Kilograms to grams
			case 'KG':
				$weight = (float) $productVM->product_weight * 1000;
				break;

			// Milligrams to grams
			case 'MG':
				$weight = 1000 / (float) $productVM->product_weight;
				break;

			// Pounds to grams
			case 'LB':
				$weight = (float) $productVM->product_weight * 453.59237;
				break;

			// Ounces to grams
			case 'OZ':
				$weight = (float) $productVM->product_weight * 28.3495231;
				break;

			default:
				$weight = (float) $productVM->product_weight;
		}

		$table->weight = $weight;

		// Product dimensions
		switch ($productVM->product_lwh_uom)
		{
			// Meters to centimeters
			case 'M':
				$length = (float) $productVM->product_length * 100;
				$height = (float) $productVM->product_height * 100;
				$width  = (float) $productVM->product_width * 100;
				break;

			// Millimetres to centimeters
			case 'MM':
				$length = (float) $productVM->product_length * 0.1;
				$height = (float) $productVM->product_height * 0.1;
				$width  = (float) $productVM->product_width * 0.1;
				break;

			// Yards to centimeters
			case 'YD':
				$length = (float) $productVM->product_length * 91.44;
				$height = (float) $productVM->product_height * 91.44;
				$width  = (float) $productVM->product_width * 91.44;
				break;

			// Foots to centimeters
			case 'FT':
				$length = (float) $productVM->product_length * 30.48;
				$height = (float) $productVM->product_height * 30.48;
				$width  = (float) $productVM->product_width * 30.48;
				break;

			// Inches to centimeters
			case 'IN':
				$length = (float) $productVM->product_length * 2.54;
				$height = (float) $productVM->product_height * 2.54;
				$width  = (float) $productVM->product_width * 2.54;
				break;

			default:
				$length = (float) $productVM->product_length;
				$height = (float) $productVM->product_height;
				$width  = (float) $productVM->product_width;
				break;
		}

		// Product params convert
		if ($productVM->product_params)
		{
			$vmProductParams = explode("|", $productVM->product_params);

			foreach ($vmProductParams as $vmProductParam)
			{
				$param = explode('=', str_replace('"', '', $vmProductParam));

				if (count($param) != 2)
				{
					continue;
				}

				if ($param[0] == 'min_order_level')
				{
					$table->min_order_product_quantity = (int) $param[1];
				}
				elseif ($param[0] == 'max_order_level')
				{
					$table->max_order_product_quantity = (int) $param[1];
				}
			}
		}

		$table->product_number   = empty($productVM->product_sku) ? $productVM->product_slug : $productVM->product_sku;
		$table->product_length   = $length;
		$table->product_height   = $height;
		$table->product_width    = $width;
		$table->sef_url          = $productVM->product_url;
		$table->product_special  = (int) $productVM->product_special;
		$table->expired          = (int) $productVM->product_discontinued;
		$table->product_on_sale  = (int) $productVM->product_sales;
		$table->visited          = (int) $productVM->hits;
		$table->metarobot_info   = $productVM->metarobot;
		$table->published        = (int) $productVM->published;
		$table->product_s_desc   = $productVM->product_s_desc;
		$table->product_desc     = $productVM->product_desc;
		$table->product_name     = $productVM->product_name;
		$table->metadesc         = $productVM->metadesc;
		$table->metakey          = $productVM->metakey;
		$table->pagetitle        = $productVM->customtitle;
		$table->product_price    = (float) $productVM->product_price;
		$table->product_template = Redshop::getConfig()->get('PRODUCT_TEMPLATE');

		// Product manufacturer
		if ($productVM->virtuemart_manufacturer_id)
		{
			$query->clear()
				->select($db->qn('manufacturer_id'))
				->from($db->qn('#__redshop_manufacturer', 'm'))
				->leftJoin(
					$db->qn('#__virtuemart_manufacturers_en_gb', 'vm') . ' ON '
					. $db->qn('vm.mf_name') . ' = ' . $db->qn('m.manufacturer_name')
				)
				->where($db->qn('vm.virtuemart_manufacturer_id') . ' = ' . $productVM->virtuemart_manufacturer_id);
			$table->manufacturer_id = $db->setQuery($query)->loadResult();
		}

		// Product parent
		if ($productVM->product_parent_id)
		{
			$query->clear()
				->select($db->qn('product_id'))
				->from($db->qn('#__redshop_product', 'p'))
				->leftJoin(
					$db->qn('#__virtuemart_products', 'vm') . ' ON '
					. $db->qn('vm.product_sku') . ' = ' . $db->qn('p.product_number')
				)
				->where($db->qn('vm.virtuemart_product_id') . ' = ' . $productVM->product_parent_id);
			$table->product_parent_id = $db->setQuery($query)->loadResult();

			if (!$table->product_parent_id)
			{
				$query->clear()
					->select($db->qn('product_id'))
					->from($db->qn('#__redshop_product', 'p'))
					->leftJoin(
						$db->qn('#__virtuemart_products_en_gb', 'vm') . ' ON '
						. $db->qn('vm.slug') . ' = ' . $db->qn('p.product_number')
					)
					->where($db->qn('vm.virtuemart_product_id') . ' = ' . $productVM->product_parent_id);
				$table->product_parent_id = $db->setQuery($query)->loadResult();
			}
		}

		if (!$table->store())
		{
			$this->setError($table->getError());

			return false;
		}

		// Product price
		$query->clear()
			->delete($db->qn('#__redshop_product_price'))
			->where($db->qn('product_id') . ' = ' . $db->quote($table->product_id));
		$db->setQuery($query)->execute();

		$query->clear()
			->select('*')
			->from($db->qn('#__virtuemart_product_prices'))
			->where($db->qn('virtuemart_product_id') . ' = ' . $db->quote((string) $productVM->virtuemart_product_id));
		$prices = $db->setQuery($query)->loadObjectList();

		if (!empty($prices))
		{
			$defaultShopperGroup = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_PRIVATE');

			foreach ($prices as $price)
			{
				if ($price->virtuemart_product_id)
				{
					$shopperGroupName = RedshopHelperVirtuemart::getVirtuemartShopperGroups($price->virtuemart_shoppergroup_id);
					$shopperGroupId   = RedshopHelperVirtuemart::getRedshopShopperGroups($shopperGroupName);
				}

				$shopperGroupId = !$shopperGroupId ? $defaultShopperGroup : $shopperGroupId;
				$createdDate    = JFactory::getDate($price->created_on);
				$priceQuery     = 'INSERT IGNORE ' . $db->qn('#__redshop_product_price')
					. '(' . $db->qn('product_id') . ',' . $db->qn('product_price') . ',' . $db->qn('cdate')
					. ',' . $db->qn('price_quantity_start') . ',' . $db->qn('price_quantity_end') . ',' . $db->qn('shopper_group_id') . ')'
					. ' VALUES(' . $table->product_id . ',' . $db->quote((string) $price->product_price) . ','
					. $db->quote((string) $createdDate->format('Y-m-d')) . ','
					. $db->quote((string) $price->price_quantity_start) . ','
					. $db->quote((string) $price->price_quantity_end) . ',' . $shopperGroupId
					. ')';

				$db->setQuery($priceQuery)->execute();
			}
		}

		// Product stock
		if ($productInStock && Redshop::getConfig()->get('DEFAULT_STOCKROOM') != 0)
		{
			$stockQuery = 'INSERT IGNORE INTO ' . $db->qn('#__redshop_product_stockroom_xref')
				. '(' . $db->qn('product_id') . ',' . $db->qn('stockroom_id') . ',' . $db->qn('quantity') . ')'
				. 'VALUES (' . $table->product_id . ',' . Redshop::getConfig()->get('DEFAULT_STOCKROOM') . ',' . $productInStock . ')';
			$db->setQuery($stockQuery);

			if (!$db->execute())
			{
				$this->setError($db->getErrorMsg());
			}
		}

		// Product images
		$this->syncMedia($table->product_id, $productVM->virtuemart_product_id);

		// Remove all current product category
		$query->clear()
			->delete($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('product_id') . ' = ' . $db->quote($table->product_id));
		$db->setQuery($query)->execute();

		// Product categories
		$query->clear()
			->select($db->qn('c.id'))
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin(
				$db->qn('#__virtuemart_categories_en_gb', 'vmc') . ' ON ' . $db->qn('vmc.category_name') . ' = ' . $db->qn('c.name')
			)
			->leftJoin(
				$db->qn('#__virtuemart_product_categories', 'ref') . ' ON '
				. $db->qn('ref.virtuemart_category_id') . ' = ' . $db->qn('vmc.virtuemart_category_id')
			)
			->where($db->qn('ref.virtuemart_product_id') . ' = ' . $productVM->virtuemart_product_id);
		$categoryIds = $db->setQuery($query)->loadColumn();

		if (!empty($categoryIds))
		{
			// Insert new categories
			$query->clear()
				->insert($db->qn('#__redshop_product_category_xref'))
				->columns(array('category_id', 'product_id'));

			foreach ($categoryIds as $categoryId)
			{
				$query->values($categoryId . ',' . $table->product_id);
			}

			return $db->setQuery($query)->execute();
		}

		return true;
	}

	/**
	 * Method for sync media
	 *
	 * @param   integer  $productId    Product ID
	 * @param   integer  $vmProductId  Virtuemart ID
	 *
	 * @return  void
	 */
	protected function syncMedia($productId, $vmProductId)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('m.*')
			->from($db->qn('#__virtuemart_medias', 'm'))
			->leftJoin(
				$db->qn('#__virtuemart_product_medias', 'ref') . ' ON '
				. $db->qn('ref.virtuemart_media_id') . ' = ' . $db->qn('m.virtuemart_media_id')
			)
			->where($db->qn('ref.virtuemart_product_id') . ' = ' . $vmProductId)
			->where($db->qn('m.file_type') . ' = ' . $db->quote('product'))
			->order($db->qn('ref.virtuemart_product_id'));

		$medias = $db->setQuery($query)->loadObjectList();

		if (empty($medias))
		{
			return;
		}

		foreach ($medias as $media)
		{
			// Skip migrate image file if not exist.
			if (empty($media->file_url) || !JFile::exists(JPATH_ROOT . '/' . $media->file_url))
			{
				continue;
			}

			$fileName = basename($media->file_url);

			$mediaTable                       = JTable::getInstance('Media_Detail', 'Table');
			$mediaTable->media_id             = 0;
			$mediaTable->media_name           = $fileName;
			$mediaTable->media_section        = 'product';
			$mediaTable->media_alternate_text = $media->file_description;
			$mediaTable->section_id           = $productId;
			$mediaTable->media_type           = 'images';
			$mediaTable->media_mimetype       = $media->file_mimetype;
			$mediaTable->published            = $media->published;

			// Skip migrate image file if fail in insert media.
			if (!$mediaTable->store())
			{
				continue;
			}

			JFile::copy(JPATH_ROOT . '/' . $media->file_url, REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $fileName);
		}
	}

	/**
	 * Method for sync order
	 *
	 * @param   int $index Index
	 *
	 * @return  boolean
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function syncOrder($index)
	{
		$db = $this->_db;

		$query = $db->getQuery(true)
			->select('vmo.*')
			->select($db->qn('ro.vm_order_number', 'redshop_order_number_ref'))
			->select($db->qn('ru.users_info_id', 'redshop_user_info_id'))
			->select($db->qn('vmu.customer_note', 'customer_note'))
			->from($db->qn('#__virtuemart_orders', 'vmo'))
			->leftJoin(
				$db->qn('#__redshop_orders', 'ro')
				. ' ON ' . $db->qn('vmo.order_number') . ' = ' . $db->qn('ro.vm_order_number')
			)
			->leftJoin(
				$db->qn('#__virtuemart_order_userinfos', 'vmu')
				. ' ON ' . $db->qn('vmu.virtuemart_order_id') . ' = ' . $db->qn('vmo.virtuemart_order_id')
			)
			->leftJoin(
				$db->qn('#__redshop_users_info', 'ru')
				. ' ON ' . $db->qn('vmo.virtuemart_user_id') . ' = ' . $db->qn('ru.user_id')
				. ' AND ' . $db->qn('ru.address_type') . ' = ' . $db->quote('BT')
			)
			->order($db->qn('vmo.virtuemart_order_id'));

		$db->setQuery($query, $index, 1);

		$orderVM = $db->setQuery($query)->loadObject();

		if (empty($orderVM))
		{
			$this->setState($this->logName, null);

			return false;
		}

		$this->setState($this->logName, $orderVM->order_number);

		if (!empty($orderVM->redshop_order_number_ref))
		{
			return true;
		}

		/** @var TableOrder_Detail $orderTable */
		$orderTable = JTable::getInstance('Order_Detail', 'Table');

		$orderTable->set('order_id', 0);
		$orderTable->set('user_id', $orderVM->virtuemart_user_id);
		$orderTable->set('order_number', RedshopHelperOrder::generateOrderNumber());
		$orderTable->set('user_info_id', $orderVM->redshop_user_info_id);
		$orderTable->set('order_total', $orderVM->order_total);
		$orderTable->set('order_subtotal', $orderVM->order_subtotal);
		$orderTable->set('order_tax', $orderVM->order_tax);
		$orderTable->set('order_shipping', $orderVM->order_shipment);
		$orderTable->set('order_shipping_tax', $orderVM->order_shipment_tax);
		$orderTable->set('coupon_discount', $orderVM->coupon_discount);
		$orderTable->set('order_discount', $orderVM->order_discount);
		$orderTable->set('order_status', $orderVM->order_status);
		$orderTable->set('order_payment_status', $orderVM->order_status == 'S' ? 'Paid' : 'Unpaid');

		$createDate = JFactory::getDate($orderVM->created_on);
		$modifyDate = JFactory::getDate($orderVM->modified_on);

		$orderTable->set('cdate', $createDate->toUnix());
		$orderTable->set('mdate', $modifyDate->toUnix());
		$orderTable->set('ship_method_id', null);
		$orderTable->set('customer_note', $orderVM->customer_note);
		$orderTable->set('ip_address', $orderVM->ip_address);
		$orderTable->set('vm_order_number', $orderVM->order_number);

		if (!$orderTable->store())
		{
			return false;
		}

		// Order Items process
		$query->clear()
			->select('vmoi.*')
			->select($db->qn('rdoi.order_id', 'rdoi_order_id'))
			->select($db->qn('rdp.product_id', 'rdp_product_id'))
			->from($db->qn('#__virtuemart_order_items', 'vmoi'))
			->leftJoin($db->qn('#__redshop_order_item', 'rdoi') . ' ON ' . $db->qn('rdoi.order_id') . ' = ' . $orderTable->order_id)
			->leftJoin($db->qn('#__redshop_product', 'rdp') . ' ON ' . $db->qn('rdp.product_number') . ' = ' . $db->qn('vmoi.order_item_sku'))
			->where($db->qn('vmoi.virtuemart_order_id') . ' = ' . $orderVM->virtuemart_order_id);

		$orderItems = $db->setQuery($query)->loadObjectList();

		if (!empty($orderItems))
		{
			/** @var Tableorder_item_detail $orderItemTable */
			$orderItemTable = $this->getTable('order_item_detail');

			foreach ($orderItems as $orderItem)
			{
				$orderItemTable->reset();

				$orderItemTable->set('order_item_id', 0);
				$orderItemTable->set('order_id', $orderTable->order_id);
				$orderItemTable->set('user_info_id', $orderVM->redshop_user_info_id);
				$orderItemTable->set('product_id', $orderItem->rdp_product_id);
				$orderItemTable->set('order_item_sku', $orderItem->order_item_sku);
				$orderItemTable->set('order_item_name', $orderItem->order_item_name);
				$orderItemTable->set('product_quantity', $orderItem->product_quantity);
				$orderItemTable->set('product_item_price', $orderItem->product_item_price);
				$orderItemTable->set('product_final_price', $orderItem->product_final_price);
				$orderItemTable->set('order_item_currency', $orderItem->order_item_currency);
				$orderItemTable->set('order_status', $orderItem->order_status);
				$orderItemTable->set('cdate', JFactory::getDate($orderItem->created_on)->toUnix());
				$orderItemTable->set('mdate', JFactory::getDate($orderItem->modified_on)->toUnix());
				$orderItemTable->set('product_attribute', $orderItem->product_attribute);

				$orderItemTable->store();
			}
		}

		// @TODO: Can not migrate VirtueMart payment in Order to redSHOP.

		// Order user infor
		$query->clear()
			->select('vmou.*')
			->select($db->qn('vms.state_3_code'))
			->select($db->qn('vmc.country_3_code'))
			->select($db->qn('rsui.users_info_id', 'redshop_users_info_id'))
			->select($db->qn('rsui.shopper_group_id', 'redshop_user_shopper_group'))
			->from($db->qn('#__virtuemart_order_userinfos', 'vmou'))
			->leftJoin(
				$db->qn('#__redshop_users_info', 'rsui') . ' ON '
				. $db->qn('rsui.user_id') . ' = ' . $db->qn('vmou.virtuemart_user_id')
			)
			->leftJoin(
				$db->qn('#__virtuemart_states', 'vms') . ' ON '
				. $db->qn('vmou.virtuemart_state_id') . ' = ' . $db->qn('vms.virtuemart_state_id')
			)
			->leftJoin(
				$db->qn('#__virtuemart_countries', 'vmc') . ' ON '
				. $db->qn('vmou.virtuemart_country_id') . ' = ' . $db->qn('vmc.virtuemart_country_id')
			)
			->where($db->qn('vmou.virtuemart_order_id') . ' = ' . $orderVM->virtuemart_order_id);

		$vmOrderUser = $db->setQuery($query)->loadObject();

		if ($vmOrderUser)
		{
			/** @var Tableorder_user_detail $orderUserTable */
			$orderUserTable = JTable::getInstance('order_user_detail', 'Table');

			$orderUserTable->order_info_id         = 0;
			$orderUserTable->users_info_id         = $vmOrderUser->redshop_users_info_id;
			$orderUserTable->order_id              = $orderTable->order_id;
			$orderUserTable->user_id               = $vmOrderUser->virtuemart_user_id;
			$orderUserTable->firstname             = $vmOrderUser->first_name;
			$orderUserTable->lastname              = $vmOrderUser->last_name;
			$orderUserTable->address_type          = $vmOrderUser->address_type;
			$orderUserTable->vat_number            = '';
			$orderUserTable->tax_exempt            = 0;
			$orderUserTable->shopper_group_id      = $vmOrderUser->redshop_user_shopper_group;
			$orderUserTable->address               = $vmOrderUser->address_1;
			$orderUserTable->city                  = $vmOrderUser->city;
			$orderUserTable->zipcode               = '';
			$orderUserTable->phone                 = $vmOrderUser->phone_1;
			$orderUserTable->tax_exempt_approved   = '';
			$orderUserTable->approved              = '';
			$orderUserTable->is_company            = empty($vmOrderUser->company) ? 0 : 1;
			$orderUserTable->user_email            = $vmOrderUser->email;
			$orderUserTable->company_name          = $vmOrderUser->company;
			$orderUserTable->ean_number            = '';
			$orderUserTable->requesting_tax_exempt = '';
			$orderUserTable->thirdparty_email      = '';

			// State
			if (!empty($vmOrderUser->virtuemart_state_id))
			{
				$orderUserTable->state_code = $vmOrderUser->state_3_code;
			}

			// Country
			if (!empty($vmOrderUser->virtuemart_country_id))
			{
				$orderUserTable->country_code = $vmOrderUser->country_3_code;
			}

			$orderUserTable->store();
		}

		return true;
	}
}
