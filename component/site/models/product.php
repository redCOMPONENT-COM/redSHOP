<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

JLoader::import('joomla.application.component.model');
JLoader::import('product', JPATH_COMPONENT . '/helpers');
JLoader::import('extra_field', JPATH_COMPONENT . '/helpers');
JLoader::import('shipping', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('extra_field', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('category_static', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');

/**
 * Class productModelproduct
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class productModelproduct extends JModel
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
		$option = JRequest::getVar('option', 'com_redshop');
		$pid = JRequest::getInt('pid', 0);

		$GLOBALS['childproductlist'] = array();

		$this->setId((int) $pid);
		$this->_catid = (int) JRequest::getVar('cid', 0);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function _buildQuery()
	{
		$query = $this->_db->getQuery(true);
		$session = JFactory::getSession();

		$helper = new redhelper;

		$user = JFactory::getUser();
		$user_id = $user->id;

		$rsUserhelper = new rsUserhelper;

		$shopperGroupId = $rsUserhelper->getShopperGroup($user_id);

		if ($helper->isredCRM())
		{
			if ($session->get('isredcrmuser'))
			{
				$crmDebitorHelper = new crmDebitorHelper;
				$debitor_id_tot = $crmDebitorHelper->getContactPersons(0, 0, 0, $user_id);
				$debitor_id = $debitor_id_tot[0]->section_id;
				$details = $crmDebitorHelper->getDebitor($debitor_id);
				$user_id = $details[0]->user_id;
			}
		}

		// Shopper group - choose from manufactures Start
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			$query->where('p.manufacturer_id IN (' . $shopper_group_manufactures . ')');
		}

		// Shopper group - choose from manufactures End
		if (isset ($this->_catid) && $this->_catid != 0)
		{
			$query->where('pcx.category_id = ' . (int) $this->_catid);
		}

		if (DEFAULT_QUANTITY_SELECTBOX_VALUE != '')
		{
			$quaboxarr = explode(',', DEFAULT_QUANTITY_SELECTBOX_VALUE);
			$quaboxarr = array_merge(array(), array_unique($quaboxarr));
			sort($quaboxarr);

			for ($q = 0; $q < count($quaboxarr); $q++)
			{
				if (intVal($quaboxarr[$q]) && intVal($quaboxarr[$q]) != 0)
				{
					$qunselect = intVal($quaboxarr[$q]);
					break;
				}
			}
		}
		else
			$qunselect = 1;

		$query->order('p_price.price_quantity_start ASC');

		$producthelper = new producthelper;
		$userdata = $producthelper->getVatUserinfo($user_id);
		$andTr = ' AND (';

		if (VAT_BASED_ON == 2)
		{
			$andTr .= ' tr.is_eu_country = 1 AND ';
		}

		$andTr .= ' tr.tax_country = "' . $userdata->country_code . '" AND (tr.tax_state = "' . $userdata->state_code . '" OR tr.tax_state = "") ';
		$andTr .= ' AND (tr.tax_group_id = p.product_tax_group_id OR tr.tax_group_id = "' . DEFAULT_VAT_GROUP . '" ))';

		// Select fields product, category, manufacturer
		$query->select(array('p.*', 'c.*', 'm.*'));

		// Label from system about using advanced info about product
		$query->select('1 as advanced_query');

		// Select all child product
		$query->select('(SELECT GROUP_CONCAT(child.product_id SEPARATOR ";") FROM ' . $this->_table_prefix . 'product as child WHERE p.product_id = child.product_parent_id AND child.published = 1 AND child.expired = 0) AS childs');

		// Select accessory
		$query->select('(SELECT COUNT(a.product_id) FROM ' . $this->_table_prefix . 'product_accessory AS a WHERE a.product_id = p.product_id ) AS totacc');

		// Select alt text from main image if exist
		$query->select('media.media_alternate_text AS alttext');

		// Select advanced info price if exist
		$query->select(
			array(
				'p_price.price_id',
				'p_price.product_price AS product_adv_price',
				'p_price.product_currency AS product_adv_currency',
				'p_price.discount_price AS discount_adv_price',
				'p_price.discount_start_date AS discount_adv_start_date',
				'p_price.discount_end_date AS discount_adv_end_date'
			)
		);

		// Select template code about product
		$query->select(
			array(
				'tpl.template_id',
				'tpl.template_desc',
				'tpl.template_section',
				'tpl.template_name'
			)
		);

		// Select TAX info
		$query->select(
			array(
				'tr.*',
				'tr.mdate AS tax_mdate'
			)
		);

		// Select product attributes
		$query->select('(SELECT GROUP_CONCAT(att.attribute_id SEPARATOR ",") FROM ' . $this->_table_prefix . 'product_attribute AS att WHERE att.product_id = p.product_id AND att.attribute_name != "" ) AS list_attribute_id');

		$query->select('pcx.ordering');

		$query->from($this->_table_prefix . 'product AS p');

		$query->leftJoin($this->_table_prefix . 'product_category_xref AS pcx ON pcx.product_id = p.product_id');
		$query->leftJoin($this->_table_prefix . 'manufacturer AS m ON m.manufacturer_id = p.manufacturer_id');
		$query->leftJoin($this->_table_prefix . 'media AS media ON p.product_id = media.section_id AND media.media_section = "product" AND media.media_type = "images"');
		$query->leftJoin($this->_table_prefix . 'template AS tpl ON tpl.template_id = p.product_template AND tpl.published = 1');
		$query->leftJoin($this->_table_prefix . 'category AS c ON c.category_id = pcx.category_id');
		$query->leftJoin($this->_table_prefix . 'tax_rate as tr ON tr.tax_group_id = p.product_tax_group_id ' . $andTr);
		$query->leftJoin($this->_table_prefix . 'tax_group as tg ON tg.tax_group_id=tr.tax_group_id AND tg.published = 1');
		$query->leftJoin($this->_table_prefix . 'product_price AS p_price ON p.product_id = p_price.product_id AND ((p_price.price_quantity_start <= ' . (int) $qunselect . ' AND p_price.price_quantity_end >= ' . (int) $qunselect . ') OR(p_price.price_quantity_start = 0 AND p_price.price_quantity_end = 0)) AND p_price.shopper_group_id = ' . (int) $shopperGroupId);

		// Select product special price
		$discount_product_id = $producthelper->getProductSpecialId($user_id);
		$query->select('dp.discount_product_id AS dp_discount_product_id, dp.amount AS dp_amount, dp.condition AS dp_condition, dp.discount_amount AS dp_discount_amount, dp.discount_type AS dp_discount_type');
		$query->leftJoin($this->_table_prefix . 'discount_product AS dp ON dp.published = 1 AND (dp.discount_product_id IN ("' . $discount_product_id . '") OR FIND_IN_SET("' . (int) $this->_id . '", dp.category_ids) ) AND dp.`start_date` <= ' . time() . ' AND dp.`end_date` >= ' . time() . ' AND dp.`discount_product_id` IN (SELECT `discount_product_id` FROM `' . $this->_table_prefix . 'discount_product_shoppers` WHERE `shopper_group_id` = "' . $shopperGroupId . '")');

		// Select ratings
		$query->select('(SELECT COUNT(pr1.rating_id) FROM ' . $this->_table_prefix . 'product_rating AS pr1 WHERE pr1.product_id = p.product_id AND pr1.published = 1) AS count_rating');
		$query->select('(SELECT SUM(pr2.user_rating) FROM ' . $this->_table_prefix . 'product_rating AS pr2 WHERE pr2.product_id = p.product_id AND pr2.published = 1) AS sum_rating');

		$query->where('p.product_id =' . (int) $this->_id);
		$query->where('(media.section_id IS NULL OR media.section_id > 0)');

		return $query;
	}

	public function getData()
	{
		$redTemplate = new Redtemplate;

		if (empty ($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			StaticCategory::setProductSef(array($this->_data->product_id => $this->_data));
		}

		$this->_data->product_s_desc = $redTemplate->parseredSHOPplugin($this->_data->product_s_desc);
		$this->_data->product_desc = $redTemplate->parseredSHOPplugin($this->_data->product_desc);

		return $this->_data;
	}

	public function getProductTemplate()
	{
		$redTemplate = new Redtemplate;

		if (empty ($this->_template))
		{
			$this->_template = $redTemplate->getTemplate("product", $this->_data);
			$this->_template = $this->_template[0];
		}

		return $this->_template;
	}

	/**
	 * get next or previous product using ordering.
	 *
	 * @param   int $product_id   current product id
	 * @param   int $category_id  current product category id
	 * @param   int $dirn         to indicate next or previous product
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
		$db = JFactory::getDBO();
		$query = "SELECT email from " . $this->_table_prefix . "product_rating WHERE email='" . $email . "' AND email != '' AND product_id = '" . $product_id . "' limit 0,1 ";
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
		$user = JFactory::getUser();
		$data['userid'] = $user->id;

		$data['user_rating'] = $data['user_rating'];
		$data['username'] = $data['username'];
		$data['title'] = $data['title'];
		$data['comment'] = $data['comment'];
		$data['product_id'] = $data['product_id'];
		$data['published'] = 0;
		$data['time'] = $data['time'];

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

		$producthelper = new producthelper;
		$redshopMail = new redshopMail;
		$user = JFactory::getUser();

		$url = JURI::base();
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$mailbcc = null;
		$fromname = $data['username'];
		$from = $user->email;
		$subject = "";
		$message = $data['title'];
		$comment = $data['comment'];
		$username = $data['username'];
		$product_id = $data['product_id'];

		$mailbody = $redshopMail->getMailtemplate(0, "review_mail");

		$data_add = $message;

		if (count($mailbody) > 0)
		{
			$data_add = $mailbody[0]->mail_body;
			$subject = $mailbody[0]->mail_subject;

			if (trim($mailbody[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailbody[0]->mail_bcc);
			}
		}

		$product = $producthelper->getProductById($product_id);

		$link = JRoute::_($url . "index.php?option=" . $option . "&view=product&pid=" . $product_id . '&Itemid=' . $Itemid);
		$product_url = "<a href=" . $link . ">" . $product->product_name . "</a>";
		$data_add = str_replace("{product_link}", $product_url, $data_add);
		$data_add = str_replace("{product_name}", $product->product_name, $data_add);
		$data_add = str_replace("{title}", $message, $data_add);
		$data_add = str_replace("{comment}", $comment, $data_add);
		$data_add = str_replace("{username}", $username, $data_add);

		if (ADMINISTRATOR_EMAIL != "")
		{
			$sendto = explode(",", ADMINISTRATOR_EMAIL);

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
			. "WHERE pt.tags_name LIKE '" . $tagname . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function updateVisited($product_id)
	{
		$query = "UPDATE " . $this->_table_prefix . "product "
			. "SET visited=visited + 1 "
			. "WHERE product_id='" . $product_id . "' ";
		$this->_db->setQuery($query);
		$this->_db->Query();
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

	public function addtowishlist2session($data)
	{
		ob_clean();
		$extraField = new extraField;
		$section = 12;
		$row_data = $extraField->getSectionFieldList($section);

		for ($check_i = 1; $check_i <= $_SESSION ["no_of_prod"]; $check_i++)
			if ($_SESSION ['wish_' . $check_i]->product_id == $data ['product_id'])
				if ($data['task'] != "")
				{
					unset($_SESSION["no_of_prod"]);
				}

		$_SESSION ["no_of_prod"] += 1;
		$no_prod_i = 'wish_' . $_SESSION ["no_of_prod"];

		$_SESSION [$no_prod_i]->product_id = $data ['product_id'];
		$_SESSION [$no_prod_i]->comment = isset ($data ['comment']) ? $data ['comment'] : "";
		$_SESSION [$no_prod_i]->cdate = $data ['cdate'];

		for ($k = 0; $k < count($row_data); $k++)
		{
			$myfield = "productuserfield_" . $k;
			$_SESSION[$no_prod_i]->$myfield = $data['productuserfield_' . $k];
		}

		return true;
	}

	public function addProductTagsXref($post, $tags)
	{
		$user = JFactory::getUser();
		$query = "INSERT INTO " . $this->_table_prefix . "product_tags_xref "
			. "VALUES('" . $tags->tags_id . "','" . $post['product_id'] . "','" . $user->id . "')";
		$this->_db->setQuery($query);
		$this->_db->Query();

		return true;
	}

	public function checkProductTags($tagname, $productid)
	{
		$user = JFactory::getUser();
		$query = "SELECT pt.*,ptx.product_id,ptx.users_id FROM " . $this->_table_prefix . "product_tags AS pt "
			. "LEFT JOIN " . $this->_table_prefix . "product_tags_xref AS ptx ON pt.tags_id=ptx.tags_id "
			. "WHERE pt.tags_name LIKE '" . $tagname . "' "
			. "AND ptx.product_id='" . $productid . "' "
			. "AND ptx.users_id='" . $user->id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function checkWishlist($product_id)
	{
		$user = JFactory::getUser();
		$query = "SELECT * FROM " . $this->_table_prefix . "wishlist "
			. "WHERE product_id='" . $product_id . "' "
			. "AND user_id='" . $user->id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function checkComparelist($product_id)
	{
		$session = JFactory::getSession();
		$compare_product = $session->get('compare_product');
		$cid = JRequest::getInt('cid');
		$catid = $compare_product[0]['category_id'];

		if (PRODUCT_COMPARISON_TYPE == 'category' && $catid != $cid)
		{
			unset($compare_product);
			$compare['idx'] = 0;
		}

		if ($product_id != 0)
		{
			if (!$compare_product)
			{
				// Return true to store product in compare product cart.
				return true;
			}
			else
			{
				$idx = (int) ($compare_product['idx']);

				for ($i = 0; $i < $idx; $i++)
				{
					if ($compare_product[$i]["product_id"] == $product_id)
					{
						// Return false if product is already in compare product cart
						return false;
					}
				}

				return true;
			}
		}

		// If function is called for total product in cart than return no of product in cart*/
		return isset($compare_product['idx']) ? (int) ($compare_product['idx']) : 0;
	}

	public function addtocompare($data)
	{
		$session = JFactory::getSession();
		$compare_product = $session->get('compare_product');

		if (!$compare_product)
		{
			$compare_product = array();
			$compare_product['idx'] = 0;

			$session->set('compare_product', $compare_product);
			$compare_product = $session->get('compare_product');
		}

		$idx = (int) ($compare_product['idx']);

		if (PRODUCT_COMPARISON_TYPE == 'category' && $compare_product[0]["category_id"] != $data["cid"])
		{
			unset($compare_product);
			$idx = 0;
		}

		$compare_product[$idx]["product_id"] = $data["pid"];
		$compare_product[$idx]["category_id"] = $data["cid"];

		$compare_product['idx'] = $idx + 1;
		$session->set('compare_product', $compare_product);

		return true;
	}

	public function removeCompare($product_id)
	{
		$session = JFactory::getSession();
		$compare_product = $session->get('compare_product');

		if (!$compare_product)
		{
			return;
		}

		$tmp_array = array();
		$idx = (int) ($compare_product['idx']);
		$tmp_i = 0;

		for ($i = 0; $i < $idx; $i++)
		{
			if ($compare_product[$i]["product_id"] != $product_id)
			{
				$tmp_array[] = $compare_product[$i];
			}
			else
			{
				$tmp_i++;
			}
		}

		$idx -= $tmp_i;

		if ($idx < 0)
		{
			$idx = 0;
		}

		$compare_product = $tmp_array;
		$compare_product['idx'] = $idx;
		$session->set('compare_product', $compare_product);

		return true;
	}

	public function downloadProduct($tid)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_download AS pd "
			. "LEFT JOIN " . $this->_table_prefix . "media AS m ON m.media_name = pd.file_name "
			. "WHERE download_id='" . $tid . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function AdditionaldownloadProduct($mid = 0, $id = 0, $media = 0)
	{
		$where = "";

		if ($mid != 0)
		{
			$where .= "AND media_id='" . $mid . "' ";
		}

		if ($id != 0)
		{
			$where .= "AND id='" . $id . "' ";
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
		$list = $this->_getList($query);

		return $list;
	}

	public function setDownloadLimit($did)
	{
		$query = "UPDATE " . $this->_table_prefix . "product_download "
			. "SET download_max=(download_max - 1) "
			. "WHERE download_id='" . $did . "' ";
		$this->_db->setQuery($query);
		$ret = $this->_db->Query();

		if ($ret)
		{
			return true;
		}

		return false;
	}

	public function getAllChildProductArrayList($childid = 0, $parentid = 0)
	{
		$producthelper = new producthelper;
		$info = $producthelper->getChildProduct($parentid);

		for ($i = 0; $i < count($info); $i++)
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
		$user = JFactory::getUser();
		$user_id = $user->id;
		$data = array();
		$data['product_id'] = $product_id;
		$data['property_id'] = $property_id;
		$data['subproperty_id'] = $subproperty_id;
		$data['user_id'] = $user_id;
		$row = $this->getTable('notifystock_user');

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
