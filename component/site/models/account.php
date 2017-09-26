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
 * Class accountModelaccount
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelAccount extends RedshopModel
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
		$order_functions = order_functions::getInstance();

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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__redshop_coupons')
			->where('published = 1')
			->where('userid = ' . (int) $uid)
			->where('end_date >= ' . time())
			->where('coupon_left > 0');
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function getMyDetail()
	{
		$app = JFactory::getApplication();

		$redconfig = $app->getParams();
		$start     = $app->input->getInt('limitstart', 0);
		$limit     = $redconfig->get('maxcategory');

		if (empty($this->_data))
		{
			$query = $this->_buildQuery();

			if ($query != '')
			{
				$this->_data = $this->_getList($query, $start, $limit);
			}
		}

		return $this->_data;
	}

	public function _buildQuery()
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$user   = JFactory::getUser();
		$userid = $user->id;

		$tagid		 = $app->input->getInt('tagid', 0);
		$wishlist_id = $app->input->getInt('wishlist_id', 0);
		$layout		 = $app->input->getCmd('layout', '');

		switch ($layout)
		{
			case 'mytags':

				if ($tagid != 0)
				{
					$query->select(array('ptx.product_id','p.*'))
						->leftJoin($db->quoteName('#__redshop_product', 'p') . ' ON p.product_id = ptx.product_id')
						->where('pt.tags_id = ' . (int) $tagid);
				}

				$query->select('DISTINCT pt.*')
					->from($db->quoteName('#__redshop_product_tags', 'pt'))
					->leftJoin($db->quoteName('#__redshop_product_tags_xref', 'ptx') . ' ON pt.tags_id = ptx.tags_id')
					->where('ptx.users_id = ' . (int) $userid)
					->where('pt.published = 1');

				break;
			case 'mywishlist':
				if ($userid && $wishlist_id)
				{
					$query->select('DISTINCT(' . $db->qn('w.wishlist_id') . ')')
						->select(array('w.*','p.*'))
						->from($db->quoteName('#__redshop_wishlist', 'w'))
						->leftJoin($db->quoteName('#__redshop_wishlist_product', 'pw') . ' ON w.wishlist_id = pw.wishlist_id')
						->leftJoin($db->quoteName('#__redshop_product', 'p') . ' ON p.product_id = pw.product_id')
						->where('w.user_id = ' . (int) $user->id)
						->where('w.wishlist_id = ' . (int) $wishlist_id)
						->where('pw.wishlist_id = ' . (int) $wishlist_id);
				}
				else
				{
					// Add this code to send wishlist while user is not loged in ...
					$productIds = array();

					if (isset($_SESSION["no_of_prod"]))
					{
						for ($add_i = 1; $add_i <= $_SESSION["no_of_prod"]; $add_i++)
						{
							if ($_SESSION['wish_' . $add_i]->product_id != '')
							{
								$productIds[] = (int) $_SESSION['wish_' . $add_i]->product_id;
							}
						}

						$productIds[] = (int) $_SESSION['wish_' . $add_i]->product_id;
					}

					if (!empty($productIds))
					{
						$query->select('p.*')
							->from($db->quoteName('#__redshop_product', 'p'))
							->where('p.product_id IN (' . implode(',', $productIds) . ')');
					}
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

		$start = $app->input->getInt('limitstart', 0);

		$limit = $redconfig->get('maxcategory', 5);

		if (empty($this->_pagination))
		{
			JLoader::import('joomla.html.pagination');

			$this->_pagination = new JPagination($this->getTotal(), $start, $limit);
		}

		return $this->_pagination;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();

			$this->_total = 0;

			if ($query != '')
			{
				$this->_total = $this->_getListCount($query);
			}
		}

		return $this->_total;
	}

	public function countMyTags()
	{
		$user   = JFactory::getUser();
		$userid = $user->id;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(pt.tags_id)')
			->from($db->quoteName('#__redshop_product_tags', 'pt'))
			->leftJoin($db->quoteName('#__redshop_product_tags_xref', 'ptx') . ' ON pt.tags_id = ptx.tags_id')
			->where('ptx.users_id = ' . (int) $userid)
			->where('pt.published = 1');
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Get number of wishlist
	 *
	 * @return mixed
	 *
	 * @since  2.0.2
	 */
	public function countMyWishlist()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('COUNT(*)')
			->from($db->quoteName('#__redshop_wishlist', 'pw'))
			->where('pw.user_id = ' . (int) JFactory::getUser()->id);
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function removeWishlistProduct()
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();

		$Itemid            = $app->input->getInt('Itemid', 0);
		$wishlist_id       = $app->input->getInt('wishlist_id', 0);
		$pid               = $app->input->getInt('pid', 0);
		$wishlistProductId = $app->input->getInt('wishlist_product_id', 0);

		$user = JFactory::getUser();

		// Check is user have access to wishlist
		$query = $db->getQuery(true)
			->select('wishlist_id')
			->from($db->quoteName('#__redshop_wishlist'))
			->where('user_id = ' . (int) $user->id)
			->where('wishlist_id = ' . (int) $wishlist_id);
		echo "<pre>";

		$db->setQuery($query);
		$list = $db->loadResult();

		if (count($list) > 0)
		{
			$query->clear()
				->delete($db->quoteName('#__redshop_wishlist_product'))
				->where('product_id = ' . (int) $pid)
				->where('wishlist_id = ' . (int) $wishlist_id);

			if ($wishlistProductId)
			{
				$query->where($db->qn('wishlist_product_id') . ' = ' . $wishlistProductId);
			}

			$db->setQuery($query);

			if ($db->execute())
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

		$app->redirect(JRoute::_('index.php?option=com_redshop&wishlist_id=' . $wishlist_id . '&view=account&layout=mywishlist&Itemid=' . $Itemid));
	}

	public function removeTag()
	{
		$app = JFactory::getApplication();

		$Itemid = $app->input->getInt('Itemid', 0);
		$tagid  = $app->input->getInt('tagid', 0);

		if ($this->removeTags($tagid))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_TAG_DELETED_SUCCESSFULLY'));
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DELETING_TAG'));
		}

		$app->redirect(JRoute::_('index.php?option=com_redshop&view=account&layout=mytags&Itemid=' . $Itemid));
	}

	public function removeTags($tagid)
	{
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__redshop_product_tags_xref'))
			->where('tags_id = ' . (int) $tagid)
			->where('users_id = ' . (int) $user->id);
		$db->setQuery($query);

		if ($db->execute())
		{
			$query->clear()
				->select('COUNT(tags_id)')
				->from($db->quoteName('#__redshop_product_tags_xref'))
				->where('tags_id =' . (int) $tagid);
			$db->setQuery($query);

			if ($db->loadResult() == 0)
			{
				$query->clear()
					->delete($db->quoteName('#__redshop_product_tags'))
					->where('tags_id = ' . (int) $tagid);
				$db->setQuery($query);

				if (!$db->execute())
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
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('tags_name')
			->from($db->quoteName('#__redshop_product_tags'))
			->where('tags_id = ' . (int) $tagid);
		$db->setQuery($query);
		$list = $db->loadResult();

		return $list;
	}

	public function editTag($post)
	{
		$db = JFactory::getDbo();
		$query = "UPDATE " . $this->_table_prefix . "product_tags SET tags_name = "
			. $db->quote($post['tags_name']) . ' WHERE tags_id = ' . (int) $post['tags_id'];
		$db->setQuery($query);

		if (!$db->execute())
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
			. "WHERE user_id = " . (int) $user->id;

		return $this->_getList($query);
	}

	public function removeCompare()
	{
		$app = JFactory::getApplication();

		$Itemid     = $app->input->get('Itemid');
		$product_id = $app->input->getInt('pid', 0);

		$user = JFactory::getUser();

		$query = "DELETE FROM " . $this->_table_prefix . "product_compare "
			. "WHERE product_id = " . (int) $product_id . " AND user_id = " . (int) $user->id;
		$this->_db->setQuery($query);

		if ($this->_db->execute())
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_PRODUCT_DELETED_FROM_COMPARE_SUCCESSFULLY'));
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_DELETING_PRODUCT_FROM_COMPARE'));
		}

		$app->redirect(JRoute::_('index.php?option=com_redshop&view=account&layout=compare&Itemid=' . $Itemid));
	}

	public function sendWishlist($post)
	{
		$user        = JFactory::getUser();
		$redshopMail = redshopMail::getInstance();

		$wishlist_id = JFactory::getApplication()->input->getInt('wishlist_id');
		$emailto     = $post['emailto'];
		$sender      = $post['sender'];
		$email       = $post['email'];
		$subject     = $post['subject'];
		$Itemid      = $post['Itemid'];

		$producthelper = productHelper::getInstance();

		// Get data from database if not than fetch from session
		if ($user->id && $wishlist_id)
		{
			$query = "SELECT DISTINCT w.* ,p.* FROM " . $this->_table_prefix . "wishlist AS w "
				. "LEFT JOIN " . $this->_table_prefix . "wishlist_product AS pw ON w.wishlist_id=pw.wishlist_id "
				. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = pw.product_id "
				. "WHERE w.user_id = " . (int) $user->id . " "
				. "AND w.wishlist_id = " . (int) $wishlist_id . " ";
		}
		else
		{
			// Add this code to send wishlist while user is not loged in ...
			$productIds = array();

			for ($add_i = 1; $add_i < $_SESSION["no_of_prod"]; $add_i++)
			{
				$productIds[] = (int) $_SESSION['wish_' . $add_i]->product_id;
			}

			$productIds[] = (int) $_SESSION['wish_' . $add_i]->product_id;
			$query = "SELECT DISTINCT p.* FROM #__redshop_product AS p "
				. "WHERE p.product_id IN (" . implode(',', $productIds) . ")";
		}

		$MyWishlist    = $this->_getList($query);
		$data          = "";
		$mailbcc       = null;
		$wishlist_body = $redshopMail->getMailtemplate(0, "mywishlist_mail");
		$data_add = '';

		if (count($wishlist_body) > 0)
		{
			$wishlist_body = $wishlist_body[0];
			$data          = $wishlist_body->mail_body;

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
				$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT_2');
				$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH_3');
			}
			elseif (strstr($data, '{product_thumb_image_3}'))
			{
				$tag     = '{product_thumb_image_3}';
				$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT_3');
				$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH_3');
			}
			elseif (strstr($data, '{product_thumb_image_1}'))
			{
				$tag     = '{product_thumb_image_1}';
				$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT');
				$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH');
			}
			else
			{
				$tag     = '{product_thumb_image}';
				$h_thumb = Redshop::getConfig()->get('THUMB_HEIGHT');
				$w_thumb = Redshop::getConfig()->get('THUMB_WIDTH');
			}

			$temp_template = '';

			if (count($MyWishlist))
			{
				foreach ($MyWishlist as $row)
				{
					$Itemid        = RedshopHelperUtility::getItemid($row->product_id);
					$link          = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . (int) $Itemid, true, -1);
					$thum_image    = $producthelper->getProductImage($row->product_id, $link, $w_thumb, $h_thumb);
					$pname         = $row->product_name;
					$pname         = $pname;
					$wishlist_data = str_replace($tag, $thum_image, $wishlist_desc);
					$wishlist_data = str_replace('{product_name}', $pname, $wishlist_data);

					// Attribute ajax change
					if (!$row->not_for_sale)
					{
						$wishlist_data = RedshopHelperProductPrice::getShowPrice($row->product_id, $wishlist_data);
					}
					else
					{
						$wishlist_data = str_replace("{product_price}", "", $wishlist_data);
						$wishlist_data = str_replace("{price_excluding_vat}", "", $wishlist_data);
						$wishlist_data = str_replace("{product_price_table}", "", $wishlist_data);
						$wishlist_data = str_replace("{product_old_price}", "", $wishlist_data);
						$wishlist_data = str_replace("{product_price_saving}", "", $wishlist_data);
						$wishlist_data = str_replace("{product_price_saving_percentage}", "", $wishlist_data);
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
				$data_add = '';

				foreach ($MyWishlist as $row)
				{
					$data_add .= '<div class="redProductWishlist">';

					$pname = $row->product_name;
					$link  = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $Itemid);

					$thum_image = $producthelper->getProductImage($row->product_id, $link, Redshop::getConfig()->get('THUMB_WIDTH'), Redshop::getConfig()->get('THUMB_HEIGHT'));
					$data_add .= $thum_image;

					$data_add .= "<div><a href='" . $link . "' >" . $pname . "</a></div>";
					$data_add .= '</div>';
				}
			}
		}

		$data_add = $redshopMail->imginmail($data_add);

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
			. "WHERE userid = " . (int) $user->id . " AND coupon_value > 0 limit 0,1 ";
		$Data            = $this->_getList($query);
		$remain_discount = 0;

		if ($Data)
		{
			$remain_discount = $Data[0]->coupon_value;
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "product_voucher_transaction "
			. "WHERE user_id = " . (int) $user->id . " AND amount > 0 limit 0,1 ";
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
			. "WHERE pd.user_id = " . (int) $user_id . " AND o.order_payment_status = 'Paid'";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function unused_coupon_amount($user_id, $coupone_code)
	{
		$db = JFactory::getDbo();
		$query = 'SELECT coupon_value FROM ' . $this->_table_prefix . 'coupons_transaction WHERE userid ='
			. (int) $user_id . ' AND coupon_code = ' . $db->quote($coupone_code);
		$db->setQuery($query);

		return $db->loadResult();
	}
}
