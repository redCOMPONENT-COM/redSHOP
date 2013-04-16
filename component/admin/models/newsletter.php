<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_ROOT . '/components/com_redshop/helpers/product.php';
require_once JPATH_COMPONENT . '/helpers/text_library.php';

class newsletterModelnewsletter extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	public $_context = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();
		$this->_context = 'newsletter_id';
		$this->_table_prefix = '#__redshop_';
		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
		$filter = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('filter', $filter);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
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

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$filter = $this->getState('filter');
		$where = '';

		if ($filter)
		{
			$where = " WHERE n.name like '%" . $filter . "%' ";
		}

		$query = 'SELECT distinct(n.newsletter_id),n.* FROM ' . $this->_table_prefix . 'newsletter AS n '
			. 'WHERE 1=1 '
			. $where
			. $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();

		$filter_order = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', 'newsletter_id');
		$filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

		return $orderby;
	}

	public function getnewsletter_content($newsletter_id)
	{
		$query = 'SELECT n.template_id,n.body,n.subject,nt.template_desc FROM ' . $this->_table_prefix . 'newsletter AS n '
			. 'LEFT JOIN ' . $this->_table_prefix . 'template AS nt ON n.template_id=nt.template_id '
			. 'WHERE n.published=1 '
			. 'AND n.newsletter_id="' . $newsletter_id . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getnewsletterproducts_content()
	{
		$query = 'SELECT nt.template_desc FROM ' . $this->_table_prefix . 'template as nt '
			. 'WHERE nt.template_section="newsletter_product" ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function getProductIdList()
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function noofsubscribers($nid)
	{
		$query = 'SELECT count(*) FROM ' . $this->_table_prefix . 'newsletter_subscription WHERE newsletter_id=' . $nid . ' AND published=1';
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
			$query = "SELECT field_id FROM " . $this->_table_prefix . "fields WHERE field_name like 'field_city' ";
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
			$query = 'SELECT uf.firstname,uf.lastname,u.username,ns.* FROM ' . $this->_table_prefix . 'newsletter_subscription AS ns '
				. ', ' . $this->_table_prefix . 'users_info AS uf '
				. ', #__users AS u '
				. ', ' . $this->_table_prefix . 'fields_data AS f '
				. 'WHERE ns.newsletter_id="' . $n . '" '
				. 'AND ns.published=1 AND u.id=ns.user_id '
				. 'AND ns.user_id=uf.user_id '
				. 'AND uf.address_type LIKE "BT" '
				. $where . $between . $country;
		}
		else
		{
			$query = "SELECT ns.*,u.username,uf.address_type,uf.firstname,uf.lastname FROM " . $this->_table_prefix . "newsletter_subscription AS ns "
				. "LEFT JOIN #__users AS u ON ns.user_id = u.id "
				. "LEFT JOIN " . $this->_table_prefix . "users_info AS uf ON uf.user_id = ns.user_id "
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
		$query = 'SELECT IFNULL(u.email,s.email) AS email,IFNULL(u.username,s.name) AS username FROM '
			. $this->_table_prefix . 'newsletter_subscription AS s '
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
			$query = "SELECT * FROM " . $this->_table_prefix . "product_category_xref AS pcx "
				. "LEFT JOIN " . $this->_table_prefix . "order_item AS oi ON pcx.product_id = oi.product_id "
				. "LEFT JOIN " . $this->_table_prefix . "orders AS o ON o.order_id = oi.order_id "
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
			$query = "SELECT o.* FROM " . $this->_table_prefix . "orders AS o "
				. "LEFT JOIN " . $this->_table_prefix . "order_item AS oi ON o.order_id=oi.order_id "
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
		$number_order = JRequest::getVar('number_order');
		$oprand = JRequest::getVar('oprand', 'select');

		$start = JRequest::getVar('total_start', '');
		$end = JRequest::getVar('total_end', '');
		$order_total = '';

		if ($start != '' && $end != '')
		{
			$order_total = " or order_total between " . $start . " and " . $end;
		}
		if ($oprand != 'select')
		{
			$cond = $oprand . $number_order;
		}
		else
		{
			$cond = "=" . "''";
		}
		$query = "SELECT COUNT(*) AS total,order_total FROM " . $this->_table_prefix . "orders "
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
		$query = "SELECT country_3_code as value, country_name as text from " . $this->_table_prefix . "country";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getProduct()
	{
		$query = "SELECT product_name as text, product_id as value from " . $this->_table_prefix . "product"
			. " ORDER BY product_id	";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getShopperGroup()
	{
		$query = "SELECT shopper_group_id as value,shopper_group_name as text FROM `" . $this->_table_prefix . "shopper_group`	";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getShoppers($shopperid)
	{
		$query = "SELECT * FROM `" . $this->_table_prefix . "users_info` WHERE `shopper_group_id` IN (" . $shopperid . ")";
		$this->_db->setQuery($query);
		$data = $this->_db->loadObjectlist();
		$userid = array();

		for ($d = 0; $d < count($data); $d++)
		{
			$userid[] = $data[$d]->user_id;
		}

		$uids = implode(",", $userid);

		return $uids;
	}

	public function getNewsletterSubscriber($newsletter_id, $subscription_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "newsletter_subscription "
			. "where newsletter_id='" . $newsletter_id . "' "
			. "AND subscription_id='" . $subscription_id . "' ";

		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	public function newsletterEntry($cid = array(), $userid = array(), $username = array())
	{
		$producthelper = new producthelper;
		$jconfig = new jconfig;
		$db = JFactory::getDBO();
		$newsletter_id = JRequest::getVar('newsletter_id');

		$uri = JURI::getInstance();
		$url = $uri->root();

		$mailfrom = $jconfig->mailfrom;
		$fromname = $jconfig->fromname;

		if (NEWS_MAIL_FROM != "")
		{
			$mailfrom = NEWS_MAIL_FROM;
		}
		if (NEWS_FROM_NAME != "")
		{
			$fromname = NEWS_FROM_NAME;
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
		$dispatcher = JDispatcher::getInstance();
		$x = array();
		$results = $dispatcher->trigger('onPrepareContent', array(&$o, &$x, 1));
		$newsletter_template2 = $o->text;

		$content = str_replace("{data}", $newsletter_template2, $newsletter_template);

		$product_id_list = $this->getProductIdList();

		for ($i = 0; $i < count($product_id_list); $i++)
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
					$thum_image = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/"
						. $product_id_list[$i]->product_full_image . "' title='' rel=\"lightbox[product7]\">";
					$thum_image .= "<img id='main_image' src='" . $url . "/components/com_redshop/helpers/thumb.php?filename=product/"
						. $product_id_list[$i]->product_full_image . "&newxsize=" . PRODUCT_MAIN_IMAGE . "&newysize=" . PRODUCT_MAIN_IMAGE . "'>";
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

		// If the template contains the images, then revising the path of the images,
		// So the full URL goes with the mail, so images are visible in the mails.
		$data1 = $data = $content;

		preg_match_all("/\< *[img][^\>]*[.]*\>/i", $data, $matches);
		$imagescurarray = array();

		foreach ($matches[0] as $match)
		{
			preg_match_all("/(src|height|width)*= *[\"\']{0,1}([^\"\'\ \>]*)/i", $match, $m);
			$images[] = array_combine($m[1], $m[2]);
			$imagescur = array_combine($m[1], $m[2]);
			$imagescurarray[] = $imagescur['src'];
		}
		$imagescurarray = array_unique($imagescurarray);

		if ($imagescurarray)
		{
			foreach ($imagescurarray as $change)
			{
				if (strpos($change, 'http') === false)
				{
					$data1 = str_replace($change, $url . $change, $data1);
				}
			}
		}

		$retsubscriberid = array();

		for ($j = 0; $j < count($cid); $j++)
		{
			$subscriberinfo = $this->subscribersinfo($cid[$j]);

			if (count($subscriberinfo) > 0)
			{
				$today = time();
				$subscribe_email = trim($subscriberinfo[0]->email);

				$unsub_link = $url . 'index.php?option=com_redshop&view=newsletter&task=unsubscribe&email1=' . $subscribe_email;

				$query = "INSERT INTO `" . $this->_table_prefix . "newsletter_tracker` "
					. "(`tracker_id`, `newsletter_id`, `subscription_id`, `subscriber_name`, `user_id` , `read`, `date`)  "
					. "VALUES ('', '" . $newsletter_id . "', '" . $cid[$j] . "', '" . $username[$j] . "', '" . $userid[$j] . "',0, '" . $today . "')";
				$db->setQuery($query);
				$db->query();
				$content = '<img  src="' . $url . 'components/com_redshop/helpers/newsletteropener.php?tracker_id='
					. $db->insertid() . '" style="display:none;" />';

				// Replacing the tags with the values
				$content .= str_replace("{username}", $subscriberinfo[0]->username, $data1);
				$content = str_replace("{email}", $subscribe_email, $content);


				$unsubscriberlink = "<a href='" . $unsub_link . "'>" . JText::_('COM_REDSHOP_UNSUBSCRIBE') . "</a>";
				$content = str_replace("{unsubscribe_link}", $unsubscriberlink, $content);
				$message = $content;

				if ($subscribe_email != "")
				{
					if (JUtility::sendMail($mailfrom, $fromname, $subscribe_email, $subject, $message, 1))
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
