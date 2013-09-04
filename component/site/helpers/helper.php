<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/user.php';

class redhelper
{
	public $_table_prefix = null;

	public $_db = null;

	public $_isredCRM = null;

	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
		$this->_db           = JFactory::getDBO();
	}

	/**
	 * add item to cart from db ...
	 *
	 * @return  void
	 */
	public function dbtocart()
	{
		require_once JPATH_SITE . '/components/com_redshop/helpers/cart.php';
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$user    = JFactory::getUser();

		if ($user->id && !isset($cart['idx']))
		{
			$rscarthelper = new rsCarthelper;
			$rscarthelper->dbtocart();
		}
	}

	/**
	 * Delete shipping rate when shipping method is not available
	 *
	 * @return  void
	 */
	public function removeShippingRate()
	{
		$query = "SELECT DISTINCT(shipping_class)  FROM " . $this->_table_prefix . "shipping_rate ";
		$this->_db->setQuery($query);
		$data = $this->_db->loadColumn();

		if (count($data) > 0)
		{
			$query_plg = "SELECT element FROM #__extensions WHERE folder='redshop_shipping'";
			$this->_db->setQuery($query_plg);
			$plg_ship_elm = $this->_db->loadColumn();

			$diff_ship = array_diff($data, $plg_ship_elm);
			sort($diff_ship);

			for ($i = 0; $i < count($diff_ship); $i++)
			{
				$query = "DELETE  FROM " . $this->_table_prefix . "shipping_rate WHERE shipping_class='" . $diff_ship[$i] . "'";
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}
	}

	public function getPlugins($folder = 'redshop')
	{
		$query = "SELECT * FROM #__extensions "
			. "WHERE  enabled = '1' "
			. "AND LOWER(`folder`) = '" . strtolower($folder) . "' "
			. "ORDER BY ordering ASC ";
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();

		return $data;
	}

	public function getallPlugins($folder = 'redshop')
	{
		$query = "SELECT * FROM #__extensions "
			. "WHERE LOWER(`folder`) = '" . strtolower($folder) . "' "
			. "ORDER BY ordering ASC ";
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectList();

		return $data;
	}

	public function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
	{
		$res   = false;
		$query = "SELECT COUNT(*) `qty` FROM `" . $this->_table_prefix . "order_payment` "
			. "WHERE `order_id` = '" . $this->_db->getEscaped($order_id) . "' "
			. "AND order_payment_trans_id = '" . $this->_db->getEscaped($tid) . "' ";
		$this->_db->SetQuery($query);
		$order_payment = $this->_db->loadResult();

		if ($order_payment == 0)
		{
			$res = true;
		}

		return $res;
	}

	public function getItemid($product_id = '', $cat_id = 0)
	{
		$producthelper = new producthelper;
		$catDetailmenu = false;

		if ($cat_id)
		{
			$sql = "SELECT id FROM #__menu "
				. "WHERE published=1 "
				. "AND `link` LIKE '%com_redshop%' "
				. "AND `link` LIKE '%view=category%' "
				. "AND ( link LIKE '%cid=" . $cat_id . "' OR link LIKE '%cid=" . $cat_id . "&%' ) "
				. "ORDER BY 'ordering'";
			$this->_db->setQuery($sql);

			if ($Itemid = $this->_db->loadResult())
			{
				$catDetailmenu = true;

				return $Itemid;
			}
		}

		$sql = "SELECT category_id 	FROM " . $this->_table_prefix . "product_category_xref cx WHERE product_id = '$product_id'";
		$this->_db->setQuery($sql);
		$cats = $this->_db->loadObjectList();

		for ($i = 0; $i < count($cats); $i++)
		{
			$cat = $cats[$i];
			$sql = "SELECT id FROM #__menu "
				. "WHERE published=1 "
				. "AND `link` LIKE '%com_redshop%' "
				. "AND `link` LIKE '%view=category%' "
				. "AND ( link LIKE '%cid=" . $cat->category_id . "' OR link LIKE '%cid=" . $cat->category_id . "&%' ) "
				. "ORDER BY 'ordering'";
			$this->_db->setQuery($sql);

			if ($Itemid = $this->_db->loadResult())
			{
				return $Itemid;
			}
		}

		$option = JRequest::getVar('option');

		if ($option != 'com_redshop')
		{
			if (!$catDetailmenu)
			{
				$sql = "SELECT id FROM #__menu "
					. "WHERE published=1 "
					. "AND `link` LIKE '%com_redshop%' "
					. "AND `link` LIKE '%view=category%' "
					. "ORDER BY 'ordering'";

				$this->_db->setQuery($sql);

				if ($Itemid = $this->_db->loadResult())
				{
					return $Itemid;
				}
			}

			$Itemidlist = $producthelper->getMenuInformation();

			if (count($Itemidlist) == 1)
			{
				$Itemid = $Itemidlist->id;

				return $Itemid;
			}
		}

		$Itemid = intval(JRequest::getVar('Itemid'));

		return $Itemid;
	}

	public function getCategoryItemid($category_id = 0)
	{
		if ($category_id)
		{
			$and = ' AND (`link` LIKE "%option=com_redshop&view=category&layout=detail") AND (`params` LIKE \'%"cid":"' . $category_id . '"%\') ';
		}
		else
		{
			$and = ' AND (`link` LIKE "%option=com_redshop&view=category") ';
		}

		$query = "SELECT id FROM #__menu "
			. "WHERE 1=1  and published='1' "
			. $and
			. "ORDER BY 'ordering'";
		$this->_db->setQuery($query);
		$Itemid = $this->_db->loadResult();

		return $Itemid;
	}

	public function convertLanguageString($arr)
	{
		for ($i = 0; $i < count($arr); $i++)
		{
			$txt   = $arr[$i]->text;
			$ltext = JText::_($txt);

			if ($ltext != $txt)
			{
				$arr[$i]->text = $ltext;
			}
			elseif ($arr[$i]->country_jtext != "")
			{
				$arr[$i]->text = $arr[$i]->country_jtext;
			}
		}

		$tmpArray = array();

		for ($i = 0; $i < count($arr); $i++)
		{
			$txt            = $arr[$i]->text;
			$val            = $arr[$i]->value;
			$tmpArray[$val] = $txt;
		}

		asort($tmpArray);
		$x = 0;

		foreach ($tmpArray AS $val => $txt)
		{
			$arr[$x]->text  = $txt;
			$arr[$x]->value = $val;
			$x++;
		}

		return $arr;
	}

	/**
	 * shopper Group portal info
	 *
	 * @return  object  Shopper Group Ids Object
	 */
	public function getShopperGroupPortal()
	{
		$user = JFactory::getUser();

		// If user is not logged in than take shoppergroup id from configuration
		$where = "AND `shopper_group_id`='" . SHOPPER_GROUP_DEFAULT_UNREGISTERED . "' ";

		if ($user->id)
		{
			$userq = "SELECT shopper_group_id FROM " . $this->_table_prefix . "users_info WHERE user_id = " . $user->id . " AND address_type = 'BT'";
			$where = "AND `shopper_group_id`IN ($userq)";
		}

		$query = "SELECT * FROM `" . $this->_table_prefix . "shopper_group` "
			. "WHERE 1=1 "
			. $where;
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	/**
	 * shopper Group category ACL
	 */
	public function getShopperGroupCategory($cid = 0)
	{
		$user = JFactory::getUser();

		// If user is not logged in than take shoppergroup id from configuration
		$where = "AND `shopper_group_id`='" . SHOPPER_GROUP_DEFAULT_UNREGISTERED . "' ";

		if ($user->id)
		{
			$userq = "SELECT shopper_group_id FROM " . $this->_table_prefix . "users_info WHERE user_id = " . $user->id . " AND address_type = 'BT'";
			$where = "AND `shopper_group_id`IN ($userq)";
		}

		$query = "SELECT *, count(`shopper_group_id`) as total FROM `" . $this->_table_prefix . "shopper_group` "
			. "WHERE 1=1 "
			. $where
			. "AND FIND_IN_SET(" . $cid . ",shopper_group_categories) "
			. "GROUP BY shopper_group_id ";
		$this->_db->setQuery($query);
		$shoppercatdata = $this->_db->loadObject();

		return $shoppercatdata;
	}

	public function getShopperGroupProductCategory($pid = 0)
	{
		$user = JFactory::getUser();

		$query = "SELECT p.product_id,cx.category_id FROM `" . $this->_table_prefix . "product` AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS cx ON p.product_id=cx.product_id "
			. "WHERE p.product_id='" . $pid . "' ";
		$this->_db->setQuery($query);
		$prodctcat = $this->_db->loadObjectList();
		$catflag   = false;

		for ($i = 0; $i < count($prodctcat); $i++)
		{
			$cid            = $prodctcat[$i]->category_id;
			$shoppercatdata = $this->getShopperGroupCategory($cid);

			if (count($shoppercatdata) <= 0 && $catflag == false)
			{
				$catflag = true;
			}
		}

		return $catflag;
	}

	// 	Order by list
	public function getOrderByList()
	{
		$order_data           = array();
		$order_data[0] = new stdClass;
		$order_data[0]->value = "p.product_name ASC";
		$order_data[0]->text  = JText::_('COM_REDSHOP_PRODUCT_NAME');

		$order_data[1] = new stdClass;
		$order_data[1]->value = "p.product_price ASC";
		$order_data[1]->text  = JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC');

		$order_data[2] = new stdClass;
		$order_data[2]->value = "p.product_price DESC";
		$order_data[2]->text  = JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC');

		$order_data[3] = new stdClass;
		$order_data[3]->value = "p.product_number ASC";
		$order_data[3]->text  = JText::_('COM_REDSHOP_PRODUCT_NUMBER');

		$order_data[4] = new stdClass;
		$order_data[4]->value = "p.product_id DESC";
		$order_data[4]->text  = JText::_('COM_REDSHOP_NEWEST');

		$order_data[5] = new stdClass;
		$order_data[5]->value = "pc.ordering ASC";
		$order_data[5]->text  = JText::_('COM_REDSHOP_ORDERING');

		return $order_data;
	}

	public function getManufacturerOrderByList()
	{
		$order_data           = array();
		$order_data[0] = new stdClass;
		$order_data[0]->value = "mn.manufacturer_name ASC";
		$order_data[0]->text  = JText::_('COM_REDSHOP_ALPHABETICALLY');

		$order_data[1] = new stdClass;
		$order_data[1]->value = "mn.manufacturer_id DESC";
		$order_data[1]->text  = JText::_('COM_REDSHOP_NEWEST');

		$order_data[2] = new stdClass;
		$order_data[2]->value = "mn.ordering ASC";
		$order_data[2]->text  = JText::_('COM_REDSHOP_ORDERING');

		return $order_data;
	}

	public function getRelatedOrderByList()
	{
		$order_data           = array();
		$order_data[0] = new stdClass;
		$order_data[0]->value = "p.product_name ASC";
		$order_data[0]->text  = JText::_('COM_REDSHOP_PRODUCT_NAME_ASC');

		$order_data[1] = new stdClass;
		$order_data[1]->value = "p.product_name DESC";
		$order_data[1]->text  = JText::_('COM_REDSHOP_PRODUCT_NAME_DESC');

		$order_data[2] = new stdClass;
		$order_data[2]->value = "p.product_price ASC";
		$order_data[2]->text  = JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC');

		$order_data[3] = new stdClass;
		$order_data[3]->value = "p.product_price DESC";
		$order_data[3]->text  = JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC');

		$order_data[4] = new stdClass;
		$order_data[4]->value = "p.product_number ASC";
		$order_data[4]->text  = JText::_('COM_REDSHOP_PRODUCT_NUMBER_ASC');

		$order_data[5] = new stdClass;
		$order_data[5]->value = "p.product_number DESC";
		$order_data[5]->text  = JText::_('COM_REDSHOP_PRODUCT_NUMBER_DESC');

		$order_data[6] = new stdClass;
		$order_data[6]->value = "r.ordering ASC";
		$order_data[6]->text  = JText::_('COM_REDSHOP_ORDERING_ASC');

		$order_data[7] = new stdClass;
		$order_data[7]->value = "r.ordering DESC";
		$order_data[7]->text  = JText::_('COM_REDSHOP_ORDERING_DESC');

		$order_data[8] = new stdClass;
		$order_data[8]->value = "e.data_txt ASC";
		$order_data[8]->text  = JText::_('COM_REDSHOP_DATEPICKER_ASC');

		$order_data[9] = new stdClass;
		$order_data[9]->value = "e.data_txt DESC";
		$order_data[9]->text  = JText::_('COM_REDSHOP_DATEPICKER_DESC');

		return $order_data;
	}

	public function getAccessoryOrderByList()
	{
		$order_data           = array();
		$order_data[0] = new stdClass;
		$order_data[0]->value = "child_product_id ASC";
		$order_data[0]->text  = JText::_('COM_REDSHOP_PRODUCT_ID_ASC');

		$order_data[1] = new stdClass;
		$order_data[1]->value = "child_product_id DESC";
		$order_data[1]->text  = JText::_('COM_REDSHOP_PRODUCT_ID_DESC');

		$order_data[2] = new stdClass;
		$order_data[2]->value = "accessory_id ASC";
		$order_data[2]->text  = JText::_('COM_REDSHOP_ACCESSORY_ID_ASC');

		$order_data[3] = new stdClass;
		$order_data[3]->value = "accessory_id DESC";
		$order_data[3]->text  = JText::_('COM_REDSHOP_ACCESSORY_ID_DESC');

		$order_data[4] = new stdClass;
		$order_data[4]->value = "newaccessory_price ASC";
		$order_data[4]->text  = JText::_('COM_REDSHOP_ACCESSORY_PRICE_ASC');

		$order_data[5] = new stdClass;
		$order_data[5]->value = "newaccessory_price DESC";
		$order_data[5]->text  = JText::_('COM_REDSHOP_ACCESSORY_PRICE_DESC');

		$order_data[6] = new stdClass;
		$order_data[6]->value = "ordering ASC";
		$order_data[6]->text  = JText::_('COM_REDSHOP_ORDERING_ASC');

		$order_data[7] = new stdClass;
		$order_data[7]->value = "ordering DESC";
		$order_data[7]->text  = JText::_('COM_REDSHOP_ORDERING_DESC');

		return $order_data;
	}

	//  function to get preorder option list
	public function getPreOrderByList()
	{
		$preorder_data = array();
		$preorder_data[0] = new stdClass;
		$preorder_data[0]->value = "global";
		$preorder_data[0]->text  = JText::_('COM_REDSHOP_GLOBAL');

		$preorder_data[1] = new stdClass;
		$preorder_data[1]->value = "yes";
		$preorder_data[1]->text  = JText::_('COM_REDSHOP_YES');

		$preorder_data[2] = new stdClass;
		$preorder_data[2]->value = "no";
		$preorder_data[2]->text  = JText::_('COM_REDSHOP_NO');

		return $preorder_data;
	}

	//  function to get child product option list
	public function getChildProductOption()
	{
		$childproduct_data = array();
		$childproduct_data[0] = new stdClass;
		$childproduct_data[0]->value = "product_name";
		$childproduct_data[0]->text  = JText::_('COM_REDSHOP_CHILD_PRODUCT_NAME');

		$childproduct_data[1] = new stdClass;
		$childproduct_data[1]->value = "product_number";
		$childproduct_data[1]->text  = JText::_('COM_REDSHOP_CHILD_PRODUCT_NUMBER');

		return $childproduct_data;
	}

	//  function to get state abbrivation option list
	public function getStateAbbrivationByList()
	{
		$state_data           = array();
		$state_data[0] = new stdClass;
		$state_data[0]->value = "2";
		$state_data[0]->text  = JText::_('COM_REDSHOP_TWO_LETTER_ABBRIVATION');

		$state_data[1] = new stdClass;
		$state_data[1]->value = "3";
		$state_data[1]->text  = JText::_('COM_REDSHOP_THREE_LETTER_ABBRIVATION');

		return $state_data;
	}

	// Get checkout Itemid
	public function getCheckoutItemid()
	{
		$userhelper         = new rsUserhelper;
		$Itemid             = DEFAULT_CART_CHECKOUT_ITEMID;
		$shopper_group_data = $userhelper->getShoppergroupData();

		if (count($shopper_group_data) > 0 && $shopper_group_data->shopper_group_cart_checkout_itemid != 0)
		{
			$Itemid = $shopper_group_data->shopper_group_cart_checkout_itemid;
		}

		if ($Itemid == 0)
		{
			$Itemid = JRequest::getInt('Itemid');
		}

		return $Itemid;
	}

	// Get cart Itemid
	public function getCartItemid($Itemid)
	{
		$userhelper         = new rsUserhelper;
		$Itemid             = DEFAULT_CART_CHECKOUT_ITEMID;
		$shopper_group_data = $userhelper->getShoppergroupData();

		if (count($shopper_group_data) > 0 && $shopper_group_data->shopper_group_cart_itemid != 0)
		{
			$Itemid = $shopper_group_data->shopper_group_cart_itemid;
		}

		return $Itemid;
	}

	/**
	 * Water mark image.
	 *
	 *  @param   string  $mtype             Comment.
	 *  @param   string  $Imagename         Comment.
	 *  @param   string  $thumb_width       Comment.
	 *  @param   string  $thumb_height      Comment.
	 *  @param   string  $enable_watermart  Comment.
	 *  @param   int     $add_img           Comment.
	 *
	 * @return string
	 */
	public function watermark($mtype, $Imagename = '', $thumb_width = '', $thumb_height = '', $enable_watermart = WATERMARK_PRODUCT_IMAGE, $add_img = 0)
	{
		require_once JPATH_ROOT . '/administrator/components/com_redshop/helpers/images.php';

		$url    = JURI::root();

		/*
		 * IF watermark is not enable
		 * return thumb image
		 */
		if ($enable_watermart <= 0)
		{
			if (($thumb_width != '' || $thumb_width != 0) && ($thumb_height != '' || $thumb_width != 0))
			{
				$file_path          = JPATH_SITE . '/components/com_redshop/assets/images/' . $mtype . '/' . $Imagename;
				$filename           = RedShopHelperImages::generateImages($file_path, '', 'thumb', $mtype, $thumb_width, $thumb_height, USE_IMAGE_SIZE_SWAPPING);
				$filename_path_info = pathinfo($filename);
				$filename           = REDSHOP_FRONT_IMAGES_ABSPATH . $mtype . '/thumb/' . $filename_path_info['basename'];
			}
			else
			{
				$filename = REDSHOP_FRONT_IMAGES_ABSPATH . $mtype . "/" . $Imagename;
			}

			return $filename;
		}

		if ($Imagename
			&& file_exists(REDSHOP_FRONT_IMAGES_RELPATH . $mtype . "/" . $Imagename)
			&& (WATERMARK_IMAGE
			&& file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . WATERMARK_IMAGE)))
		{
			if ($thumb_width != '' && $thumb_height != '')
			{
				$file_path    = JPATH_SITE . '/components/com_redshop/assets/images/product/' . WATERMARK_IMAGE;
				$filename     = RedShopHelperImages::generateImages($file_path, '', 'thumb', 'product', $thumb_width, $thumb_height, USE_IMAGE_SIZE_SWAPPING);
				$filename_path_info = pathinfo($filename);
				$watermark          = REDSHOP_FRONT_IMAGES_ABSPATH . 'product/thumb/' . $filename_path_info['basename'];

				$file_path          = JPATH_SITE . '/components/com_redshop/assets/images/' . $mtype . '/' . $Imagename;
				$filename           = RedShopHelperImages::generateImages($file_path, '', 'thumb', $mtype, $thumb_width, $thumb_height, USE_IMAGE_SIZE_SWAPPING);
				$filename_path_info = pathinfo($filename);
				$filename           = REDSHOP_FRONT_IMAGES_ABSPATH . $mtype . '/thumb/' . $filename_path_info['basename'];

				if ($add_img == 2)
				{
					$gnImagename = 'hover' . $Imagename;
				}
				elseif ($add_img == 1)
				{
					$gnImagename = 'add' . $Imagename;
				}
				else
				{
					$gnImagename = $Imagename;
				}
			}
			else
			{
				$watermark   = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . WATERMARK_IMAGE;
				$filename    = REDSHOP_FRONT_IMAGES_RELPATH . $mtype . "/" . $Imagename;
				$gnImagename = 'main' . $Imagename;

				if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "watermarked/" . $gnImagename))
				{
					return $DestinationFile = REDSHOP_FRONT_IMAGES_ABSPATH . "watermarked/" . $gnImagename;
				}
			}

			$DestinationFile = REDSHOP_FRONT_IMAGES_RELPATH . "watermarked/" . $gnImagename;
			$filetype        = JFile::getExt(WATERMARK_IMAGE);

			switch ($filetype)
			{
				case "gif":
					$dest = @imagecreatefromjpeg($filename);
					$src  = @imagecreatefromgif($watermark);

					list($width, $height, $type, $attr) = @getimagesize($filename);

					list($markwidth, $markheight, $type1, $attr1) = @getimagesize($watermark);

					@imagecopymerge($dest, $src, ($width - $markwidth) >> 1, ($height - $markheight) >> 1, 0, 0, $markwidth, $markheight, 50);

					// Save the image to a file
					@imagejpeg($dest, $DestinationFile);

					$DestinationFile = REDSHOP_FRONT_IMAGES_ABSPATH . "watermarked/" . $gnImagename;

					return $DestinationFile;

				case "png":
					$im    = imagecreatefrompng($watermark);
					$exten = JFile::getExt($filename);

					$extARRAY = @ explode('&', $exten);
					$ext      = $extARRAY[0];

					if (strtolower($ext) == "gif")
					{
						if (!$im2 = imagecreatefromgif($filename))
						{
							echo "Error opening $filename!";
						}
					}
					elseif (strtolower($ext) == "jpg")
					{
						if (!$im2 = imagecreatefromjpeg($filename))
						{
							echo "Error opening $filename!";
							exit;
						}
					}
					elseif (strtolower($ext) == "png")
					{
						if (!$im2 = imagecreatefrompng($filename))
						{
							echo "Error opening $filename!";
							exit;
						}
					}
					else
					{
						die;
					}

					imagecopy($im2, $im, (imagesx($im2) / 2) - (imagesx($im) / 2), (imagesy($im2) / 2) - (imagesy($im) / 2), 0, 0, imagesx($im), imagesy($im));
					$waterless = imagesx($im2) - imagesx($im);
					$rest      = ceil($waterless / imagesx($im) / 2);

					for ($n = 1; $n <= $rest; $n++)
					{
						imagecopy(
							$im2,
							$im,
							((imagesx($im2) / 2) - (imagesx($im) / 2)) - (imagesx($im) * $n),
							(imagesy($im2) / 2) - (imagesy($im) / 2),
							0,
							0,
							imagesx($im),
							imagesy($im)
						);

						imagecopy(
							$im2,
							$im,
							((imagesx($im2) / 2) - (imagesx($im) / 2)) + (imagesx($im) * $n),
							(imagesy($im2) / 2) - (imagesy($im) / 2),
							0,
							0,
							imagesx($im), imagesy($im)
						);
					}

					imagejpeg($im2, $DestinationFile);
					$DestinationFile = REDSHOP_FRONT_IMAGES_ABSPATH . "watermarked/" . $gnImagename;

					return $DestinationFile;
			}
		}
		else
		{
			if (($thumb_width != '' || $thumb_width != 0) && ($thumb_height != '' || $thumb_width != 0))
			{
				$filename = $url
					. "components/com_redshop/helpers/thumb.php?filename="
					. $mtype . "/" . $Imagename . "&newxsize="
					. $thumb_width . "&newysize=" . $thumb_height
					. "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}
			else
			{
				$filename = REDSHOP_FRONT_IMAGES_ABSPATH . $mtype . "/" . $Imagename;
			}

			return $filename;
		}
	}

	public function clickatellSMS($order_id)
	{
		if (CLICKATELL_ENABLE <= 0)
		{
			return;
		}

		$shippinghelper = new shipping;

		$query = "SELECT * FROM " . $this->_table_prefix . "order_users_info AS oui "
			. "LEFT JOIN " . $this->_table_prefix . "orders AS o ON o.order_id = oui.order_id "
			. "WHERE oui.order_id = '" . $order_id . "' "
			. "AND address_type='ST' ";
		$this->_db->setQuery($query);
		$orderData = $this->_db->loadobject();

		$query = "SELECT payment_method_name, oy.payment_method_id FROM " . $this->_table_prefix . "order_payment AS oy "
			. "LEFT JOIN " . $this->_table_prefix . "orders AS o ON o.order_id = oy.order_id "
			. "LEFT JOIN " . $this->_table_prefix . "payment_method AS p ON p.payment_method_id = oy.payment_method_id "
			. "WHERE oy.order_id = '" . $order_id . "' ";
		$this->_db->setQuery($query);
		$paymentData       = $this->_db->loadobject();
		$paymentName       = $paymentData->payment_method_name;
		$payment_method_id = $paymentData->payment_method_id;
		$redTemplate       = new Redtemplate;
		$TemplateDetail    = $redTemplate->getTemplate("clicktell_sms_message");

		$order_shipping_class = 0;
		$order_shipping       = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $orderData->ship_method_id)));

		if (isset($order_shipping[0]))
		{
			$order_shipping_class = $order_shipping[0];
		}

		$p_where = " AND (FIND_IN_SET( '" . $payment_method_id . "', payment_methods ))";
		$s_where = " AND (FIND_IN_SET( '" . $order_shipping_class . "', shipping_methods ))";

		$orderby = " ORDER BY `template_id` DESC LIMIT 0,1";
		$query   = "SELECT * FROM " . $this->_table_prefix . "template AS t "
			. "WHERE t.template_section = 'clicktell_sms_message' "
			. "AND (FIND_IN_SET( '" . $orderData->order_status . "', order_status )) ";
		$to      = $orderData->phone;
		$this->_db->setQuery($query . $p_where . $orderby);
		$payment_methods = $this->_db->loadobject();
		$message         = $this->replaceMessage($payment_methods->template_desc, $orderData, $paymentName);

		if ($message)
		{
			$this->sendmessage(urlencode($message), $to);
		}

		$this->_db->setQuery($query . $s_where . $orderby);
		$shipping_methods = $this->_db->loadobject();

		$message = $this->replaceMessage($shipping_methods->template_desc, $orderData, $paymentName);

		if ($message)
		{
			$this->sendmessage(urlencode($message), $to);
		}

		if (CLICKATELL_ORDER_STATUS == $orderData->order_status)
		{
			$message = $this->replaceMessage($TemplateDetail[0]->template_desc, $orderData, $paymentName);

			if ($message)
			{
				$this->sendmessage(urlencode($message), $to);
			}
		}
	}

	public function sendmessage($text, $to)
	{
		// Clickatell_username
		$user     = CLICKATELL_USERNAME;

		// Clickatell_password
		$password = CLICKATELL_PASSWORD;

		// Clickatell_api_id
		$api_id   = CLICKATELL_API_ID;
		$baseurl  = "http://api.clickatell.com";

		// Auth call
		$url  = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

		// Do auth call
		$ret  = file($url);

		// Split our response. return string is on first line of the data returned
		$sess = explode(":", $ret[0]);

		if ($sess[0] == "OK")
		{
			// Remove any whitespace
			$sess_id = trim($sess[1]);
			$url     = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";

			// Do sendmsg call
			$ret  = file($url);
			$send = explode(":", $ret[0]);

			if ($send[0] == "ID")
			{
				echo "success message ID: " . $send[1];
			}
			else
			{
				JError::raiseWarning(21, "send message failed: ");
			}
		}
		else
		{
			JError::raiseWarning(21, "Authentication failure: " . $ret[0]);
		}
	}

	public function replaceMessage($message, $orderData, $paymentName)
	{
		$shippinghelper  = new shipping;
		$shipping_method = '';
		$details         = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $orderData->ship_method_id)));

		if (count($details) > 1)
		{
			$ext = "";

			if (array_key_exists(2, $details))
			{
				$ext = " (" . $details[2] . ")";
			}

			$shipping_method = $details[1] . $ext;
		}

		$producthelper = new producthelper;

		$userData = $producthelper->getUserInformation($orderData->user_id);

		$message = str_replace('{order_id}', $orderData->order_id, $message);
		$message = str_replace('{order_status}', $orderData->order_status, $message);
		$message = str_replace('{customer_name}', $userData->firstname, $message);
		$message = str_replace('{payment_status}', $orderData->order_payment_status, $message);
		$message = str_replace('{order_comment}', $orderData->customer_note, $message);
		$message = str_replace('{shipping_method}', $shipping_method, $message);
		$message = str_replace('{payment_method}', $paymentName, $message);

		return $message;
	}

	public function getsslLink($link, $applySSL)
	{
		$uri = JURI::getInstance($link);

		if ($applySSL)
		{
			$uri->setScheme('https');
		}
		else
		{
			$uri->setScheme('http');
		}

		$link = $uri->toString();

		return $link;
	}

	public function sslLink($link, $applySSL = 1)
	{
		if (!SSL_ENABLE_IN_BACKEND || $applySSL == 0)
		{
			return $link;
		}
		else
		{
			$url  = JURI::base();
			$link = $url . $link;
			$link = $this->getsslLink($link, $applySSL);
		}

		return $link;
	}

	public function getEconomicAccountGroup($accountgroup_id = 0, $front = 0)
	{
		$and = '';

		if ($accountgroup_id != 0)
		{
			$and .= 'AND ea.accountgroup_id="' . $accountgroup_id . '" ';
		}

		if ($front != 0)
		{
			$and .= 'AND ea.published="1" ';
		}

		$query = 'SELECT ea.*, ea.accountgroup_id AS value, ea.accountgroup_name AS text FROM ' . $this->_table_prefix . 'economic_accountgroup AS ea '
			. 'WHERE 1=1 '
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function isredProductfinder()
	{
		$user = JFactory::getUser();

		// Get redshop from joomla component table
		$query = "SELECT enabled FROM `#__extensions` WHERE `element` LIKE '%com_redproductfinder%'";
		$this->_db->setQuery($query);
		$redproductfinder      = $this->_db->loadobject();
		$redproductfinder_path = JPATH_ADMINISTRATOR . '/components/com_redproductfinder';

		if (!is_dir($redproductfinder_path) || $redproductfinder->enabled == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * check redCRM is installed or not.
	 *
	 * TODO: set session variable 'isredcrmuser'
	 * Set as boolean - check login user is redCRM contact person as well
	 *
	 * @return   boolean
	 *
	 * @since    1.0
	 */
	public function isredCRM()
	{
		$session = JFactory::getSession();

		// Get redshop from joomla component table
		$isredCRM = $session->get('isredCRM');

		if (is_null($isredCRM) && !empty($isredCRM))
		{
			$query = "SELECT extension_id FROM `#__extensions` WHERE `element` LIKE '%com_redcrm%'";
			$this->_db->setQuery($query);
			$this->_isredCRM = $this->_db->loadResult();
		}

		$redcrm_path = JPATH_ADMINISTRATOR . '/components/com_redcrm';

		if (!is_dir($redcrm_path) && !$this->_isredCRM)
		{
			$this->_isredCRM = false;
		}
		else
		{
			$user = JFactory::getUser();
			require_once JPATH_ADMINISTRATOR . '/components/com_redcrm/helpers/configuration.php';
			$crmConfig = new crmConfig;
			$crmConfig->config();
			require_once JPATH_ADMINISTRATOR . '/components/com_redcrm/helpers/helper.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_redcrm/helpers/debitor.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_redcrm/helpers/product.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_redcrm/helpers/supplier_order.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_redcrm/helpers/order.php';

			$crmHelper = new crmHelper;

			$session = JFactory::getSession();

			if ($crmHelper->isredCRMUser($user->id))
			{
				$session->set('isredcrmuser', true);
			}
			else
			{
				$session->set('isredcrmuser', false);
			}

			$session->set('isredcrmuser_debitor', $crmHelper->isredCRMUserdebitor($user->id));

			$this->_isredCRM = true;
		}

		$session->set('isredCRM', $this->_isredCRM);

		return $this->_isredCRM;
	}
}
