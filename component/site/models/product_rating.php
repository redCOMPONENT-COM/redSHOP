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

/**
 * Class product_ratingModelproduct_rating
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class product_ratingModelproduct_rating extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		$app = JFactory::getApplication();
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$option              = JRequest::getVar('option');
		$Itemid              = JRequest::getVar('Itemid');
		$pid                 = JRequest::getInt('product_id');
		$cid                 = JRequest::getInt('cid');
	}

	public function store($data)
	{
		$user                = JFactory::getUser();
		$data['userid']      = $user->id;
		$data['email']       = $user->email;
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

		return true;
	}

	public function sendMailForReview($data)
	{
		$this->store($data);
		$producthelper = new producthelper;
		$redshopMail   = new redshopMail;
		$user          = JFactory::getUser();

		$url        = JURI::base();
		$option     = JRequest::getVar('option');
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

		$link        = JRoute::_($url . "index.php?option=" . $option . "&view=product&pid=" . $product_id . '&Itemid=' . $Itemid);
		$product_url = "<a href=" . $link . ">" . $product->product_name . "</a>";
		$data_add    = str_replace("{product_link}", $product_url, $data_add);
		$data_add    = str_replace("{product_name}", $product->product_name, $data_add);
		$data_add    = str_replace("{title}", $message, $data_add);
		$data_add    = str_replace("{comment}", $comment, $data_add);
		$data_add    = str_replace("{username}", $username, $data_add);

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

	public function getuserfullname($uid)
	{
		$db = JFactory::getDBO();

		$query = "SELECT firstname,lastname from " . $this->_table_prefix . "users_info WHERE user_id=" . (int) $uid . " AND address_type like 'BT'";
		$db->setQuery($query);
		$userfullname = $db->loadObject();

		return $userfullname;
	}

	public function checkRatedProduct($pid, $uid)
	{
		$db    = JFactory::getDBO();
		$query = "SELECT count(*) as rec from " . $this->_table_prefix . "product_rating WHERE product_id=" . (int) $pid . " AND userid=" . (int) $uid;
		$db->setQuery($query);
		$already_rated = $db->loadResult();

		return $already_rated;
	}
}
