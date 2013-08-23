<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.model');

require_once JPATH_ROOT . '/components/com_redshop/helpers/product.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';

/**
 * Class accountModelaccount
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class AccountModelaccount extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
	}

	public function getuseraccountinfo($uid)
	{
		$order_functions = new order_functions;

		$user = JFactory::getUser();

		$session = JFactory::getSession();

		$auth = $session->get('auth');

		$list = array();

		if ($user->id)
		{
			$list = $order_functions->getBillingAddress($user->id);
		}
		elseif ($auth['users_info_id'])
		{
			$uid  = - $auth['users_info_id'];
			$list = $order_functions->getBillingAddress($uid);
		}

		if (!empty($list))
			$list->email = $list->user_email;

		return $list;
	}

	public function usercoupons($uid)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'coupons '
			. 'WHERE published = 1 AND userid="' . $uid . '" AND end_date >=' . time() . ' AND coupon_left > 0';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getMyDetail()
	{
		$app = JFactory::getApplication();

		$redconfig = $app->getParams();
		$start     = JRequest::getVar('limitstart', 0, '', 'int');
		$limit     = $redconfig->get('maxcategory');

		if (empty($this->_data))
		{
			$query       = $this->_buildQuery();
			$this->_data = $this->_getList($query, $start, $limit);
		}

		return $this->_data;
	}

	public function _buildQuery()
	{
		$app = JFactory::getApplication();

		$user   = JFactory::getUser();
		$userid = $user->id;

		$tagid       = JRequest::getInt('tagid', 0, 'int');
		$wishlist_id = JRequest::getInt('wishlist_id');
		$layout      = JRequest::getVar('layout');

		switch ($layout)
		{
			case 'mytags':
				$query = "SELECT DISTINCT pt.* ";

				if ($tagid != 0)
				{
					$query .= " ,ptx.product_id,p.*";
				}

				$query .= "\n FROM " . $this->_table_prefix . "product_tags as pt"
					. "\n left join " . $this->_table_prefix . "product_tags_xref as ptx on pt.tags_id = ptx.tags_id ";

				if ($tagid != 0)
				{
					$query .= "\n , " . $this->_table_prefix . "product as p ";
				}

				$query .= "\n WHERE ptx.users_id =" . (int) $userid . " and pt.published = 1";

				if ($tagid != 0)
				{
					$query .= "\n AND p.product_id = ptx.product_id AND pt.tags_id =" . $this->_db->quote($tagid);
				}

				break;
			case 'mywishlist':
				if ($userid && $wishlist_id)
				{
					$query = "SELECT DISTINCT w.* ,p.* FROM " . $this->_table_prefix . "wishlist AS w "
						. "LEFT JOIN " . $this->_table_prefix . "wishlist_product AS pw ON w.wishlist_id=pw.wishlist_id "
						. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = pw.product_id "
						. "WHERE w.user_id='" . $user->id . "' "
						. "AND w.wishlist_id='" . $wishlist_id . "' and pw.wishlist_id='" . $wishlist_id . "'";
				}
				else
				{
					// Add this code to send wishlist while user is not loged in ...
					$prod_id = 0;

					if (isset($_SESSION["no_of_prod"]))
					{
						for ($add_i = 1; $add_i <= $_SESSION["no_of_prod"]; $add_i++)
						{
							if ($_SESSION['wish_' . $add_i]->product_id != '')
							{
								$prod_id .= $_SESSION['wish_' . $add_i]->product_id . ",";
							}
						}

						$prod_id .= $_SESSION['wish_' . $add_i]->product_id;
					}

					$query = "SELECT DISTINCT p.* FROM " . $this->_table_prefix . "product AS p "
						. "WHERE p.product_id IN ('" . substr_replace($prod_id, "", -1) . "') ";
				}
				break;
			default:
				$query = "";
				break;
		}

		return $query;
	}

	public function getPagination()
	{
		$app = JFactory::getApplication();

		$redconfig = $app->getParams();

		$start = JRequest::getVar('limitstart', 0, '', 'int');

		$limit = $redconfig->get('maxcategory', 5);

		if (empty($this->_pagination))
		{
			JLoader::import('joomla.html.pagination');

			$this->_pagination = new redPagination($this->getTotal(), $start, $limit);
		}

		return $this->_pagination;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();

			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function countMyTags()
	{
		$user   = JFactory::getUser();
		$userid = $user->id;
		$query  = "SELECT COUNT(pt.tags_id) FROM " . $this->_table_prefix . "product_tags AS pt "
			. "LEFT JOIN " . $this->_table_prefix . "product_tags_xref AS ptx ON pt.tags_id = ptx.tags_id "
			. "WHERE ptx.users_id='" . $userid . "' AND pt.published = 1";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function countMyWishlist()
	{
		$user   = JFactory::getUser();
		$userid = $user->id;

		$query = "SELECT * FROM " . $this->_table_prefix . "wishlist AS pw "
			. "WHERE pw.user_id = '" . $userid . "' ";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function removeWishlistProduct()
	{
		$app = JFactory::getApplication();

		$Itemid      = JRequest::getVar('Itemid');
		$option      = JRequest::getVar('option');
		$wishlist_id = JRequest::getInt('wishlist_id');
		$pid         = JRequest::getInt('pid', 0, '', 'int');

		$user = JFactory::getUser();

		// Check is user have access to wishlist
		$query = "SELECT wishlist_id FROM " . $this->_table_prefix . "wishlist "
			. "WHERE user_id='" . $user->id . "' AND wishlist_id='" . $wishlist_id . "' ";
		echo "<pre>";

		$this->_db->setQuery($query);
		$list = $this->_db->loadResult();

		if (count($list) > 0)
		{
			$query = "DELETE FROM " . $this->_table_prefix . "wishlist_product "
				. "WHERE product_id = '" . $pid . "' AND wishlist_id='" . $wishlist_id . "' ";

			$this->_db->setQuery($query);

			if ($this->_db->Query())
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_WISHLIST_PRODUCT_DELETED_SUCCESSFULLY'));
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DELETING_WISHLIST_PRODUCT'));
			}
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_YOU_DONT_HAVE_ACCESS_TO_DELETE_THIS_PRODUCT'));
		}

		$app->Redirect('index.php?option=' . $option . '&wishlist_id=' . $wishlist_id . '&view=account&layout=mywishlist&Itemid=' . $Itemid);
	}

	public function removeTag()
	{
		$app = JFactory::getApplication();

		$Itemid = JRequest::getVar('Itemid');
		$option = JRequest::getVar('option');
		$tagid  = JRequest::getVar('tagid', 0, '', 'int');

		if ($this->removeTags($tagid))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_TAG_DELETED_SUCCESSFULLY'));
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DELETING_TAG'));
		}

		$app->Redirect('index.php?option=' . $option . '&view=account&layout=mytags&Itemid=' . $Itemid);
	}

	public function removeTags($tagid)
	{
		$user = JFactory::getUser();
		$xref = "DELETE FROM " . $this->_table_prefix . "product_tags_xref "
			. "WHERE tags_id = '" . $tagid . "' AND users_id='" . $user->id . "' ";
		$this->_db->setQuery($xref);

		if ($this->_db->Query())
		{
			$check = "SELECT count(tags_id) FROM " . $this->_table_prefix . "product_tags_xref  WHERE tags_id ='" . $tagid . "' ";
			$this->_db->setQuery($check);

			if ($this->_db->loadResult() == 0)
			{
				$query = "DELETE FROM " . $this->_table_prefix . "product_tags WHERE tags_id = '" . $tagid . "' ";
				$this->_db->setQuery($query);

				if (!$this->_db->Query())
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}

		return true;
	}

	public function getMytag($tagid)
	{
		$query = "SELECT tags_name FROM " . $this->_table_prefix . "product_tags "
			. "WHERE tags_id = '" . $tagid . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadResult();

		return $list;
	}

	public function editTag($post)
	{
		$query = "UPDATE " . $this->_table_prefix . "product_tags SET tags_name = '" . $post['tags_name'] . "' WHERE tags_id = '" . $post['tags_id'] . "' ";
		$this->_db->setQuery($query);

		if (!$this->_db->Query())
		{
			return false;
		}

		return true;
	}

	public function getCompare()
	{
		$user  = JFactory::getUser();
		$query = "SELECT pc.compare_id,pc.user_id,p.* FROM " . $this->_table_prefix . "product_compare AS pc "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = pc.product_id "
			. "WHERE user_id='" . $user->id . "' ";

		return $this->_getList($query);
	}

	public function removeCompare()
	{
		$app = JFactory::getApplication();

		$Itemid     = JRequest::getVar('Itemid');
		$option     = JRequest::getVar('option');
		$product_id = JRequest::getVar('pid', 0, '', 'int');

		$user = JFactory::getUser();

		$query = "DELETE FROM " . $this->_table_prefix . "product_compare "
			. "WHERE product_id = '" . $product_id . "' AND user_id='" . $user->id . "' ";
		$this->_db->setQuery($query);

		if ($this->_db->Query())
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_PRODUCT_DELETED_FROM_COMPARE_SUCCESSFULLY'));
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DELETING_PRODUCT_FROM_COMPARE'));
		}

		$app->Redirect('index.php?option=' . $option . '&view=account&layout=compare&Itemid=' . $Itemid);
	}

	public function sendWishlist($post)
	{
		$user        = JFactory::getUser();
		$redshopMail = new redshopMail;

		$wishlist_id = JRequest::getInt('wishlist_id');
		$emailto     = $post['emailto'];
		$sender      = $post['sender'];
		$email       = $post['email'];
		$subject     = $post['subject'];
		$Itemid      = $post['Itemid'];

		$producthelper = new producthelper;

		// Get data from database if not than fetch from session
		if ($user->id && $wishlist_id)
		{
			$query = "SELECT DISTINCT w.* ,p.* FROM " . $this->_table_prefix . "wishlist AS w "
				. "LEFT JOIN " . $this->_table_prefix . "wishlist_product AS pw ON w.wishlist_id=pw.wishlist_id "
				. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = pw.product_id "
				. "WHERE w.user_id='" . $user->id . "' "
				. "AND w.wishlist_id='" . $wishlist_id . "' ";
		}
		else
		{
			// Add this code to send wishlist while user is not loged in ...
			$prod_id = "";

			for ($add_i = 1; $add_i < $_SESSION["no_of_prod"]; $add_i++)
			{
				$prod_id .= $_SESSION['wish_' . $add_i]->product_id . ",";
			}

			$prod_id .= $_SESSION['wish_' . $add_i]->product_id;
			$query = "SELECT DISTINCT p.* FROM " . $this->_table_prefix . "product AS p "
				. "WHERE p.product_id IN (" . $prod_id . ")";
		}

		$MyWishlist    = $this->_getList($query);
		$i             = 0;
		$data          = "";
		$mailbcc       = null;
		$wishlist_body = $redshopMail->getMailtemplate(0, "mywishlist_mail");

		if (count($wishlist_body) > 0)
		{
			$wishlist_body = $wishlist_body[0];
			$data          = $wishlist_body->mail_body;
			$subject       = $wishlist_body->mail_subject;

			if (trim($wishlist_body->mail_bcc) != "")
			{
				$mailbcc = explode(",", $wishlist_body->mail_bcc);
			}
		}

		if ($data)
		{
			$template_d1   = explode("{product_loop_start}", $data);
			$template_d2   = explode("{product_loop_end}", $template_d1[1]);
			$wishlist_desc = $template_d2[0];

			if (strstr($data, '{product_thumb_image_2}'))
			{
				$tag     = '{product_thumb_image_2}';
				$h_thumb = THUMB_HEIGHT_2;
				$w_thumb = THUMB_WIDTH_2;
			}
			elseif (strstr($data, '{product_thumb_image_3}'))
			{
				$tag     = '{product_thumb_image_3}';
				$h_thumb = THUMB_HEIGHT_3;
				$w_thumb = THUMB_WIDTH_3;
			}
			elseif (strstr($data, '{product_thumb_image_1}'))
			{
				$tag     = '{product_thumb_image_1}';
				$h_thumb = THUMB_HEIGHT;
				$w_thumb = THUMB_WIDTH;
			}
			else
			{
				$tag     = '{product_thumb_image}';
				$h_thumb = THUMB_HEIGHT;
				$w_thumb = THUMB_WIDTH;
			}

			$temp_template = '';

			if (count($MyWishlist))
			{
				foreach ($MyWishlist as $row)
				{
					$link          = JURI::root() . 'index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid;
					$thum_image    = $producthelper->getProductImage($row->product_id, $link, $w_thumb, $h_thumb);
					$pname         = $row->product_name;
					$pname         = $pname;
					$wishlist_data = str_replace($tag, $thum_image, $wishlist_desc);
					$wishlist_data = str_replace('{product_name}', $pname, $wishlist_data);

					// Attribute ajax change
					if (!$row->not_for_sale)
					{
						$wishlist_data = $producthelper->GetProductShowPrice($row->product_id, $wishlist_data);
					}
					else
					{
						$wishlist_data = str_replace("{product_price}", "", $wishlist_data);
						$wishlist_data = str_replace("{price_excluding_vat}", "", $wishlist_data);
						$wishlist_data = str_replace("{product_price_table}", "", $wishlist_data);
						$wishlist_data = str_replace("{product_old_price}", "", $wishlist_data);
						$wishlist_data = str_replace("{product_price_saving}", "", $wishlist_data);
					}

					$temp_template .= $wishlist_data;
				}
			}

			$data = $template_d1[0] . $temp_template . $template_d2[1];

			$name     = @ explode('@', $emailto);
			$data     = str_replace('{from}', $sender, $data);
			$data     = str_replace('{name}', $name[0], $data);
			$data     = str_replace('{from_name}', $sender, $data);
			$data_add = $data;
		}
		else
		{
			if (count($MyWishlist))
			{
				$link = JURI::root() . "index.php?tmpl=component&option=com_redshop&view=account&layout=mywishlist&mail=1";

				foreach ($MyWishlist as $row)
				{
					$data_add .= '<div class="redProductWishlist">';
					$thum_image = "";

					$pname = $row->product_name;
					$link  = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid);

					$thum_image = $producthelper->getProductImage($row->product_id, $link, THUMB_WIDTH, THUMB_HEIGHT);
					$data_add .= $thum_image;

					$pname = "<div><a href='" . $link . "' >" . $pname . "</a></div>";
					$data_add .= $pname;

					$formatted_price = $producthelper->GetProductShowPrice($row->product_id, $wishlist_data);

					// For attribute price count
					$price_add       = '<span id="pr_price">' . $formatted_price . '</span>';

					$i++;
					$data_add .= '</div>';
				}
			}
		}

		if (JFactory::getMailer()->sendMail($email, $sender, $emailto, $subject, $data_add, true, null, $mailbcc))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getReserveDiscount()
	{
		$user            = JFactory::getUser();
		$query           = "SELECT * FROM " . $this->_table_prefix . "coupons_transaction "
			. "WHERE userid='" . $user->id . "' AND coupon_value > 0 limit 0,1 ";
		$Data            = $this->_getList($query);
		$remain_discount = 0;

		if ($Data)
		{
			$remain_discount = $Data[0]->coupon_value;
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "product_voucher_transaction "
			. "WHERE user_id='" . $user->id . "' AND amount > 0 limit 0,1 ";
		$this->_db->setQuery($query);
		$Data = $this->_getList($query);

		if ($Data)
		{
			$remain_discount += $Data[0]->amount;
		}

		return $remain_discount;
	}

	public function getdownloadproductlist($user_id)
	{
		$query = "SELECT pd.*,product_name FROM " . $this->_table_prefix . "product_download AS pd "
			. "INNER JOIN " . $this->_table_prefix . "product AS p ON p.product_id=pd.product_id "
			. "INNER JOIN " . $this->_table_prefix . "orders AS o ON o.order_id=pd.order_id "
			. "WHERE pd.user_id='" . $user_id . "' AND o.order_payment_status = 'Paid'";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function unused_coupon_amount($user_id, $coupone_code)
	{
		$query = 'SELECT coupon_value FROM ' . $this->_table_prefix . 'coupons_transaction WHERE userid ="' . $user_id . '" AND coupon_code = "' . $coupone_code . '"';
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}
}
