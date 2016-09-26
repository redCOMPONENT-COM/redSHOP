<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


class RedshopModelNewsletter extends RedshopModel
{
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'newsletter_id', $direction = '')
	{
		$filter = $this->getUserStateFromRequest($this->context . '.filter', 'filter', '');
		$this->setState('filter', $filter);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$filter = $this->getState('filter');
		$where = '';

		if ($filter)
		{
			$where = " AND n.name like '%" . $filter . "%' ";
		}

		$query = 'SELECT distinct(n.newsletter_id),n.* FROM #__redshop_newsletter AS n '
			. 'WHERE 1=1 '
			. $where
			. $orderby;

		return $query;
	}

	public function getnewsletter_content($newsletter_id)
	{
		$query = 'SELECT n.template_id,n.body,n.subject,nt.template_desc FROM #__redshop_newsletter AS n '
			. 'LEFT JOIN #__redshop_template AS nt ON n.template_id=nt.template_id '
			. 'WHERE n.published=1 '
			. 'AND n.newsletter_id="' . $newsletter_id . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getnewsletterproducts_content()
	{
		$query = 'SELECT nt.template_desc FROM #__redshop_template as nt '
			. 'WHERE nt.template_section="newsletter_product" ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function getProductIdList()
	{
		$query = 'SELECT * FROM #__redshop_product WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function noofsubscribers($nid)
	{
		$query = 'SELECT count(*) FROM #__redshop_newsletter_subscription WHERE newsletter_id=' . (int) $nid . ' AND published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function listallsubscribers($n = 0)
	{
		$post = JRequest::get('post');
		$where = "";

		$zipstart = (isset($post['zipstart'])) ? $post['zipstart'] : "";
		$zipend = (isset($post['zipend'])) ? $post['zipend'] : "";
		$cityfilter = (isset($post['cityfilter'])) ? $post['cityfilter'] : "";
		$newsletter_id = (isset($post['newsletter_id'])) ? $post['newsletter_id'] : 0;
		$between = "";
		$start_date = (isset($post['start_date'])) ? $post['start_date'] : "";
		$end_date = (isset($post['end_date'])) ? $post['end_date'] : "";
		$country_value = (isset($post['country'])) ? $post['country'] : "";
		$country = "";

		if ($country_value)
		{
			$country_code = implode("','", $country_value);

			if ($country_code != '')
			{
				$country = "  AND uf.country_code in('" . $country_code . "') ";
			}
		}

		if ($newsletter_id != 0)
		{
			$n = $newsletter_id;
		}

		if ($cityfilter != "")
		{
			// City field filter
			$query = "SELECT field_id FROM #__redshop_fields WHERE field_name like 'field_city' ";
			$this->_db->setQuery($query);
			$cityfieldid = $this->_db->loadResult();

			// City field filter end
			$where = " AND f.fieldid in(" . $cityfieldid . ") AND f.section in(7) AND f.data_txt like '"
				. $cityfilter . "%' AND f.itemid=uf.users_info_id";
		}

		if ($start_date && $end_date)
		{
			$between = " AND cast(u.registerDate as date)  between '" . $start_date . "' and '" . $end_date . "' ";
		}

		if ($zipstart != "")
		{
			$where = " AND uf.zipcode like '$zipstart%'";
		}

		if ($zipstart != "" && $zipend != "")
		{
			$where = " AND (uf.zipcode like '$zipstart%' OR uf.zipcode like '$zipend%')";
		}

		// Shopper group filter
		$shopper_group_ids = "";

		if (isset($post['shoppergroups']) && count($post['shoppergroups']) > 0 && isset($post['checkoutshoppers']))
		{
			$shoppergroupids = implode("','", $post['shoppergroups']);
			$shopper_group_ids = "  AND uf.shopper_group_id IN ('" . $shoppergroupids . "') ";
		}

		if ($cityfilter != "")
		{
			$query = 'SELECT uf.firstname,uf.lastname,u.username,ns.* FROM #__redshop_newsletter_subscription AS ns '
				. ', #__redshop_users_info AS uf '
				. ', #__users AS u '
				. ', #__redshop_fields_data AS f '
				. 'WHERE ns.newsletter_id="' . $n . '" '
				. 'AND ns.published=1 AND u.id=ns.user_id '
				. 'AND ns.user_id=uf.user_id '
				. 'AND uf.address_type LIKE "BT" '
				. $where . $between . $country;
		}
		else
		{
			$query = "SELECT ns.*,u.username,uf.address_type,uf.firstname,uf.lastname FROM #__redshop_newsletter_subscription AS ns "
				. "LEFT JOIN #__users AS u ON ns.user_id = u.id "
				. "LEFT JOIN #__redshop_users_info AS uf ON uf.user_id = ns.user_id "
				. "WHERE (uf.address_type = 'BT' OR uf.address_type IS NULL) "
				. "AND ns.newsletter_id='" . $n . "' "
				. "AND published=1 "
				. $where . $between . $country . $shopper_group_ids;
		}

		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function subscribersinfo($subscriberid)
	{
		$query = 'SELECT IFNULL(u.email,s.email) AS email,IFNULL(u.username,s.name) AS username FROM #__redshop_newsletter_subscription AS s '
			. 'LEFT JOIN #__users as u ON  u.id=s.user_id '
			. 'WHERE s.subscription_id="' . $subscriberid . '" '
			. 'AND published=1 ';

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function category($uid)
	{
		$return = 1;
		$categories = JRequest::getVar('product_category');

		if (count($categories) > 0)
		{
			$categories_ids = implode("','", $categories);
			$query = "SELECT * FROM #__redshop_product_category_xref AS pcx "
				. "LEFT JOIN #__redshop_order_item AS oi ON pcx.product_id = oi.product_id "
				. "LEFT JOIN #__redshop_orders AS o ON o.order_id = oi.order_id "
				. "WHERE o.user_id='" . $uid . "' "
				. "AND category_id IN ('" . $categories_ids . "') ";
			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectlist();

			if (count($result) <= 0)
			{
				$return = 0;
			}
		}

		return $return;
	}

	public function product($user_id)
	{
		$return = 1;
		$product = JRequest::getVar('product');

		if (count($product) > 0)
		{
			$product_ids = implode("','", $product);
			$query = "SELECT o.* FROM #__redshop_orders AS o "
				. "LEFT JOIN #__redshop_order_item AS oi ON o.order_id=oi.order_id "
				. "WHERE o.user_id='" . $user_id . "' "
				. "AND product_id IN ('" . $product_ids . "') ";
			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectlist();

			if (count($result) <= 0)
			{
				$return = 0;
			}
		}

		return $return;
	}

	public function order_user($uid)
	{
		$jInput = JFactory::getApplication()->input;
		$number_order = $jInput->getInt('number_order', 0);
		$oprand = $jInput->getCmd('oprand', 'select');

		$start = JRequest::getVar('total_start', '');
		$end = JRequest::getVar('total_end', '');
		$order_total = '';

		if ($start != '' && $end != '')
		{
			$order_total = " or order_total between " . $start . " and " . $end;
		}

		switch ($oprand)
		{
			case 'more':
				$cond = '>=' . $number_order;
				break;
			case 'less':
				$cond = '<=' . $number_order;
				break;
			case 'select':
				$cond = "=" . $this->_db->quote('');
				break;
			case 'equally':
			default:
				$cond = '=' . $number_order;
				break;
		}

		$query = "SELECT COUNT(*) AS total,order_total FROM #__redshop_orders "
			. "GROUP BY user_id "
			. "HAVING total " . $cond . $order_total . " AND user_id =" . $uid;
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();

		if ($result || ($start == '' && $end == '' && $oprand == 'select'))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	public function getContry()
	{
		$query = "SELECT country_3_code as value, country_name as text from #__redshop_country";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getProduct()
	{
		$query = "SELECT product_name as text, product_id as value from #__redshop_product"
			. " ORDER BY product_id	";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getShopperGroup()
	{
		$query = "SELECT shopper_group_id as value,shopper_group_name as text FROM `#__redshop_shopper_group`	";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getShoppers($shopperid)
	{
		$query = "SELECT * FROM `#__redshop_users_info` WHERE `shopper_group_id` IN (" . $shopperid . ")";
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectlist();
		$userid = array();

		for ($d = 0, $dn = count($data); $d < $dn; $d++)
		{
			$userid[] = $data[$d]->user_id;
		}

		$uids = implode(",", $userid);

		return $uids;
	}

	public function getNewsletterSubscriber($newsletter_id, $subscription_id)
	{
		$query = "SELECT * FROM #__redshop_newsletter_subscription "
			. "where newsletter_id='" . $newsletter_id . "' "
			. "AND subscription_id='" . $subscription_id . "' ";

		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	public function newsletterEntry($cid = array(), $userid = array(), $username = array())
	{
		$producthelper = productHelper::getInstance();
		$jconfig = new jconfig;
		$db = JFactory::getDbo();
		$newsletter_id = JRequest::getVar('newsletter_id');

		$uri = JURI::getInstance();
		$url = $uri->root();

		$mailfrom = $jconfig->mailfrom;
		$fromname = $jconfig->fromname;

		if (Redshop::getConfig()->get('NEWS_MAIL_FROM') != "")
		{
			$mailfrom = Redshop::getConfig()->get('NEWS_MAIL_FROM');
		}

		if (Redshop::getConfig()->get('NEWS_FROM_NAME') != "")
		{
			$fromname = Redshop::getConfig()->get('NEWS_FROM_NAME');
		}

		// Getting newsletter content
		$newsbody = $this->getnewsletter_content($newsletter_id);

		$subject = "";
		$newsletter_body = "";
		$newsletter_template = "";

		if (count($newsbody) > 0)
		{
			$subject = $newsbody[0]->subject;
			$newsletter_body = $newsbody[0]->body;
			$newsletter_template = $newsbody[0]->template_desc;
		}

		$o = new stdClass;
		$o->text = $newsletter_body;
		JPluginHelper::importPlugin('content');
		$x = array();
		JDispatcher::getInstance()->trigger('onPrepareContent', array(&$o, &$x, 1));
		$newsletter_template2 = $o->text;

		$content = str_replace("{data}", $newsletter_template2, $newsletter_template);

		$product_id_list = $this->getProductIdList();

		for ($i = 0, $in = count($product_id_list); $i < $in; $i++)
		{
			$product_id = $product_id_list[$i]->product_id;

			if (strstr($content, '{redshop:' . $product_id . '}'))
			{
				$content = str_replace('{redshop:' . $product_id . '}', "", $content);
			}
			if (strstr($content, '{Newsletter Products:' . $product_id . '}'))
			{

				$product_id = $product_id_list[$i]->product_id;
				$newsproductbody = $this->getnewsletterproducts_content();
				$np_temp_desc = $newsproductbody[0]->template_desc;

				$thum_image = "";

				if ($product_id_list[$i]->product_full_image)
				{
					$thumbUrl = RedShopHelperImages::getImagePath(
									$product_id_list[$i]->product_full_image,
									'',
									'thumb',
									'product',
									Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
									Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);
					$thum_image = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/"
						. $product_id_list[$i]->product_full_image . "' title='' rel=\"lightbox[product7]\">";
					$thum_image .= "<img id='main_image' src='" . $thumbUrl . "'>";
					$thum_image .= "</a>";
				}

				$np_temp_desc = str_replace("{product_thumb_image}", $thum_image, $np_temp_desc);
				$np_temp_desc = str_replace("{product_price}", $producthelper->getProductFormattedPrice($product_id_list[$i]->product_price), $np_temp_desc);
				$np_temp_desc = str_replace("{product_name}", $product_id_list[$i]->product_name, $np_temp_desc);
				$np_temp_desc = str_replace("{product_desc}", $product_id_list[$i]->product_desc, $np_temp_desc);
				$np_temp_desc = str_replace("{product_s_desc}", $product_id_list[$i]->product_s_desc, $np_temp_desc);

				$content = str_replace("{Newsletter Products:" . $product_id . "}", $np_temp_desc, $content);
			}
		}

		// Replacing the Text library texts
		$texts = new text_library;
		$content = $texts->replace_texts($content);

		$redshopMail     = redshopMail::getInstance();
		$data1 = $redshopMail->imginmail($content);

		$retsubscriberid = array();

		for ($j = 0, $jn = count($cid); $j < $jn; $j++)
		{
			$subscriberinfo = $this->subscribersinfo($cid[$j]);

			if (count($subscriberinfo) > 0)
			{
				$today = time();
				$subscribe_email = trim($subscriberinfo[0]->email);

				$unsub_link = $url . 'index.php?option=com_redshop&view=newsletter&task=unsubscribe&email1=' . $subscribe_email;

				$query = "INSERT INTO `#__redshop_newsletter_tracker` "
					. "(`tracker_id`, `newsletter_id`, `subscription_id`, `subscriber_name`, `user_id` , `read`, `date`)  "
					. "VALUES ('', '" . $newsletter_id . "', '" . $cid[$j] . "', '" . $username[$j] . "', '" . $userid[$j] . "',0, '" . $today . "')";
				$db->setQuery($query);
				$db->execute();
				$content = '<img  src="' . $url . 'index.php?option=com_redshop&view=newsletter&task=tracker&tmpl=component&tracker_id=' . $db->insertid() . '" style="display:none;" />';

				// Replacing the tags with the values
				$content .= str_replace("{username}", $subscriberinfo[0]->username, $data1);
				$content = str_replace("{email}", $subscribe_email, $content);


				$unsubscriberlink = "<a href='" . $unsub_link . "'>" . JText::_('COM_REDSHOP_UNSUBSCRIBE') . "</a>";
				$content = str_replace("{unsubscribe_link}", $unsubscriberlink, $content);
				$message = $content;

				if ($subscribe_email != "")
				{
					if (JFactory::getMailer()->sendMail($mailfrom, $fromname, $subscribe_email, $subject, $message, 1))
					{
						$retsubscriberid[$j] = 1;
					}
					else
					{
						$retsubscriberid[$j] = 0;
					}
				}
			}
		}

		return $retsubscriberid;
	}
}
