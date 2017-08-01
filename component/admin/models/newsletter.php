<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


class RedshopModelNewsletter extends RedshopModel
{
	public function _buildQuery()
	{
		$orderby = $this->_buildContentOrderBy();
		$filter  = $this->getState('filter');
		$where   = '';

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

	public function noofsubscribers($nid)
	{
		$query = 'SELECT count(*) FROM #__redshop_newsletter_subscription WHERE newsletter_id=' . (int) $nid . ' AND published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/**
	 * Method for list all subscribers
	 *
	 * @param   integer $newsletterId ID of newsletter
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function listAllSubscribers($newsletterId = 0)
	{
		$input = JFactory::getApplication()->input;

		$newsletterId = $input->getInt('newsletter_id', $newsletterId);

		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn(array('uf.firstname', 'uf.lastname', 'u.username')))
			->select('ns.*')
			->from($db->qn('#__redshop_newsletter_subscription', 'ns'))
			->leftJoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('uf.user_id') . ' = ' . $db->qn('ns.user_id'))
			->leftJoin($db->qn('#__users', 'u') . ' ON ' . $db->qn('u.id') . ' = ' . $db->qn('ns.user_id'))
			->where($db->qn('ns.newsletter_id') . ' = ' . (int) $newsletterId)
			->where($db->qn('ns.published') . ' = 1');

		$zipStart      = $input->getString('zipstart', '');
		$zipEnd        = $input->getString('zipend', '');
		$filterCity    = $input->getString('cityfilter', '');
		$startDate     = $input->getString('start_date', '');
		$endDate       = $input->getString('end_date', '');
		$filterCountry = $input->get('country', array(), 'array');

		// Filter: Country
		if (!empty($filterCountry))
		{
			$query->where($db->qn('uf.country_code') . ' IN (' . implode(',', $filterCountry) . ')');
		}

		// Filter: Start date and end date
		if (!empty($startDate) && !empty($endDate))
		{
			$query->where(
				'CAST(' . $db->qn('u.registerDate') . ' AS ' . $db->qn('date') . ') '
				. 'BETWEEN ' . $db->quote($startDate) . ' AND ' . $db->quote($endDate)
			);
		}

		// Filter: zip code start
		if (!empty($zipStart))
		{
			$query->where($db->qn('uf.zipcode') . ' LIKE ' . $db->quote($zipStart . '%'));
		}

		// Filter: zip code start and end
		if (!empty($zipStart) && !empty($zipEnd))
		{
			$query->where(
				'(' . $db->qn('uf.zipcode') . ' LIKE ' . $db->quote($zipStart . '%')
				. ' OR ' . $db->qn('uf.zipcode') . ' LIKE ' . $db->quote($zipEnd . '%') . ')'
			);
		}

		// Filter: city
		if (!empty($filterCity))
		{
			$cityQuery    = $db->getQuery(true)
				->select($db->qn('field_id'))
				->from($db->qn('#__redshop_fields'))
				->where($db->qn('field_name') . ' = ' . $db->quote('field_city'));
			$cityFieldIds = $db->setQuery($cityQuery)->loadRow();

			$query->leftJoin($db->qn('#__redshop_fields_data', 'f') . ' ON ' . $db->qn('f.itemid') . ' = ' . $db->qn('ns.users_info_id'))
				->where($db->qn('uf.address_type') . ' = ' . $db->quote('BT'))
				->where($db->qn('f.fieldid') . ' IN (' . implode(',', $cityFieldIds) . ')')
				->where($db->qn('f.section') . ' = 7')
				->where($db->qn('f.data_txt') . ' LIKE ' . $db->quote($filterCity . '%'));
		}
		else
		{
			$query->select($db->qn('uf.address_type'))
				->where('(' . $db->qn('uf.address_type') . ' = ' . $db->quote('BT') . ' OR ' . $db->qn('uf.address_type') . ' IS NULL)');

			$shopperGroupFilter = $input->get('shoppergroups', array(), 'array');

			if (!empty($shopperGroupFilter))
			{
				$query->where($db->qn('uf.shopper_group_id') . ' IN (' . implode(',', $shopperGroupFilter) . ')');
			}
		}

		return $db->setQuery($query)->loadObjectList();
	}

