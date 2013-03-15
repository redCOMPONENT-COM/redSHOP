<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

jimport('joomla.filesystem.file');

require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'text_library.php');
class configurationModelconfiguration extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;
	var $_configpath = null;
	var $_configdata = null;
	var $Redconfiguration = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$this->Redconfiguration = new Redconfiguration();

		$this->_configpath = JPATH_SITE . DS . "administrator" . DS . "components" . DS . "com_redshop" . DS . "helpers" . DS . "redshop.cfg.php";
	}

	function cleanFileName($name, $id = null)
	{
		//$value = htmlspecialchars($name, ENT_QUOTES);
		$filetype = JFile::getExt($name);
		$values   = preg_replace("/[&'#]/", "", $name);


		$valuess = str_replace('_', 'and', $values);

		if (strlen($valuess) == 0)
		{
			$valuess  = $id;
			$filename = JPath::clean(time() . '_' . $valuess) . "." . $filetype; //Make the filename unique
		}
		else
		{
			$filename = JPath::clean(time() . '_' . $valuess); //Make the filename unique
		}

		return $filename;
	}

	function store($data)
	{
		# Product Default Image upload

		$productImg = JRequest::getVar('productImg', null, 'files', 'array');

		if ($productImg['name'] != "")
		{
			$filetype = JFile::getExt($productImg['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["product_default_image"] = $this->cleanFileName($productImg['name'], 'productdefault');

				$src = $productImg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $data["product_default_image"]; //specific path of the file

				if ($data['product_default_image'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $data['product_default_image']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $data['product_default_image']);

				JFile::upload($src, $dest);
			}
		}

		// 	Watermark Image upload

		$watermarkImg = JRequest::getVar('watermarkImg', null, 'files', 'array');

		if ($watermarkImg['name'] != "")
		{
			$filetype = JFile::getExt($watermarkImg['name']); //Get extension of the file

			if ($filetype == 'gif' || $filetype == 'png')
			{

				$data["watermark_image"] = $this->cleanFileName($watermarkImg['name'], 'watermark');

				$src = $watermarkImg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $data["watermark_image"]; //specific path of the file

				if ($data['watermark_image'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $data['watermark_image']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $data['watermark_image']);

				JFile::upload($src, $dest);
			}
		}

		// End

		// Shopper Group default portal upload
		$default_portalLogo = JRequest::getVar('default_portal_logo', null, 'files', 'array');

		if ($default_portalLogo['name'] != "")
		{
			$filetype = JFile::getExt($default_portalLogo['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$logoname                    = $this->cleanFileName($default_portalLogo['name']);
				$data["default_portal_logo"] = $logoname;
				$src                         = $default_portalLogo['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo' . DS . $logoname; //specific path of the file

				if ($data['default_portal_logo_tmp'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo' . DS . $data['default_portal_logo_tmp']))
					@unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo' . DS . $data['default_portal_logo_tmp']);

				JFile::upload($src, $dest);
			}
		}
		else
		{
			$data["default_portal_logo"] = $data['default_portal_logo_tmp'];
		}
		// End

		// Product image which is out of stock

		$productoutofstockImg = JRequest::getVar('productoutofstockImg', null, 'files', 'array');

		if ($productoutofstockImg['name'] != "")
		{
			$filetype = JFile::getExt($productoutofstockImg['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["product_outofstock_image"] = $productoutofstockImg['name'];

				$src = $productoutofstockImg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $productoutofstockImg['name']; //specific path of the file

				if ($data['product_outofstock_image'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $data['product_outofstock_image']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'product' . DS . $data['product_outofstock_image']);

				JFile::upload($src, $dest);
			}
		}

		// End

		// Category Default Image upload

		$categoryImg = JRequest::getVar('categoryImg', null, 'files', 'array');

		if ($categoryImg['name'] != "")
		{
			$filetype = JFile::getExt($categoryImg['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["category_default_image"] = $categoryImg['name'];

				$src = $categoryImg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $categoryImg['name']; //specific path of the file

				if ($data['category_default_image'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $data['category_default_image']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'category' . DS . $data['categoryt_default_image']);

				JFile::upload($src, $dest);
			}
		}

		// End

		// Cart image upload

		$cartimg = JRequest::getVar('cartimg', null, 'files', 'array');

		if ($cartimg['name'] != "")
		{
			$filetype = JFile::getExt($cartimg['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["addtocart_image"] = $cartimg['name'];

				$src = $cartimg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $cartimg['name']; //specific path of the file

				if ($data['addtocart_image'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['addtocart_image']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['addtocart_image']);

				JFile::upload($src, $dest);
			}
		}
		$quoteimg = JRequest::getVar('quoteimg', null, 'files', 'array');

		if ($quoteimg['name'] != "")
		{
			$filetype = JFile::getExt($quoteimg['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["requestquote_image"] = $quoteimg['name'];

				$src = $quoteimg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $quoteimg['name']; //specific path of the file

				if ($data['requestquote_image'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['requestquote_image']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['requestquote_image']);

				JFile::upload($src, $dest);
			}
		}

		// End
		// Cart delete image upload

		$cartdelete = JRequest::getVar('cartdelete', null, 'files', 'array');

		if ($cartdelete['name'] != "")
		{
			$filetype = JFile::getExt($cartdelete['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["addtocart_delete"] = $cartdelete['name'];

				$src = $cartdelete['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $cartdelete['name']; //specific path of the file

				if ($data['addtocart_delete'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['addtocart_delete']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['addtocart_delete']);

				JFile::upload($src, $dest);
			}
		}

		// End

		// Cart update image upload

		$cartupdate = JRequest::getVar('cartupdate', null, 'files', 'array');

		if ($cartupdate['name'] != "")
		{
			$filetype = JFile::getExt($cartupdate['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["addtocart_update"] = $cartupdate['name'];

				$src = $cartupdate['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $cartupdate['name']; //specific path of the file

				if ($data['addtocart_update'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['addtocart_update']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['addtocart_update']);

				JFile::upload($src, $dest);
			}
		}

		// End

		// Pre Order image upload

		$preorderimg = JRequest::getVar('file_pre_order_image', null, 'files', 'array');

		if ($preorderimg['name'] != "")
		{
			$filetype = JFile::getExt($preorderimg['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["pre_order_image"] = $preorderimg['name'];

				$src = $preorderimg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $preorderimg['name']; //specific path of the file

				if ($data['pre_order_image'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['pre_order_image']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['pre_order_image']);

				JFile::upload($src, $dest);

			}
		}

		// End

		// Cart Background image upload

		$cartback = JRequest::getVar('cartback', null, 'files', 'array');

		if ($cartback['name'] != "")
		{
			$filetype = JFile::getExt($cartback['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["addtocart_background"] = $cartback['name'];

				$src = $cartback['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $cartback['name']; //specific path of the file

				if ($data['addtocart_background'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['addtocart_background']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['addtocart_background']);

				JFile::upload($src, $dest);
			}
		}
		$quoteback = JRequest::getVar('quoteback', null, 'files', 'array');

		if ($quoteback['name'] != "")
		{
			$filetype = JFile::getExt($quoteback['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["requestquote_background"] = $quoteback['name'];

				$src = $quoteback['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $quoteback['name']; //specific path of the file

				if ($data['requestquote_background'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['requestquote_background']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['requestquote_background']);

				JFile::upload($src, $dest);
			}
		}

		// End


		// image next link

		$imgnext = JRequest::getVar('imgnext', null, 'files', 'array');

		if ($imgnext['name'] != "")
		{
			$filetype = JFile::getExt($imgnext['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["image_next_link"] = $imgnext['name'];

				$src = $imgnext['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $imgnext['name']; //specific path of the file

				if ($data['image_next_link'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['image_next_link']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['image_next_link']);

				JFile::upload($src, $dest);
			}
		}

		// End


		// image previous link

		$imgpre = JRequest::getVar('imgpre', null, 'files', 'array');

		if ($imgpre['name'] != "")
		{
			$filetype = JFile::getExt($imgpre['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["image_previous_link"] = $imgpre['name'];

				$src = $imgpre['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . DS . $imgpre['name']; //specific path of the file

				if ($data['image_previous_link'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['image_previous_link']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . DS . $data['image_previous_link']);

				JFile::upload($src, $dest);
			}
		}

		// Product Detail Lightbox close button Image Start

		$imgpre = JRequest::getVar('imgslimbox', null, 'files', 'array');


		if ($imgpre['name'] != "")
		{
			$filetype = JFile::getExt($imgpre['name']); //Get extension of the file

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{

				$data["product_detail_lighbox_close_button_image"] = $imgpre['name'];

				$src = $imgpre['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . DS . $imgpre['name']; //specific path of the file

				if ($data['product_detail_lighbox_close_button_image'] != "" && is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . DS . $data['product_detail_lighbox_close_button_image']))
					unlink(REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox' . DS . $data['product_detail_lighbox_close_button_image']);

				JFile::upload($src, $dest);
			}
		}

		//  Product Detail Lightbox close button Image End

		//Save the HTML tags into the tables
		$data["welcomepage_introtext"]        = JRequest::getVar('welcomepage_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["category_frontpage_introtext"] = JRequest::getVar('category_frontpage_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["registration_introtext"]       = JRequest::getVar('registration_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["registration_comp_introtext"]  = JRequest::getVar('registration_comp_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["vat_introtext"]                = JRequest::getVar('vat_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["order_lists_introtext"]        = JRequest::getVar('order_lists_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["order_detail_introtext"]       = JRequest::getVar('order_detail_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["welcomepage_introtext"]        = JRequest::getVar('welcomepage_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["order_receipt_introtext"]      = JRequest::getVar('order_receipt_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["product_expire_text"]          = JRequest::getVar('product_expire_text', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["cart_reservation_message"]     = JRequest::getVar('cart_reservation_message', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["with_vat_text_info"]           = JRequest::getVar('with_vat_text_info', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["without_vat_text_info"]        = JRequest::getVar('without_vat_text_info', '', 'post', 'string', JREQUEST_ALLOWRAW);


		$data["show_price_user_group_list"]    = @implode(",", $data['show_price_user_group_list']);
		$data["show_price_shopper_group_list"] = @implode(",", $data['show_price_shopper_group_list']);

		$data["show_price_user_group_list"]    = $data["show_price_user_group_list"] ? $data["show_price_user_group_list"] : '';
		$data["show_price_shopper_group_list"] = $data["show_price_shopper_group_list"] ? $data["show_price_shopper_group_list"] : '';
		if ($data['image_quality_output'] <= 10) $data['image_quality_output'] = 100;
		if ($data['image_quality_output'] >= 100) $data['image_quality_output'] = 100;


		// prepare post data to write
		if (!$this->configurationPrepare($data))
		{
			return false;
		}

		// write data to file
		if (!$this->configurationWrite())
		{
			return false;
		}

		return true;
	}

	/**
	 * Returns the "is_writeable" status of the configuration file
	 *
	 * @param void
	 *
	 * @returns boolean True when the configuration file is writeable, false when not
	 */
	function configurationWriteable()
	{
		return is_writeable($this->_configpath);
	}

	/**
	 * Returns the "is_readable" status of the configuration file
	 *
	 * @param void
	 *
	 * @returns boolean True when the configuration file is writeable, false when not
	 */
	function configurationReadable()
	{
		return is_readable($this->_configpath);
	}

	function configurationPrepare($d)
	{

		# defination file for wizard
		//$this->Redconfiguration->WriteDefFile($d);

		$this->_configdata = $this->Redconfiguration->redshopCFGData($d);

		return ( boolean ) $this->_configdata;
	}


	/**
	 * Writes the configuration file for this payment method
	 *
	 * @param array An array of objects
	 *
	 * @returns boolean True when writing was successful
	 */
	function configurationWrite()
	{

		$config = "<?php\n";
		foreach ($this->_configdata as $key => $value)
		{
			$config .= "define ('$key', '" . addslashes($value) . "');\n";
		}
		$config .= "?>";

		if ($fp = fopen($this->_configpath, "w"))
		{
			fputs($fp, $config, strlen($config));
			fclose($fp);

			return true;
		}
		else
		{
			return false;
		}
	}

	/*
	 * get Shop Currency Support
	 *
	 * @params: string $currency 	comma separated countries
	 * @return: array stdClass Array for Shop country
	 *
	 * currency_code as value
	 * currency_name as text
	 */
	function getCurrency($currency = "")
	{
		$where = "";
		if ($currency)
		{
			$where = " WHERE currency_code IN ('" . $currency . "')";
		}
		$query = 'SELECT currency_code as value, currency_name as text FROM ' . $this->_table_prefix . 'currency' . $where . ' ORDER BY currency_name ASC';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	function getnewsletters()
	{
		$query = 'SELECT newsletter_id as value,name as text FROM ' . $this->_table_prefix . 'newsletter WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	function cleardata()
	{

		$redirect = "";

		$query = "SELECT id  FROM `#__redirection` WHERE `newurl` LIKE '%com_redshop%'";
		$this->_db->setQuery($query);
		$result1 = $this->_db->loadObjectList();

		for ($i = 0; $i < count($result1); $i++)
		{

			$redirect .= $result1[$i]->id;

			if (count($result1) > 1)
				$redirect .= "','";
		}

		// shumisha 2007-03-14 URL caching : we must clear URL cache as well
		if (file_exists(JPATH_ROOT . '/components/com_sh404sef/cache/shCacheContent.php'))
			unlink(JPATH_ROOT . '/components/com_sh404sef/cache/shCacheContent.php');

		$sql = "delete from #__redirection where id in ('" . $redirect . "') ";
		$this->_db->setQuery($sql);
		$this->_db->query();

		return (count($result1));
	}

	// get private shopper group detail
	function getShopperGroupPrivate()
	{
		$query = "SELECT shopper_group_id as value , shopper_group_name as text "
			. " FROM " . $this->_table_prefix . "shopper_group "
			. " WHERE `shopper_group_customer_type` = '1'";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	// get company shopper group detail
	function getShopperGroupCompany()
	{
		$query = "SELECT shopper_group_id as value , shopper_group_name as text "
			. " FROM " . $this->_table_prefix . "shopper_group "
			. " WHERE `shopper_group_customer_type` = '0'";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	/*function getShopperGroupTaxexempt(){
		$query = "SELECT shopper_group_id as value , shopper_group_name as text "
				." FROM ".$this->_table_prefix."shopper_group "
				." WHERE `tax_exempt` = '1' ";
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}*/
	/*function getAllArticle(){
		$query = 'SELECT a.sectionid,a.catid, a.id AS value, a.title AS text ' .
								' FROM #__content AS a' .
								' WHERE a.state = 1';

		$this->_db->setQuery( $query );
		$rows = $this->_db->loadObjectList();
		$article = array();
		$j=count($rows);
		foreach($rows as $row){


			if($row->sectionid != 0 && $row->catid != 0){
				$query = 'SELECT a.id AS value, a.title AS text ' .
								' FROM #__content AS a' .
								' LEFT JOIN #__categories AS cc ON cc.id = a.catid'.
								' LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope = "content" ' .
								' LEFT JOIN #__groups AS g ON a.access = g.id' .
								' WHERE (cc.published = 1 AND s.published = 1) AND a.state = 1 '  ;
				$this->_db->setQuery( $query );

				$r = $this->_db->loadObjectList();
				$i=0;
				foreach($r as $value){
					$article[$i]['text'] = $value->text;
					$article[$i]['value'] = $value->value;
					$i++;

				}


			}else{
				$article[$j]['text'] = $row->text;
				$article[$j]['value'] = $row->value;
			}
			$j++;

		}

		return $article;
	}*/
	function getVatGroup()
	{
		$query = 'SELECT tg.tax_group_id as value,tg.tax_group_name as text FROM ' . $this->_table_prefix . 'tax_group as tg WHERE tg.published=1 ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	function getnewsletter_content($newsletter_id)
	{
		$query = 'SELECT n.template_id,n.body,n.subject,nt.template_desc FROM ' . $this->_table_prefix . 'newsletter AS n '
			. 'LEFT JOIN ' . $this->_table_prefix . 'template AS nt ON n.template_id=nt.template_id '
			. 'WHERE n.published=1 '
			. 'AND n.newsletter_id="' . $newsletter_id . '" ';
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	function getProductIdList()
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'product WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	function getnewsletterproducts_content()
	{
		$query = 'SELECT nt.template_desc FROM ' . $this->_table_prefix . 'template as nt '
			. 'WHERE nt.template_section="newsletter_product" ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	function newsletterEntry($data)
	{
		$db            = JFactory::getDBO();
		$newsletter_id = $data['default_newsletter'];
		$mailfrom      = $data['news_mail_from'];
		$mailfromname  = $data['news_from_name'];
		$to            = $data['newsletter_test_email'];
		$producthelper = new producthelper();
		$uri           =& JURI::getInstance();
		$url           = $uri->root();

		// Getting newsletter content
		$newsbody = $this->getnewsletter_content($newsletter_id);

		$subject             = "";
		$newsletter_body     = "";
		$newsletter_template = "";
		if (count($newsbody) > 0)
		{
			$subject             = $newsbody[0]->subject;
			$newsletter_body     = $newsbody[0]->body;
			$newsletter_template = $newsbody[0]->template_desc;
		}

		$o       = new stdClass();
		$o->text = $newsletter_body;
		JPluginHelper::importPlugin('content');
		$dispatcher           = & JDispatcher::getInstance();
		$x                    = array();
		$results              = $dispatcher->trigger('onPrepareContent', array(&$o, &$x, 0));
		$newsletter_template2 = $o->text;
		// $newsletter_template1=$newsletter_template1.$newsletter_template;

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

				$product_id      = $product_id_list[$i]->product_id;
				$newsproductbody = $this->getnewsletterproducts_content();
				$np_temp_desc    = $newsproductbody[0]->template_desc;

				$thum_image = "";
				if ($product_id_list[$i]->product_full_image)
				{
					$thum_image = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product_id_list[$i]->product_full_image . "' title='' rel=\"lightbox[product7]\">";
					$thum_image .= "<img id='main_image' src='" . $url . "/components/com_redshop/helpers/thumb.php?filename=product/" . $product_id_list[$i]->product_full_image . "&newxsize=" . PRODUCT_MAIN_IMAGE . "&newysize=" . PRODUCT_MAIN_IMAGE . "'>";
					$thum_image .= "</a>";
				}
				$np_temp_desc = str_replace("{product_thumb_image}", $thum_image, $np_temp_desc); //echo '<pre/>';print_r($product_id_list[$i]);exit;
				$np_temp_desc = str_replace("{product_price}", $producthelper->getProductFormattedPrice($product_id_list[$i]->product_price), $np_temp_desc);
				$np_temp_desc = str_replace("{product_name}", $product_id_list[$i]->product_name, $np_temp_desc);
				$np_temp_desc = str_replace("{product_desc}", $product_id_list[$i]->product_desc, $np_temp_desc);
				$np_temp_desc = str_replace("{product_s_desc}", $product_id_list[$i]->product_s_desc, $np_temp_desc);

				$content = str_replace("{Newsletter Products:" . $product_id . "}", $np_temp_desc, $content);
			}
		}
		//Replacing the Text library texts
		$texts   = new text_library();
		$content = $texts->replace_texts($content);


		//If the template contains the images, then revising the path of the images,
		//So the full URL goes with the mail, so images are visible in the mails.
		$data1 = $data = $content;

		preg_match_all("/\< *[img][^\>]*[.]*\>/i", $data, $matches);
		$imagescurarray = array();
		foreach ($matches[0] as $match)
		{
			preg_match_all("/(src|height|width)*= *[\"\']{0,1}([^\"\'\ \>]*)/i", $match, $m);
			$images[]         = array_combine($m[1], $m[2]);
			$imagescur        = array_combine($m[1], $m[2]);
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

		$to    = trim($to);
		$today = time();
		//replacing the tags with the values
		$name = explode('@', $to);

		$query = "INSERT INTO `" . $this->_table_prefix . "newsletter_tracker` "
			. "(`tracker_id`, `newsletter_id`, `subscription_id`, `subscriber_name`, `user_id` , `read`, `date`)  "
			. "VALUES ('', '" . $newsletter_id . "', '0', '" . $name . "', '0',0, '" . $today . "')";
		$db->setQuery($query);
		$db->query();

		$content = '<img  src="' . $url . 'components/com_redshop/helpers/newsletteropener.php?tracker_id=' . $db->insertid() . '" />';
		$content .= str_replace("{username}", $name[0], $data1);
		$content = str_replace("{email}", $to, $content);

		if (JUtility::sendMail($mailfrom, $mailfromname, $to, $subject, $content, 1))
		{
			return true;
		}

		return false;
	}

	function getOrderstatus()
	{
		$query = "SELECT order_status_code AS value, order_status_name AS text"
			. "\n FROM " . $this->_table_prefix . "order_status  where published = '1'";

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	/*
	 * handle .htaccess file for download product
	 * @param: product download root path
	 */
	function handleHtaccess($product_download_root)
	{

		$row_product_download_root = PRODUCT_DOWNLOAD_ROOT;

		$filecontent = "";

		$assets_dir = JPATH_ROOT . 'components' . DS . 'com_redshop' . DS . 'assets';

		$assets_download_dir = JPATH_ROOT . 'components' . DS . 'com_redshop' . DS . 'assets' . DS . 'download';

		if (strstr($product_download_root, JPATH_ROOT) && $product_download_root != JPATH_ROOT)
		{

			$htaccessfile_path = $product_download_root . DS . '.htaccess';

			$allow_typs = "php";
			if (strstr($product_download_root, $assets_dir))
			{

				$allow_typs = "css|js|gif|jpe?g|png|php";
			}


			$filecontent .= '<FilesMatch "\.(' . $allow_typs . ')$">' . "\n";
			$filecontent .= "order deny,allow\n";
			$filecontent .= "allow from all\n";
			$filecontent .= "</FilesMatch>\n";
			$filecontent .= "deny from all";

			if (!file_exists($htaccessfile_path) && !strstr($product_download_root, $assets_dir))
			{
				$fp = fopen($htaccessfile_path, 'w');
				fwrite($fp, $filecontent);
				fclose($fp);
			}

		}
		$oldhtaccessfile_path = $row_product_download_root . DS . '.htaccess';

		if (strstr($row_product_download_root, JPATH_ROOT) && $row_product_download_root != JPATH_ROOT)
		{
			if ($row_product_download_root != $product_download_root && file_exists($oldhtaccessfile_path) && !strstr($row_product_download_root, $assets_dir))
				unlink($oldhtaccessfile_path);
		}

		return true;
	}

	/* Get current version of redshop */
	function getcurrentversion()
	{

		$xmlfile = JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'com_redshop.xml';
		$version = JText::_('COM_REDSHOP_FILE_NOT_FOUND');

		if (file_exists($xmlfile))
		{

			$data    = JApplicationHelper::parseXMLInstallFile($xmlfile);
			$version = $data['version'];
		}

		return $version;
	}

	/* Get all installed module for redshop*/
	function getinstalledmodule()
	{
		$db    = JFactory::getDBO();
		$query = "SELECT * FROM #__extensions WHERE `element` LIKE '%mod_redshop%'";
		$db->setQuery($query);
		$redshop_modules = $db->loadObjectList();

		return $redshop_modules;
	}

	/* Get all installed payment plugins for redshop*/
	function getinstalledplugins($secion = 'redshop_payment')
	{
		$db    = JFactory::getDBO();
		$query = "SELECT * FROM #__extensions WHERE `folder` = '" . $secion . "' ";
		$db->setQuery($query);
		$redshop_plugins = $db->loadObjectList();

		return $redshop_plugins;
	}

	function resetTemplate()
	{
		$Redtemplate = new Redtemplate();
		$db          = JFactory::getDBO();
		$q           = "SELECT * FROM #__redshop_template";
		$db->setQuery($q);
		$list = $db->loadObjectList();

		for ($i = 0; $i < count($list); $i++)
		{
			$data = & $list[$i];

			$red_template        = new Redtemplate();
			$tname               = $data->template_name;
			$data->template_name = strtolower($data->template_name);
			$data->template_name = str_replace(" ", "_", $data->template_name);
			$tempate_file        = $red_template->getTemplatefilepath($data->template_section, $data->template_name, true);

			if (is_file($tempate_file))
			{
				$template_desc = $red_template->getInstallSectionTemplate($data->template_name);
				$fp            = fopen($tempate_file, "w");
				fwrite($fp, $template_desc);
				fclose($fp);
			}
		}
	}

}


