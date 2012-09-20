<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class product_ratingModelproduct_rating extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;

	function __construct()
	{
		global $mainframe;
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$option=JRequest::getVar('option');
		$Itemid=JRequest::getVar('Itemid');
		$pid=JRequest::getInt('product_id');
		$cid=JRequest::getInt('cid');
	}

	function store($data)
	{
		$user = JFactory::getUser();
		$data['userid'] = $user->id;
		$data['email'] = $user->email;
		$data['user_rating'] = $data['user_rating'];
		$data['username'] = $data['username'];
		$data['title'] = $data['title'];
		$data['comment'] = $data['comment'];
		$data['product_id'] = $data['product_id'];
		$data['published'] = 0;
		$data['time'] = $data['time'];
			
		$row =& $this->getTable('rating_detail');

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}
	
	function sendMailForReview($data)
	{
		$this->store($data);
		$producthelper = new producthelper();
		$redshopMail = new redshopMail();
		$user = JFactory::getUser();

		$url= JURI::base();
	 	$option = JRequest::getVar('option');
	 	$Itemid = JRequest::getVar('Itemid');
		$mailbcc=NULL;
		$fromname = $data['username'];
		$from = $user->email;
		$subject = "";
		$message = $data['title'];
		$comment = $data['comment'];
		$username = $data['username'];
		$product_id = $data['product_id'];
	
		$mailbody = $redshopMail->getMailtemplate(0,"review_mail");

		$data_add = $message;
		if(count($mailbody)>0)
		{
			$data_add = $mailbody[0]->mail_body;
			$subject = $mailbody[0]->mail_subject;
			if(trim($mailbody[0]->mail_bcc)!="")
			{
				$mailbcc= explode(",",$mailbody[0]->mail_bcc);
			}
		}
		$product = $producthelper->getProductById($product_id);

		$link 	= JRoute::_( $url."index.php?option=".$option."&view=product&pid=".$product_id.'&Itemid='.$Itemid);
		$product_url = "<a href=".$link.">".$product->product_name."</a>";
		$data_add =str_replace("{product_link}",$product_url,$data_add);
		$data_add =str_replace("{product_name}",$product->product_name,$data_add);
		$data_add =str_replace("{title}",$message,$data_add);
		$data_add = str_replace("{comment}",$comment,$data_add);		
		$data_add = str_replace("{username}",$username,$data_add);
				
		if(ADMINISTRATOR_EMAIL!="")
		{
			$sendto = explode(",",ADMINISTRATOR_EMAIL);
			if(JFactory::getMailer()->sendMail($from, $fromname, $sendto, $subject, $data_add, $mode=1, NULL, $mailbcc))
			{
				return true;
			} else {
				return false;
			}
		}
	}

	function getuserfullname($uid)
	{
		$db = &JFactory::getDBO();

		$query="SELECT firstname,lastname from ".$this->_table_prefix."users_info WHERE user_id=".$uid." AND address_type like 'BT'";
		$db->setQuery($query);
		$userfullname = $db->loadObject();
		return $userfullname;
	}
	function checkRatedProduct($pid,$uid)
	{
		$db = &JFactory::getDBO();
		$query="SELECT count(*) as rec from ".$this->_table_prefix."product_rating WHERE product_id=".$pid." AND userid=".$uid;
		$db->setQuery($query);
		$already_rated = $db->loadResult();
		return $already_rated;
	}

}	?>