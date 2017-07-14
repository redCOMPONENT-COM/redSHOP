<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class productModelproduct
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelProduct extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	/**
	 * Product data
	 */
	public $_product = null;

	public $_table_prefix = null;

	public $_template = null;

	public $_catid = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$pid                 = JRequest::getInt('pid', 0);

		$GLOBALS['childproductlist'] = array();

		$this->setId((int) $pid);
		$this->_catid = (int) JRequest::getVar('cid', 0);
	}

	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	public function _buildQuery()
	{
		// Shopper group - choose from manufactures Start
		$rsUserhelper               = rsUserHelper::getInstance();
		$shopperGroupManufactures = $rsUserhelper->getShopperGroupManufacturers();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('p.*')
			->select($db->qn('c.id', 'category_id'))
			->select($db->qn('c.name', 'category_name'))
			->select($db->qn('c.category_full_image'))
			->select($db->qn('c.category_back_full_image'))
			->select($db->qn('m.manufacturer_name'))
			->select($db->qn('pcx.ordering'))
			->from($db->qn('#__redshop_product', 'p'))
			->leftjoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id'))
			->leftjoin($db->qn('#__redshop_manufacturer', 'm') . ' ON ' . $db->qn('m.manufacturer_id') . ' = ' . $db->qn('p.manufacturer_id'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('p.product_id') . ' = ' . $db->q((int) $this->_id))
			->setLimit(0, 1);

		if (!empty($shopperGroupManufactures))
		{
			$shopperGroupManufactures = explode(',', $shopperGroupManufactures);
			JArrayHelper::toInteger($shopperGroupManufactures);
			$shopperGroupManufactures = implode(',', $shopperGroupManufactures);
			$query->where($db->qn('p.m.manufacturer_id') . ' IN (' . $shopperGroupManufactures . ')');
		}

		// Shopper group - choose from manufactures End
		if (isset($this->_catid) && $this->_catid != 0)
		{
			$query->where($db->qn('pcx.category_id') . ' = ' . $db->q((int) $this->_catid));
		}

		return $query;
	}

	public function getData()
	{
		$redTemplate = Redtemplate::getInstance();

		if (empty ($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}

		if (is_object($this->_data))
		{
			$this->_data->product_s_desc = $redTemplate->parseredSHOPplugin($this->_data->product_s_desc);
			$this->_data->product_desc   = $redTemplate->parseredSHOPplugin($this->_data->product_desc);
		}

		return $this->_data;
	}

	public function getProductTemplate()
	{
		$redTemplate = Redtemplate::getInstance();

		if (empty ($this->_template))
		{
			$this->_template = $redTemplate->getTemplate("product", $this->_data->product_template);
			$this->_template = $this->_template[0];
		}

		return $this->_template;
	}

	/**
	 * get next or previous product using ordering.
	 *
	 * @param   int  $product_id   current product id
	 * @param   int  $category_id  current product category id
	 * @param   int  $dirn         to indicate next or previous product
	 *
	 * @return mixed
	 */
	public function getPrevNextproduct($product_id, $category_id, $dirn)
	{
		$query = "SELECT ordering FROM " . $this->_table_prefix . "product_category_xref WHERE product_id = " . (int) $product_id . " AND category_id = " . (int) $category_id . " LIMIT 0,1";

		$where = ' AND p.published="1" AND category_id = ' . (int) $category_id;

		$sql = "SELECT pcx.product_id, p.product_name , ordering FROM " . $this->_table_prefix . "product_category_xref ";

		$sql .= " as pcx LEFT JOIN " . $this->_table_prefix . "product as p ON p.product_id = pcx.product_id ";

		if ($dirn < 0)
		{
			$sql .= ' WHERE ordering < (' . $query . ')';
			$sql .= $where;
			$sql .= ' ORDER BY ordering DESC';
		}
		elseif ($dirn > 0)
		{
			$sql .= ' WHERE ordering > (' . $query . ')';
			$sql .= $where;
			$sql .= ' ORDER BY ordering';
		}
		else
		{
			$sql .= ' WHERE ordering = (' . $query . ')';
			$sql .= $where;
			$sql .= ' ORDER BY ordering';
		}

		$this->_db->setQuery($sql, 0, 1);
		$row = null;
		$row = $this->_db->loadObject();

		return $row;
	}

	public function checkReview($email)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT email from " . $this->_table_prefix . "product_rating WHERE email = " . $db->quote($email) . " AND email != '' AND product_id = " . (int) $product_id . " limit 0,1 ";
		$db->setQuery($query);
		$chkemail = $db->loadResult();

		if ($chkemail)
		{
			return true;
		}

		return false;
	}

	public function sendMailForReview($data)
	{
		$user           = JFactory::getUser();
		$data['userid'] = $user->id;

		$data['user_rating'] = $data['user_rating'];
		$data['username']    = $data['username'];
		$data['title']       = $data['title'];
		$data['comment']     = $data['comment'];
		$data['product_id']  = $data['product_id'];
		$data['published']   = 0;
		$data['time']        = $data['time'];

		$row = $this->getTable('rating_detail');

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

		$producthelper = productHelper::getInstance();
		$redshopMail   = redshopMail::getInstance();
		$user          = JFactory::getUser();

		$url        = JURI::base();
		$Itemid     = JRequest::getVar('Itemid');
		$mailbcc    = null;
		$fromname   = $data['username'];
		$from       = $user->email;
		$subject    = "";
		$message    = $data['title'];
		$comment    = $data['comment'];
		$username   = $data['username'];
		$product_id = $data['product_id'];

		$mailbody = $redshopMail->getMailtemplate(0, "review_mail");

		$data_add = $message;

		if (count($mailbody) > 0)
		{
			$data_add = $mailbody[0]->mail_body;
			$subject  = $mailbody[0]->mail_subject;

			if (trim($mailbody[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailbody[0]->mail_bcc);
			}
		}

		$product = $producthelper->getProductById($product_id);

		$link        = JRoute::_($url . "index.php?option=com_redshop&view=product&pid=" . $product_id . '&Itemid=' . $Itemid);
		$product_url = "<a href=" . $link . ">" . $product->product_name . "</a>";
		$data_add    = str_replace("{product_link}", $product_url, $data_add);
		$data_add    = str_replace("{product_name}", $product->product_name, $data_add);
		$data_add    = str_replace("{title}", $message, $data_add);
		$data_add    = str_replace("{comment}", $comment, $data_add);
		$data_add    = str_replace("{username}", $username, $data_add);
		$data_add = $redshopMail->imginmail($data_add);

		if (Redshop::getConfig()->get('ADMINISTRATOR_EMAIL') != "")
		{
			$sendto = explode(",", Redshop::getConfig()->get('ADMINISTRATOR_EMAIL'));

			if (JFactory::getMailer()->sendMail($from, $fromname, $sendto, $subject, $data_add, $mode = 1, null, $mailbcc))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * Product Tags Functions
	 */
	public function getProductTags($tagname, $productid)
	{
		$query = "SELECT pt.*,ptx.product_id,ptx.users_id "
			. "FROM " . $this->_table_prefix . "product_tags AS pt "
			. "LEFT JOIN " . $this->_table_prefix . "product_tags_xref AS ptx ON pt.tags_id=ptx.tags_id "
			. "WHERE pt.tags_name LIKE " . $this->_db->quote($tagname) . " ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function updateVisited($product_id)
	{
		$query = "UPDATE " . $this->_table_prefix . "product "
			. "SET visited=visited + 1 "
			. "WHERE product_id = " . (int) $product_id;
		$this->_db->setQuery($query);
		$this->_db->execute();
	}

	public function addProductTags($data)
	{
		$tags = $this->getTable('product_tags');

		if (!$tags->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$tags->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $tags;
	}

	public function addtowishlist($data)
	{
		$row = $this->getTable('wishlist');

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

	/**
	 * Method for store wishlist in session.
	 *
	 * @param   array  $data  List of data.
	 *
	 * @return  bool          True on success. False otherwise.
	 */
	public function addtowishlist2session($data)
	{
		$attributes    = null;
		$properties    = null;
		$subAttributes = null;
		$session       = JFactory::getSession();

		if (array_key_exists('attribute_id', $data))
		{
			$attributes = explode('##', $data['attribute_id']);
		}

		if (array_key_exists('property_id', $data))
		{
			$properties = explode('##', $data['property_id']);
		}

		if (array_key_exists('subattribute_id', $data))
		{
			$subAttributes = explode('##', $data['subattribute_id']);
		}

		ob_clean();
		$extraField = extraField::getInstance();
		$section    = 12;
		$row_data   = $extraField->getSectionFieldList($section);
		$wishlistSession = $session->get('wishlist');

		if (!empty($wishlistData) && !Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			$wishlistSession[$data['product_id']] = null;
		}

		$wishlist = new stdClass;
		$wishlist->product_id = $data['product_id'];
		$wishlist->comment    = isset($data ['comment']) ? $data ['comment'] : "";
		$wishlist->cdate      = $data['cdate'];

		for ($k = 0, $kn = count($row_data); $k < $kn; $k++)
		{
			$field = "productuserfield_" . $k;
			$wishlist->{$field} = $data['productuserfield_' . $k];
		}

		if (!$attributes || !Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			$wishlistSession[$data['product_id']] = $wishlist;
			$session->set('wishlist', $wishlistSession);

			return true;
		}

		if (empty($wishlistSession[$data['product_id']]) || !is_array($wishlistSession[$data['product_id']]))
		{
			$wishlistSession[$data['product_id']] = array();
		}

		$wishlist->product_items = array();

		foreach ($attributes as $index => $attribute)
		{
			$item = new stdClass;
			$item->attribute_id = $attribute;

			if (isset($properties[$index]))
			{
				$item->property_id = $properties[$index];
			}

			if (isset($subAttributes[$index]))
			{
				$item->subattribute_id = $subAttributes[$index];
			}

			$wishlist->product_items = $item;
		}

		if (!empty($wishlistSession[$data['product_id']]))
		{
			foreach ($wishlistSession[$data['product_id']] as $wishlistItem)
			{
				if ($wishlistItem->product_items == $wishlist->product_items)
				{
					return true;
				}
			}
		}

		$wishlistSession[$data['product_id']][] = $wishlist;
		$session->set('wishlist', $wishlistSession);

		return true;
	}

	public function addProductTagsXref($post, $tags)
	{
		$user  = JFactory::getUser();
		$query = "INSERT INTO " . $this->_table_prefix . "product_tags_xref "
			. "VALUES('" . (int) $tags->tags_id . "','" . (int) $post['product_id'] . "','" . (int) $user->id . "')";
		$this->_db->setQuery($query);
		$this->_db->execute();

		return true;
	}

	public function checkProductTags($tagname, $productid)
	{
		$user  = JFactory::getUser();
		$query = "SELECT pt.*,ptx.product_id,ptx.users_id FROM " . $this->_table_prefix . "product_tags AS pt "
			. "LEFT JOIN " . $this->_table_prefix . "product_tags_xref AS ptx ON pt.tags_id=ptx.tags_id "
			. "WHERE pt.tags_name LIKE " . $this->_db->quote($tagname) . " "
			. "AND ptx.product_id = " . (int) $productid . " "
			. "AND ptx.users_id = " . (int) $user->id;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function checkWishlist($product_id)
	{
		$user  = JFactory::getUser();
		$query = "SELECT * FROM " . $this->_table_prefix . "wishlist "
			. "WHERE product_id = " . (int) $product_id . " "
			. "AND user_id = " . (int) $user->id;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	/**
	 * Get Download product info
	 *
	 * @param   string  $downloadId  Download id
	 *
	 * @return mixed
	 */
	public function downloadProduct($downloadId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_product_download', 'pd'))
			->leftJoin($db->qn('#__redshop_media', 'm') . ' ON m.media_name = pd.file_name')
			->where('pd.download_id = ' . $db->q($downloadId));

		return $db->setQuery($query)->loadObject();
	}

	public function AdditionaldownloadProduct($mid = 0, $id = 0, $media = 0)
	{
		$where = "";

		if ($mid != 0)
		{
			$where .= "AND media_id = " . (int) $mid . " ";
		}

		if ($id != 0)
		{
			$where .= "AND id = " . (int) $id . " ";
		}

		if ($media != 0)
		{
			$tablename = "media ";
		}
		else
		{
			$tablename = "media_download ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . $tablename
			. "WHERE 1=1 "
			. $where;
		$list  = $this->_getList($query);

		return $list;
	}

	public function setDownloadLimit($did)
	{
		$query = "UPDATE " . $this->_table_prefix . "product_download "
			. "SET download_max=(download_max - 1) "
			. "WHERE download_id = " . (int) $did;
		$this->_db->setQuery($query);
		$ret = $this->_db->execute();

		if ($ret)
		{
			return true;
		}

		return false;
	}

	public function getAllChildProductArrayList($childid = 0, $parentid = 0)
	{
		$producthelper = productHelper::getInstance();
		$info          = $producthelper->getChildProduct($parentid);

		for ($i = 0, $in = count($info); $i < $in; $i++)
		{
			if ($childid != $info[$i]->product_id)
			{
				$GLOBALS['childproductlist'][] = $info[$i];
				$this->getAllChildProductArrayList($childid, $info[$i]->product_id);
			}
		}

		return $GLOBALS['childproductlist'];
	}

	public function addNotifystock($product_id, $property_id, $subproperty_id)
	{
		ob_clean();
		$user                   = JFactory::getUser();
		$user_id                = $user->id;
		$data                   = array();
		$data['product_id']     = $product_id;
		$data['property_id']    = $property_id;
		$data['subproperty_id'] = $subproperty_id;
		$data['user_id']        = $user_id;
		$row                    = $this->getTable('notifystock_user');

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

		return true;
	}
}
