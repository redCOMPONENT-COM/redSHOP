<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

use Joomla\Registry\Registry;


class RedshopModelConfiguration extends RedshopModel
{
	public $configData = null;

	public $Redconfiguration = null;

	public function __construct()
	{
		parent::__construct();

		$this->Redconfiguration = Redconfiguration::getInstance();
	}

	public function store($data)
	{
		// Product Default Image upload
		$productImg = JRequest::getVar('productImg', null, 'files', 'array');

		if ($productImg['name'] != "")
		{
			$filetype = JFile::getExt($productImg['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["product_default_image"] = RedShopHelperImages::cleanFileName($productImg['name'], 'productdefault');

				$src = $productImg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data["product_default_image"];

				if ($data['product_default_image'] != "" && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_default_image']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_default_image']);
				}

				JFile::upload($src, $dest);
			}
		}

		// 	Watermark Image upload
		$watermarkImg = JRequest::getVar('watermarkImg', null, 'files', 'array');

		if ($watermarkImg['name'] != "")
		{
			$filetype = JFile::getExt($watermarkImg['name']);

			if ($filetype == 'gif' || $filetype == 'png')
			{
				$data["watermark_image"] = RedShopHelperImages::cleanFileName($watermarkImg['name'], 'watermark');

				$src = $watermarkImg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data["watermark_image"];

				if ($data['watermark_image'] != "" && JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['watermark_image']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['watermark_image']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Shopper Group default portal upload
		$default_portalLogo = JRequest::getVar('default_portal_logo', null, 'files', 'array');

		if ($default_portalLogo['name'] != "")
		{
			$filetype = JFile::getExt($default_portalLogo['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$logoname = RedShopHelperImages::cleanFileName($default_portalLogo['name']);
				$data["default_portal_logo"] = $logoname;
				$src = $default_portalLogo['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $logoname;

				if ($data['default_portal_logo_tmp'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $data['default_portal_logo_tmp']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $data['default_portal_logo_tmp']);
				}

				JFile::upload($src, $dest);
			}
		}
		else
		{
			$data["default_portal_logo"] = $data['default_portal_logo_tmp'];
		}

		// Product image which is out of stock
		$productoutofstockImg = JRequest::getVar('productoutofstockImg', null, 'files', 'array');

		if ($productoutofstockImg['name'] != "")
		{
			$filetype = JFile::getExt($productoutofstockImg['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["product_outofstock_image"] = $productoutofstockImg['name'];

				$src = $productoutofstockImg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $productoutofstockImg['name'];

				if ($data['product_outofstock_image'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_outofstock_image']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_outofstock_image']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Category Default Image upload
		$categoryImg = JRequest::getVar('categoryImg', null, 'files', 'array');

		if ($categoryImg['name'] != "")
		{
			$filetype = JFile::getExt($categoryImg['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["category_default_image"] = $categoryImg['name'];

				$src = $categoryImg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $categoryImg['name'];

				if ($data['category_default_image'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['category_default_image']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['categoryt_default_image']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Cart image upload
		$cartimg = JRequest::getVar('cartimg', null, 'files', 'array');

		if ($cartimg['name'] != "")
		{
			$filetype = JFile::getExt($cartimg['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["addtocart_image"] = $cartimg['name'];

				$src = $cartimg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $cartimg['name'];

				if ($data['addtocart_image'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['addtocart_image']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['addtocart_image']);
				}

				JFile::upload($src, $dest);
			}
		}

		$quoteimg = JRequest::getVar('quoteimg', null, 'files', 'array');

		if ($quoteimg['name'] != "")
		{
			$filetype = JFile::getExt($quoteimg['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["requestquote_image"] = $quoteimg['name'];

				$src = $quoteimg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $quoteimg['name'];

				if ($data['requestquote_image'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['requestquote_image']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['requestquote_image']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Cart delete image upload
		$cartdelete = JRequest::getVar('cartdelete', null, 'files', 'array');

		if ($cartdelete['name'] != "")
		{
			$filetype = JFile::getExt($cartdelete['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["addtocart_delete"] = $cartdelete['name'];

				$src = $cartdelete['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $cartdelete['name'];

				if ($data['addtocart_delete'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['addtocart_delete']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['addtocart_delete']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Cart update image upload
		$cartupdate = JRequest::getVar('cartupdate', null, 'files', 'array');

		if ($cartupdate['name'] != "")
		{
			$filetype = JFile::getExt($cartupdate['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["addtocart_update"] = $cartupdate['name'];

				$src = $cartupdate['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $cartupdate['name'];

				if ($data['addtocart_update'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['addtocart_update']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['addtocart_update']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Pre Order image upload
		$preorderimg = JRequest::getVar('file_pre_order_image', null, 'files', 'array');

		if ($preorderimg['name'] != "")
		{
			$filetype = JFile::getExt($preorderimg['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["pre_order_image"] = $preorderimg['name'];

				$src = $preorderimg['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $preorderimg['name'];

				if ($data['pre_order_image'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['pre_order_image']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['pre_order_image']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Image next link
		$imgnext = JRequest::getVar('imgnext', null, 'files', 'array');

		if ($imgnext['name'] != "")
		{
			$filetype = JFile::getExt($imgnext['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["image_next_link"] = $imgnext['name'];

				$src = $imgnext['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $imgnext['name'];

				if ($data['image_next_link'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['image_next_link']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['image_next_link']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Image previous link
		$imgpre = JRequest::getVar('imgpre', null, 'files', 'array');

		if ($imgpre['name'] != "")
		{
			$filetype = JFile::getExt($imgpre['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["image_previous_link"] = $imgpre['name'];

				$src = $imgpre['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $imgpre['name'];

				if ($data['image_previous_link'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['image_previous_link']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data['image_previous_link']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Product Detail Lightbox close button Image Start
		$imgpre = JRequest::getVar('imgslimbox', null, 'files', 'array');

		if ($imgpre['name'] != "")
		{
			$filetype = JFile::getExt($imgpre['name']);

			if ($filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif' || $filetype == 'png')
			{
				$data["product_detail_lighbox_close_button_image"] = $imgpre['name'];

				$src = $imgpre['tmp_name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . $imgpre['name'];

				if ($data['product_detail_lighbox_close_button_image'] != ""
					&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . $data['product_detail_lighbox_close_button_image']))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . $data['product_detail_lighbox_close_button_image']);
				}

				JFile::upload($src, $dest);
			}
		}

		// Product Detail Lightbox close button Image End
		// Save the HTML tags into the tables
		$data["welcomepage_introtext"] = JRequest::getVar('welcomepage_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["category_frontpage_introtext"] = JRequest::getVar('category_frontpage_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["registration_introtext"] = JRequest::getVar('registration_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["registration_comp_introtext"] = JRequest::getVar('registration_comp_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["vat_introtext"] = JRequest::getVar('vat_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["welcomepage_introtext"] = JRequest::getVar('welcomepage_introtext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["product_expire_text"] = JRequest::getVar('product_expire_text', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["cart_reservation_message"] = JRequest::getVar('cart_reservation_message', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["with_vat_text_info"] = JRequest::getVar('with_vat_text_info', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["without_vat_text_info"] = JRequest::getVar('without_vat_text_info', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data["show_price_user_group_list"] = @implode(",", $data['show_price_user_group_list']);
		$data["show_price_shopper_group_list"] = @implode(",", $data['show_price_shopper_group_list']);
		$data["show_price_user_group_list"] = $data["show_price_user_group_list"] ? $data["show_price_user_group_list"] : '';
		$data["show_price_shopper_group_list"] = $data["show_price_shopper_group_list"] ? $data["show_price_shopper_group_list"] : '';

		if ($data['image_quality_output'] <= 10)
		{
			$data['image_quality_output'] = 100;
		}

		if ($data['image_quality_output'] >= 100)
		{
			$data['image_quality_output'] = 100;
		}

		$data['backward_compatible_js'] = isset($data['backward_compatible_js']) ? $data['backward_compatible_js'] : 0;
		$data['backward_compatible_php'] = isset($data['backward_compatible_php']) ? $data['backward_compatible_php'] : 0;

		// Prepare post data to write
		if (!$this->configurationPrepare($data))
		{
			return false;
		}

		JFactory::getApplication()->setUserState('com_redshop.config.global.data', $this->configData);

		JPluginHelper::importPlugin('redshop');
		$dispatcher = JEventDispatcher::getInstance();
		$dispatcher->trigger('onBeforeAdminSaveConfiguration', array(&$this->configData));

		// Temporary new way to save config
		$config = Redshop::getConfig();

		try
		{
			if ($config->save(new Registry($this->configData)))
			{
				$dispatcher->trigger('onAfterAdminSaveConfiguration', array($config));
			}
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		return true;
	}

	public function configurationPrepare($d)
	{
		$this->configData = $this->Redconfiguration->redshopCFGData($d);

		return (boolean) $this->configData;
	}

	/**
	 * Method to get the configuration data.
	 *
	 * This method will load the global configuration data straight from
	 * RedshopConfig. If configuration data has been saved in the session, that
	 * data will be merged into the original data, overwriting it.
	 *
	 * @return	object  An object containing all redshop config data.
	 *
	 * @since	1.6
	 */
	public function getData()
	{
		// Get the config data.
		$data   = Redshop::getConfig()->toArray();

		// Check for data in the session.
		$temp = JFactory::getApplication()->getUserState('com_redshop.config.global.data');

		// Merge in the session data.
		if (!empty($temp))
		{
			$data = array_merge($data, $temp);
		}

		$object = new Registry($data);

		return $object;
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
	public function getCurrencies($currency = "")
	{
		$where = "";

		if ($currency)
		{
			$where = " WHERE currency_code IN ('" . $currency . "')";
		}

		$query = 'SELECT currency_code as value, currency_name as text FROM #__redshop_currency' . $where . ' ORDER BY currency_name ASC';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getnewsletters()
	{
		$query = 'SELECT newsletter_id as value,name as text FROM #__redshop_newsletter WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function getShopperGroupPrivate()
	{
		$query = "SELECT shopper_group_id as value , shopper_group_name as text "
			. " FROM #__redshop_shopper_group "
			. " WHERE `shopper_group_customer_type` = '1'";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function getShopperGroupCompany()
	{
		$query = "SELECT shopper_group_id as value , shopper_group_name as text "
			. " FROM #__redshop_shopper_group "
			. " WHERE `shopper_group_customer_type` = '0'";
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function getVatGroup()
	{
		$query = 'SELECT tg.id as value,tg.name as text FROM #__redshop_tax_group as tg WHERE tg.published=1 ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
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

	public function newsletterEntry($data)
	{
		$db = JFactory::getDbo();
		$newsletter_id = $data['default_newsletter'];
		$mailfrom = $data['news_mail_from'];
		$mailfromname = $data['news_from_name'];
		$to = $data['newsletter_test_email'];
		$producthelper = productHelper::getInstance();
		$uri = JURI::getInstance();
		$url = $uri->root();

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
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$x = array();
		$dispatcher->trigger('onPrepareContent', array(&$o, &$x, 0));
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

		$to = trim($to);
		$today = time();

		// Replacing the tags with the values
		$name = explode('@', $to);

		$query = "INSERT INTO `#__redshop_newsletter_tracker` "
			. "(`tracker_id`, `newsletter_id`, `subscription_id`, `subscriber_name`, `user_id` , `read`, `date`)  "
			. "VALUES ('', '" . $newsletter_id . "', '0', '" . $name . "', '0',0, '" . $today . "')";
		$db->setQuery($query);
		$db->execute();

		$content = '<img  src="' . $url . 'index.php?option=com_redshop&view=newsletter&task=tracker&tmpl=component&tracker_id=' . $db->insertid() . '" />';
		$content .= str_replace("{username}", $name[0], $data1);
		$content = str_replace("{email}", $to, $content);

		// Replace tag {unsubscribe_link} for testing mail to empty link, because test mail not have subscribes
		$content = str_replace("{unsubscribe_link}", "<a href=\"#\">" . JText::_('COM_REDSHOP_UNSUBSCRIBE') . "</a>", $content);

		if (JFactory::getMailer()->sendMail($mailfrom, $mailfromname, $to, $subject, $content, 1))
		{
			return true;
		}

		return false;
	}

	public function getOrderstatus()
	{
		$query = "SELECT order_status_code AS value, order_status_name AS text"
			. "\n FROM #__redshop_order_status  where published = '1'";

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	/**
	 * Handle .htaccess file for Downloadble Product root folder
	 *
	 * @param   string  $product_download_root  Path to the downloadable product root folder
	 *
	 * @deprecated  1.6      This method is deprecated and not used anywhere
	 * @return      boolean  Return true on success
	 */
	public function handleHtaccess($product_download_root)
	{
		$row_product_download_root = Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT');

		$filecontent = "";

		$assets_dir = JPATH_ROOT . 'components/com_redshop/assets';

		if (strstr($product_download_root, JPATH_ROOT) && $product_download_root != JPATH_ROOT)
		{
			$htaccessfile_path = $product_download_root . '/.htaccess';

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

		$oldhtaccessfile_path = $row_product_download_root . '/.htaccess';

		if (strstr($row_product_download_root, JPATH_ROOT) && $row_product_download_root != JPATH_ROOT)
		{
			if ($row_product_download_root != $product_download_root
				&& file_exists($oldhtaccessfile_path) && !strstr($row_product_download_root, $assets_dir))
			{
				JFile::delete($oldhtaccessfile_path);
			}
		}

		return true;
	}

	/* Get current version of redshop */
	public function getCurrentVersion()
	{
		$xmlfile = JPATH_SITE . '/administrator/components/com_redshop/redshop.xml';
		$version = JText::_('COM_REDSHOP_FILE_NOT_FOUND');

		if (file_exists($xmlfile))
		{
			$data = JApplicationHelper::parseXMLInstallFile($xmlfile);
			$version = $data['version'];
		}

		return $version;
	}

	/* Get all installed module for redshop*/
	public function getinstalledmodule()
	{
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__extensions WHERE `element` LIKE '%mod_redshop%'";
		$db->setQuery($query);
		$redshop_modules = $db->loadObjectList();

		return $redshop_modules;
	}

	/* Get all installed payment plugins for redshop*/
	public function getinstalledplugins($secion = 'redshop_payment')
	{
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__extensions WHERE `folder` = '" . $secion . "' ";
		$db->setQuery($query);
		$redshop_plugins = $db->loadObjectList();

		return $redshop_plugins;
	}

	public function resetTemplate()
	{
		$db = JFactory::getDbo();
		$q = "SELECT * FROM #__redshop_template";
		$db->setQuery($q);
		$list = $db->loadObjectList();

		for ($i = 0, $in = count($list); $i < $in; $i++)
		{
			$data = $list[$i];

			$red_template = Redtemplate::getInstance();
			$data->template_name = strtolower($data->template_name);
			$data->template_name = str_replace(" ", "_", $data->template_name);
			$tempate_file = $red_template->getTemplatefilepath($data->template_section, $data->template_name, true);

			if (JFile::exists($tempate_file))
			{
				$template_desc = $red_template->getInstallSectionTemplate($data->template_name);
				$fp = fopen($tempate_file, "w");
				fwrite($fp, $template_desc);
				fclose($fp);
			}
		}
	}
}