	public function category($uid)
	{
		$return     = 1;
		$categories = JRequest::getVar('product_category');

		if (count($categories) > 0)
		{
			$categories_ids = implode("','", $categories);
			$query          = "SELECT * FROM #__redshop_product_category_xref AS pcx "
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
		$return  = 1;
		$product = JRequest::getVar('product');

		if (count($product) > 0)
		{
			$product_ids = implode("','", $product);
			$query       = "SELECT o.* FROM #__redshop_orders AS o "
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
		$jInput       = JFactory::getApplication()->input;
		$number_order = $jInput->getInt('number_order', 0);
		$oprand       = $jInput->getCmd('oprand', 'select');

		$start       = JRequest::getVar('total_start', '');
		$end         = JRequest::getVar('total_end', '');
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
		$data   = $this->_db->loadObjectlist();
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

	public function newsletterEntry($subscriberIds = array(), $userid = array(), $username = array())
	{
		$newsletterId = JFactory::getApplication()->input->getInt('newsletter_id');

		$url = JUri::root();

		$mailfrom = JFactory::getConfig()->get('mailform');
		$fromname = JFactory::getConfig()->get('fromname');

		if (Redshop::getConfig()->get('NEWS_MAIL_FROM') != "")
		{
			$mailfrom = Redshop::getConfig()->get('NEWS_MAIL_FROM');
		}

		if (Redshop::getConfig()->get('NEWS_FROM_NAME') != "")
		{
			$fromname = Redshop::getConfig()->get('NEWS_FROM_NAME');
		}

		// Getting newsletter content
		$newsletterContent = $this->getnewsletter_content($newsletterId);

		$subject            = "";
		$newsletterBody     = "";
		$newsletterTemplate = "";

		if (count($newsletterContent) > 0)
		{
			$subject            = $newsletterContent[0]->subject;
			$newsletterBody     = $newsletterContent[0]->body;
			$newsletterTemplate = $newsletterContent[0]->template_desc;
		}

		$o       = new stdClass;
		$o->text = $newsletterBody;
		JPluginHelper::importPlugin('content');
		$x = array();
		RedshopHelperUtility::getDispatcher()->trigger('onPrepareContent', array(&$o, &$x, 1));
		$newsletterTemplate2 = $o->text;

		$content = str_replace("{data}", $newsletterTemplate2, $newsletterTemplate);

		$products     = $this->getProductIdList();
		$imgWidth     = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE');
		$imgHeight    = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT');
		$sizeSwapping = Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING');

		foreach ($products as $product)
		{
			$productId = $product->product_id;

			if (strpos($content, '{redshop:' . $productId . '}') !== false)
			{
				$content = str_replace('{redshop:' . $productId . '}', "", $content);
			}

			if (strpos($content, '{Newsletter Products:' . $productId . '}') === false)
			{
				continue;
			}

			$newsProductBody = $this->getnewsletterproducts_content();
			$tmpTemplate     = $newsProductBody[0]->template_desc;

			$thumbImage = "";

			if (!empty($product->product_full_image))
			{
				$thumbUrl   = RedshopHelperMedia::getImagePath(
					$product->product_full_image, '', 'thumb', 'product', $imgWidth, $imgHeight, $sizeSwapping
				);
				$thumbImage = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/"
					. $product->product_full_image . "' title='' rel=\"lightbox[product7]\">";
				$thumbImage .= "<img id='main_image' src='" . $thumbUrl . "'>";
				$thumbImage .= "</a>";
			}

			$tmpTemplate = str_replace("{product_thumb_image}", $thumbImage, $tmpTemplate);
			$tmpTemplate = str_replace("{product_price}", RedshopHelperProductPrice::formattedPrice($product->product_price), $tmpTemplate);
			$tmpTemplate = str_replace("{product_name}", $product->product_name, $tmpTemplate);
			$tmpTemplate = str_replace("{product_desc}", $product->product_desc, $tmpTemplate);
			$tmpTemplate = str_replace("{product_s_desc}", $product->product_s_desc, $tmpTemplate);

			$content = str_replace("{Newsletter Products:" . $productId . "}", $tmpTemplate, $content);
		}

		// Replacing the Text library texts
		$content = RedshopHelperText::replaceTexts($content);
		$content = RedshopHelperMail::imgInMail($content);

		$subscribers = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true);
		$columns     = $db->qn(array('tracker_id', 'newsletter_id', 'subscription_id', 'subscriber_name', 'user_id', 'read', 'date'));
		$today       = time();

		foreach ($subscriberIds as $index => $subscriberId)
		{
			$subscriber = $this->subscribersinfo($subscriberId);

			if (empty($subscriber))
			{
				continue;
			}

			$subscriber = $subscriber[0];

			$subscribeEmail = trim($subscriber->email);

			if (empty($subscribeEmail))
			{
				continue;
			}

			$unSubscribeLink = $url . 'index.php?option=com_redshop&view=newsletter&task=unsubscribe&email1=' . $subscribeEmail;
			$values          = array('', $newsletterId, $subscriberId, $username[$index], $userid[$index], 0, $today);

			$query->clear()
				->insert($db->qn('#__redshop_newsletter_tracker'))
				->columns($columns)
				->values(implode(',', $values));
			$db->setQuery($query)->execute();

			$message = '<img src="' . $url . 'index.php?option=com_redshop&view=newsletter&task=tracker&tmpl=component&tracker_id='
				. $db->insertid() . '" style="display:none;" />';

			// Replacing the tags with the values
			$message .= str_replace("{username}", $subscriber->username, $content);
			$message = str_replace("{email}", $subscribeEmail, $message);


			$unSubscribeLink = "<a href='" . $unSubscribeLink . "'>" . JText::_('COM_REDSHOP_UNSUBSCRIBE') . "</a>";
			$message         = str_replace("{unsubscribe_link}", $unSubscribeLink, $message);

			$subscribers[$index] = (int) JFactory::getMailer()->sendMail($mailfrom, $fromname, $subscribeEmail, $subject, $message, true);
		}

		return $subscribers;
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

	public function getProductIdList()
	{
		$query = 'SELECT * FROM #__redshop_product WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function getnewsletterproducts_content()
	{
		$query = 'SELECT nt.template_desc FROM #__redshop_template as nt '
			. 'WHERE nt.template_section="newsletter_product" ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
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

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string $id A prefix for the store id.
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
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
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
}
