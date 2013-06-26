<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );

class subscriptionModelsubscription extends JModel
{
	var $_catid = null;
	var $_sid = null;
	var $_data = null;
	var $_table_prefix = null;
	var $_db = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
		//select at menu type
		$catid = JRequest::getInt ( 'cid', 0 );
		//show at product detail in subscription page
		$subid = JRequest::getInt ( 'catid', 0 );
		$this->setId ( ( int ) $catid );
		$this->setSubId ( ( int ) $subid );
		$this->_db = JFactory::getDBO();
	}

	public function setId($catid)
	{
		$this->_catid = $catid;
		$this->_data = null;
	}

	public function setSubId($subid)
	{
		$this->_sid = $subid;
		$this->_data = null;
	}

	public function getdata()
	{
		$query = $this->_db->getQuery(true);
		if($this->_catid > 0)
		{
			$query->select('cx.category_child_id');
			$query->from($this->_table_prefix.'category_xref AS cx');
			$query->where("cx.category_parent_id='".$this->_catid."' ");
		}
		else
		{
			$query->select('cx.category_child_id');
			$query->from($this->_table_prefix.'category_xref AS cx');
			$query->where('cx.category_parent_id=0');
		}
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		if(count($result) > 0)
		{
			for($i=0;$i<count($result);$i++)
			{
				$arr[] = $result[$i]->category_child_id;
			}
			$pids = implode(",",$arr);
			$query_x = $this->_db->getQuery(true);
			$query_x->select('c.*');
			$query_x->from($this->_table_prefix.'category AS c');
			$query_x->where("c.category_id IN (".$pids.") AND c.published=1 ");
			$query_x->order('c.ordering ASC');
			$this->_db->setQuery($query_x);
			$this->_data = $this->_db->loadObjectList();
		}
		if(count($this->_data) > 0)
		{

			return $this->_data;
		}
	}

	public function getUserWishlist()
	{
		$user = &JFactory::getUser ();
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from($this->_table_prefix.'wishlist');
		$query->where('user_id='.$user->id);
		$this->_db->setQuery($query);

		return  $this->_db->loadObjectList();
	}

	public function getSubscriptionTemplateID()
	{
		$query = $this->_db->getQuery(true);
		$query->select('t.template_id');
		$query->from($this->_table_prefix.'template AS t');
		$query->where("t.published=1 AND t.template_section = 'subscription_template' ");
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		if(count($result) > 0)
		{

			return $result;
		}
	}


	public function loadSubscriptionOverViewTemplate()
	{
		$redTemplate = new Redtemplate();
		$subscription_section = "subscription_template";
		$subscription_template_id = $this->getSubscriptionTemplateID();
		$this->_template = $redTemplate->getTemplate ($subscription_section, $subscription_template_id[0]->template_id );

		return $this->_template;
	}


	public function loadSubscriptionDetailTemplate()
	{
		$redTemplate = new Redtemplate();
		$subscription_section = "subscription_template";
		$subscription_template_id = $this->getSubscriptionTemplateID();
		$this->_template = $redTemplate->getTemplate ($subscription_section, $subscription_template_id[1]->template_id );

		return $this->_template;
	}

	public function getProductMainInSub()
	{
		$q  = $this->_db->getQuery(true);
		$q->select('cx.category_child_id');
		$q->from($this->_table_prefix.'category_xref AS cx');
		$q->where(" cx.category_parent_id ='".$this->_sid."' ");
		$this->_db->setQuery($q);
		$category_result = $this->_db->loadObjectList();
		if(count($category_result) > 0 )
		{
			$query = $this->_db->getQuery(true);
			$query->select('pcx.product_id');
			$query->from($this->_table_prefix.'product_category_xref AS pcx');
			$query->where("pcx.category_id='".$this->_sid."' ");
			$this->_db->setQuery($query);
			$product_ids = $this->_db->loadObjectList();
			if(count($product_ids) > 0)
			{
				for($i=0;$i<count($product_ids);$i++)
				{
					$arr[] = $product_ids[$i]->product_id;
				}
				$cids = implode(",",$arr);
				$p = $this->_db->getQuery(true);
				$p->select('DISTINCT p.*');
				$p->from($this->_table_prefix.'product AS p');
				$p->where("p.product_id IN (".$cids.")  AND p.published = 1");
				$this->_db->setQuery($p);
				$result_final_ex =  $this->_db->loadObjectList();
				if(count($result_final_ex) > 0 )
				{

					return $result_final_ex;
				}
			}
		}
		else
		{
			$q = $this->_db->getQuery(true);
			$q->select('cx.category_parent_id');
			$q->from($this->_table_prefix.'category_xref AS cx');
			$q->where("cx.category_child_id ='".$this->_sid."' ");
			$this->_db->setQuery($q);
			$category_result = $this->_db->loadResult();
			if(count($category_result) > 0 )
			{

				$query = $this->_db->getQuery(true);
				$query->select('pcx.product_id');
				$query->from($this->_table_prefix.'product_category_xref AS pcx');
				$query->where("pcx.category_id='".$category_result."' ");
				$this->_db->setQuery($query);
				$product_ids = $this->_db->loadObjectList();
				if(count($product_ids) > 0)
				{
					for($i=0;$i<count($product_ids);$i++)
					{
						$arr[] = $product_ids[$i]->product_id;
					}
					$cids = implode(",",$arr);
					$p = $this->_db->getQuery(true);
					$p->select('DISTINCT p.*');
					$p->from($this->_table_prefix.'product AS p');
					$p->where("p.product_id IN (".$cids.")  AND p.published = 1");
					$this->_db->setQuery($p);
					$result_final_ex =  $this->_db->loadObjectList();
					if(count($result_final_ex) > 0 )
					{

						return $result_final_ex;
					}
				}
			}

		}
	}

	public function getCategoryInSub()
	{
		$query = $this->_db->getQuery(true);
		$query->select('cx.category_child_id');
		$query->from($this->_table_prefix.'category_xref AS cx');
		$query->where("cx.category_parent_id ='".$this->_sid."' ");
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		if(count($result) > 0)
		{
			for($i=0;$i<count($result);$i++)
			{
				$arr[] = $result[$i]->category_child_id ;
			}
			$cids = implode(",",$arr);
			$q = $this->_db->getQuery(true);
			$q->select('rc.*');
			$q->from($this->_table_prefix.'category AS rc');
			$q->where("rc.category_id IN (".$cids.") AND rc.published=1 ");
			$q->order('rc.ordering ASC');
			$this->_db->setQuery($q);
			$result_final = $this->_db->loadObjectList();
			if(count($result_final) > 0)
			{

				return $result_final;
			}
		}
		else
		{
			$p = $this->_db->getQuery(true);
			$p->select('rc.*');
			$p->from($this->_table_prefix.'category AS rc');
			$p->where("rc.category_id = '".$this->_sid."' ");
			$this->_db->setQuery($p);
			$result_final = $this->_db->loadObjectList();

			return $result_final;
		}
	}



	// Not apply standar Joomla code
	public function getProductInSubscription($category_id)
	{
		$query = " SELECT cx.category_child_id "
				." FROM ".$this->_table_prefix."category_xref AS cx "
				." WHERE cx.category_parent_id ='".$category_id."' ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		if(count($result) > 0)
		{
			for($i=0;$i<count($result);$i++)
			{
				$arrs[] = $result[$i]->category_child_id;
			}
			$cids = implode(",",$arrs);
			$query = " SELECT c.* "
				." FROM ".$this->_table_prefix."category AS c "
				." WHERE c.category_id IN (".$cids.") AND c.published=1 "
				." ORDER BY c.ordering ASC ";
			$this->_db->setQuery($query);
			$cats= $this->_db->loadObjectList();
			if(count($cats) > 0)
			{
				return $cats;
			}
		}
		else
		{	$cats = array();
			$cats;
		}
	}

	//Not apply standar Joomla code
	public function checkProductInSubscription($category_id,$subscription12_id)
	{

		$q	 = " SELECT pcx.product_id "
			  ." FROM ".$this->_table_prefix."product_category_xref AS pcx, ".$this->_table_prefix."product AS p"
			  ." WHERE pcx.category_id='".$category_id."' AND p.product_id = pcx.product_id AND p.published = 1 ";
		$this->_db->setQuery($q);
		$product_in_category = $this->_db->loadObjectList();
		if(count($product_in_category) > 0)
		{
			for($j=0;$j<count($product_in_category);$j++)
			{
				$arrs[] = $product_in_category[$j]->product_id;
			}
		}
		$query = " SELECT s.subscription_applicable_products "
				." FROM ".$this->_table_prefix."subscription AS s "
				." WHERE s.product_id='".$subscription12_id."' ";
		$this->_db->setQuery($query);
		$subscription_12month = $this->_db->loadResult();
		$cids = explode("|",$subscription_12month);
		if(count($cids)>0 && count($arrs) > 0)
		{
			$arrResult = array_intersect($arrs, $cids);
			if(count($arrResult) > 0 )
			{
				return count($arrResult);
			}
		}
		return 0;
	}

	//Not apply standar Joomla code
	public function getProductMainType($product_id)
	{
		$query = " SELECT f.field_id "
				." FROM ".$this->_table_prefix."fields AS f "
				." WHERE f.field_name ='rs_product_type' ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		if(count($result) > 0)
		{
			$query = " SELECT fd.data_txt "
					." FROM ".$this->_table_prefix."fields_data AS fd "
					." WHERE fd.fieldid ='".$result."' AND fd.itemid ='".$product_id."' ";
			$this->_db->setQuery($query);
			$result_fn = $this->_db->loadResult();
			if(count($result_fn) > 0 )
			{
				return $result_fn;
			}
		}
	}

	//Not apply standar Joomla code
	public function getProductMainIcon($product_id)
	{
		$query = " SELECT f.field_id "
				." FROM ".$this->_table_prefix."fields AS f "
				." WHERE f.field_name ='rs_icon' ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		if(count($result) > 0)
		{
			$query = " SELECT fd.data_txt "
					." FROM ".$this->_table_prefix."fields_data AS fd "
					." WHERE fd.fieldid ='".$result."' AND fd.itemid ='".$product_id."' ";
			$this->_db->setQuery($query);
			$result_fn = $this->_db->loadResult();
			if(count($result_fn) > 0 )
			{
				return $result_fn;
			}
		}
	}

	//Not apply standar Joomla code
	public function checkProductInSubscriptionEx($product_id,$subscription12_id)
	{
		$query = " SELECT s.subscription_applicable_products "
				." FROM ".$this->_table_prefix."subscription AS s "
				." WHERE s.product_id='".$subscription12_id."' ";
		$this->_db->setQuery($query);
		$subscription_12month = $this->_db->loadResult();
		$cids = explode("|",$subscription_12month);
		if(count($cids)>0)
		{
			for($i=0;$i<count($cids);$i++)
			{
				if($product_id == $cids[$i] )
				{
					return 1;
				}
			}
		}
	}

	//Not apply standar Joomla code
	public function checkShopperGroup($user_id)
	{
		$q  	= " SELECT  user_subscription "
				 ." FROM ".$this->_table_prefix."users_info WHERE user_id ='".$user_id."' AND user_subscription <>''  " ;
		$this->_db->setQuery($q);
		$res = $this->_db->loadResult();
		if($res)
		{
			return 1;
		}
		else
		{
			$q = 'SELECT subscription_id as subscriptionid  FROM `#__jcs_user_subscr` WHERE `user_id` = "'.$user_id.'" AND extime >= CURDATE()';
			$this->_db->setQuery($q);
			$subscriptions = $this->_db->loadObjectList();
			if(count($subscriptions) > 0)
			{
				$f = true;
				foreach($subscriptions as $subscription)
				{
					if($subscription->subscriptionid == 2)
					{
						$f = false;
					}
				}
				if($f)
				{
					return 1;
				}
			}
		}

		return 0 ;
	}

	//Not apply standar Joomla code
	public function getProductInCategory($category_id)
	{
		$query = " SELECT pcx.product_id "
				." FROM ".$this->_table_prefix."product_category_xref AS pcx "
				." WHERE pcx.category_id ='".$category_id."' ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		if(count($result) > 0)
		{
			for($i=0;$i<count($result);$i++)
			{
				$arrs[] = $result[$i]->product_id;
			}
			$cids = implode(",",$arrs);
			$query = " SELECT p.* "
					." FROM ".$this->_table_prefix."product AS p "
					." WHERE  p.product_id IN (".$cids.") AND p.published = 1";
			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectList();
			if(count($result) > 0)
			{
				return $result;
			}
		}
	}

	//Not apply standar Joomla code
	public function getNameProductMedia($media_id)
	{
		$query = " SELECT m.media_name FROM ".$this->_table_prefix."media AS m "
			." WHERE  m.media_id ='".$media_id."' AND media_type='download' ";
		$this->_db->setQuery ( $query );
		return $this->_db->loadResult();
	}


	public function AddProductToCart($add_products)
	{
		$flag = false;
		$add_products = explode(",",$add_products);
		$carthelper = new rsCarthelper();
		$data = array();
		for($i=0;$i<count($add_products);$i++)
		{
			$data["quantity"] = 1;
			$data["product_id"] = $add_products[$i];
			$carthelper->addProductToCart($data);
			unset($data);
			$flag = true;
		}
		$carthelper->cartFinalCalculation();
		return $flag;
	}


	public function getNameProduct($prosub)
	{
		$query = " SELECT m.media_name FROM ".$this->_table_prefix."media AS m "
				." WHERE section_id ='".$prosub."' AND media_type='download' "
				." ORDER BY m.media_id DESC LIMIT 1";
		$this->_db->setQuery ( $query );
		return $this->_db->loadResult();
	}

}?>