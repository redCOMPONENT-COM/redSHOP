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
	/**
	 * @var    array
	 * @since  2.0.7
	 */
	public $configData = null;

	/**
	 * @var   \Redshop\Config\App
	 * @since  2.0.7
	 */
	public $redConfiguration = null;

	/**
	 * RedshopModelConfiguration constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->redConfiguration = \Redshop\Config\App::getInstance();
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 *
	 * @since version
	 */
	public function store($data)
	{
		$jInput = JFactory::getApplication()->input;

		$this->fileUpload($data);

		// Product Detail Lightbox close button Image End
		// Save the HTML tags into the tables
		$data["welcomepage_introtext"]         = $jInput->getRaw('welcomepage_introtext');
		$data["category_frontpage_introtext"]  = $jInput->getRaw('category_frontpage_introtext');
		$data["registration_introtext"]        = $jInput->getRaw('registration_introtext');
		$data["registration_comp_introtext"]   = $jInput->getRaw('registration_comp_introtext');
		$data["vat_introtext"]                 = $jInput->getRaw('vat_introtext');
		$data["welcomepage_introtext"]         = $jInput->getRaw('welcomepage_introtext');
		$data["product_expire_text"]           = $jInput->getRaw('product_expire_text');
		$data["cart_reservation_message"]      = $jInput->getRaw('cart_reservation_message');
		$data["with_vat_text_info"]            = $jInput->getRaw('with_vat_text_info');
		$data["without_vat_text_info"]         = $jInput->getRaw('without_vat_text_info');
		$data["show_price_user_group_list"]    = implode(",", $data['show_price_user_group_list']);
		$data["show_price_shopper_group_list"] = implode(",", $data['show_price_shopper_group_list']);
		$data["show_price_user_group_list"]    = $data["show_price_user_group_list"] ? $data["show_price_user_group_list"] : '';
		$data["show_price_shopper_group_list"] = $data["show_price_shopper_group_list"] ? $data["show_price_shopper_group_list"] : '';

		if ($data['image_quality_output'] <= 10)
		{
			$data['image_quality_output'] = 100;
		}

		if ($data['image_quality_output'] >= 100)
		{
			$data['image_quality_output'] = 100;
		}

		$data['backward_compatible_js']  = isset($data['backward_compatible_js']) ? $data['backward_compatible_js'] : 0;
		$data['backward_compatible_php'] = isset($data['backward_compatible_php']) ? $data['backward_compatible_php'] : 0;

		// Prepare post data to write
		if (!$this->configurationPrepare($data))
		{
			return false;
		}

		JFactory::getApplication()->setUserState('com_redshop.config.global.data', $this->configData);

		JPluginHelper::importPlugin('redshop');
		$dispatcher = RedshopHelperUtility::getDispatcher();
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

	/**
	 * @param   array  $data  Array of data
	 *
	 * @since  2.0.7
	 */
	private function fileUpload(&$data)
	{
		$jFile = JFactory::getApplication()->input->files;

		$allowedDefaultExt = array ('jpg', 'jpeg', 'gif', 'png');

		// Product Default Image upload
		$productImg = $jFile->get('productImg');

		if ($productImg['name'] != "")
		{
			$fileType = JFile::getExt($productImg['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["product_default_image"] = RedshopHelperMedia::cleanFileName($productImg['name'], 'productdefault');

				$dest = REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $data["product_default_image"];

				// Delete old file
				if ($data['product_default_image'] != "" && JFile::exists($dest))
				{
					JFile::delete($dest);
				}

				JFile::upload($productImg['tmp_name'], $dest);
			}
		}

		// 	Watermark Image upload
		$watermarkImg = $jFile->get('watermarkImg');

		if ($watermarkImg['name'] != "")
		{
			$fileType = JFile::getExt($watermarkImg['name']);

			if ($fileType == 'gif' || $fileType == 'png')
			{
				$data["watermark_image"] = RedshopHelperMedia::cleanFileName($watermarkImg['name'], 'watermark');

				$dest = REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $data["watermark_image"];

				// Delete old file
				if ($data['watermark_image'] != "" && JFile::exists($dest))
				{
					JFile::delete($dest);
				}

				JFile::upload($watermarkImg['tmp_name'], $dest);
			}
		}

		// Shopper Group default portal upload
		$defaultPortalLogo = $jFile->get('default_portal_logo');

		if ($defaultPortalLogo['name'] != "")
		{
			$fileType = JFile::getExt($defaultPortalLogo['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["default_portal_logo"] = RedshopHelperMedia::cleanFileName($defaultPortalLogo['name']);

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $data["default_portal_logo"];

				if ($data['default_portal_logo_tmp'] != "" && $dest)
				{
					JFile::delete($dest);
				}

				JFile::upload($defaultPortalLogo['tmp_name'], $dest);
			}
		}
		else
		{
			$data["default_portal_logo"] = $data['default_portal_logo_tmp'];
		}

		// Product image which is out of stock
		$productOutOfStockImg = $jFile->get('productoutofstockImg');

		if ($productOutOfStockImg['name'] != "")
		{
			$fileType = JFile::getExt($productOutOfStockImg['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["product_outofstock_image"] = $productOutOfStockImg['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $data["product_outofstock_image"];

				if ($data['product_outofstock_image'] != "" && JFile::exists($dest))
				{
					JFile::delete($dest);
				}

				JFile::upload($productOutOfStockImg['tmp_name'], $dest);
			}
		}

		// Category Default Image upload
		$categoryImg = $jFile->get('categoryImg');

		if ($categoryImg['name'] != "")
		{
			$fileType = JFile::getExt($categoryImg['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["category_default_image"] = $categoryImg['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data["category_default_image"];

				if ($data['category_default_image'] != "" && JFile::exists($dest))
				{
					JFile::delete(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $data['categoryt_default_image']);
				}

				JFile::upload($categoryImg['tmp_name'], $dest);
			}
		}

		// Cart image upload
		$cartImage = $jFile->get('cartimg');

		if ($cartImage['name'] != "")
		{
			$fileType = JFile::getExt($cartImage['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["addtocart_image"] = $cartImage['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data["addtocart_image"];

				if ($data['addtocart_image'] != "" && JFile::exists($dest))
				{
					JFile::delete($dest);
				}

				JFile::upload($cartImage['tmp_name'], $dest);
			}
		}

		$quoteImage = $jFile->get('quoteimg');

		if ($quoteImage['name'] != "")
		{
			$fileType = JFile::getExt($quoteImage['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["requestquote_image"] = $quoteImage['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data["requestquote_image"];

				if ($data['requestquote_image'] != "" && JFile::exists($dest))
				{
					JFile::delete($dest);
				}

				JFile::upload($quoteImage['tmp_name'], $dest);
			}
		}

		// Cart delete image upload
		$cartDelete = $jFile->get('cartdelete');

		if ($cartDelete['name'] != "")
		{
			$fileType = JFile::getExt($cartDelete['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["addtocart_delete"] = $cartDelete['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data["addtocart_delete"];

				if ($data['addtocart_delete'] != "" && JFile::exists($dest)
				)
				{
					JFile::delete($dest);
				}

				JFile::upload($cartDelete['tmp_name'], $dest);
			}
		}

		// Cart update image upload
		$cartUpdate = $jFile->get('cartupdate');

		if ($cartUpdate['name'] != "")
		{
			$fileType = JFile::getExt($cartUpdate['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["addtocart_update"] = $cartUpdate['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data["addtocart_update"];

				if ($data['addtocart_update'] != "" && JFile::exists($dest))
				{
					JFile::delete($dest);
				}

				JFile::upload($cartUpdate['tmp_name'], $dest);
			}
		}

		// Pre Order image upload
		$preOrderImage = $jFile->get('file_pre_order_image');

		if ($preOrderImage['name'] != "")
		{
			$fileType = JFile::getExt($preOrderImage['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["pre_order_image"] = $preOrderImage['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data["pre_order_image"];

				if ($data['pre_order_image'] != "" && JFile::exists($dest)
				)
				{
					JFile::delete($dest);
				}

				JFile::upload($preOrderImage['tmp_name'], $dest);
			}
		}

		// Image next link
		$imageNext = $jFile->get('imgnext');

		if ($imageNext['name'] != "")
		{
			$fileType = JFile::getExt($imageNext['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["image_next_link"] = $imageNext['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data["image_next_link"];

				if ($data['image_next_link'] != "" && JFile::exists($dest)
				)
				{
					JFile::delete($dest);
				}

				JFile::upload($imageNext['tmp_name'], $dest);
			}
		}

		// Image previous link
		$imagePrev = $jFile->get('imgpre');

		if ($imagePrev['name'] != "")
		{
			$fileType = JFile::getExt($imagePrev['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["image_previous_link"] = $imagePrev['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . '/' . $data["image_previous_link"];

				if ($data['image_previous_link'] != "" && JFile::exists($dest)
				)
				{
					JFile::delete($dest);
				}

				JFile::upload($imagePrev['tmp_name'], $dest);
			}
		}

		// Product Detail Lightbox close button Image Start
		$imageSlimBox = $jFile->get('imgslimbox');

		if ($imageSlimBox['name'] != "")
		{
			$fileType = JFile::getExt($imagePrev['name']);

			if (in_array($fileType, $allowedDefaultExt))
			{
				$data["product_detail_lighbox_close_button_image"] = $imageSlimBox['name'];

				$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . $data["product_detail_lighbox_close_button_image"];

				if ($data['product_detail_lighbox_close_button_image'] != "" && JFile::exists($dest))
				{
					JFile::delete($dest);
				}

				JFile::upload($imageSlimBox['tmp_name'], $dest);
			}
		}

	}

	/**
	 * @param   string  $d  D
	 *
	 * @return  boolean
	 *
	 */
	public function configurationPrepare($d)
	{
		$this->configData = $this->redConfiguration->prepareConfigData($d);

		return (boolean) $this->configData;
	}

	/**
	 * Method to get the configuration data.
	 *
	 * This method will load the global configuration data straight from
	 * RedshopConfig. If configuration data has been saved in the session, that
	 * data will be merged into the original data, overwriting it.
	 *
	 * @return    object  An object containing all redshop config data.
	 *
	 * @since    1.6
	 */
	public function getData()
	{
		// Get the config data.
		$data = Redshop::getConfig()->toArray();

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

	/**
	 * @param   array  $data  Data
	 *
	 * @return  boolean
	 *
	 */
	public function newsletterEntry($data)
	{
		$db            = JFactory::getDbo();
		$query         = $db->getQuery(true);

		$newsletterId = $data['default_newsletter'];
		$mailfrom      = $data['news_mail_from'];
		$mailfromname  = $data['news_from_name'];
		$to            = $data['newsletter_test_email'];
		$uri           = JURI::getInstance();
		$url           = $uri->root();

		// Getting newsletter content
		$newsbody = $this->getnewsletter_content($newsletterId);

		$subject            = "";
		$newsletterBody     = "";
		$newsletterTemplate = "";

		if (count($newsbody) > 0)
		{
			$subject            = $newsbody[0]->subject;
			$newsletterBody     = $newsbody[0]->body;
			$newsletterTemplate = $newsbody[0]->template_desc;
		}

		$o       = new stdClass;
		$o->text = $newsletterBody;
		JPluginHelper::importPlugin('content');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$x          = array();
		$dispatcher->trigger('onPrepareContent', array(&$o, &$x, 0));
		$newsletterTemplate2 = $o->text;

		$content = str_replace("{data}", $newsletterTemplate2, $newsletterTemplate);

		$products = $this->getProductIdList();

		if ($products)
		{
			foreach ($products as $product)
			{
				$productId = $product->product_id;

				if (strstr($content, '{redshop:' . $productId . '}'))
				{
					$content = str_replace('{redshop:' . $productId . '}', "", $content);
				}

				if (strstr($content, '{Newsletter Products:' . $productId . '}'))
				{
					$productId       = $product->product_id;
					$newsproductbody = $this->getnewsletterproducts_content();
					$npTemplateDesc  = $newsproductbody[0]->template_desc;

					$thumbImage = "";

					if ($product->product_full_image)
					{
						$thumbUrl   = RedshopHelperMedia::getImagePath(
							$product->product_full_image,
							'',
							'thumb',
							'product',
							Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
							Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
						$thumbImage = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/"
							. $product->product_full_image . "' title='' rel=\"lightbox[product7]\">";
						$thumbImage .= "<img id='main_image' src='" . $thumbUrl . "'>";
						$thumbImage .= "</a>";
					}

					$npTemplateDesc = str_replace("{product_thumb_image}", $thumbImage, $npTemplateDesc);
					$npTemplateDesc = str_replace(
						"{product_price}",
						RedshopHelperProductPrice::formattedPrice($product->product_price), $npTemplateDesc
					);
					$npTemplateDesc = str_replace("{product_name}", $product->product_name, $npTemplateDesc);
					$npTemplateDesc = str_replace("{product_desc}", $product->product_desc, $npTemplateDesc);
					$npTemplateDesc = str_replace("{product_s_desc}", $product->product_s_desc, $npTemplateDesc);

					$content = str_replace("{Newsletter Products:" . $productId . "}", $npTemplateDesc, $content);
				}
			}
		}

		// Replacing the Text library texts
		$content = RedshopHelperText::replaceTexts($content);

		$data1       = RedshopHelperMail::imgInMail($content);

		$to    = trim($to);
		$today = time();

		// Replacing the tags with the values
		$name = explode('@', $to);

		// Insert columns.
		$columns = array('tracker_id', 'newsletter_id', 'subscription_id', 'subscriber_name', 'user_id', 'read', 'date');
		$values = array('', (int) $newsletterId, '0', $db->quote($name), 0, 0, $db->quote($today));

		$query->insert($db->quoteName('#__redshop_newsletter_tracker'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));

		$db->setQuery($query)->execute();

		$content = '<img  src="' . $url . 'index.php?option=com_redshop&view=newsletter&task=tracker&tmpl=component&tracker_id=' . $db->insertid() . '" />';
		$content .= str_replace("{username}", $name[0], $data1);
		$content = str_replace("{email}", $to, $content);

		// Replace tag {unsubscribe_link} for testing mail to empty link, because test mail not have subscribes
		$content = str_replace("{unsubscribe_link}", "<a href=\"#\">" . JText::_('COM_REDSHOP_UNSUBSCRIBE') . "</a>", $content);

		return JFactory::getMailer()->sendMail($mailfrom, $mailfromname, $to, $subject, $content, 1);

	}

	/**
	 *
	 * @return  array<object>
	 */
	public function getOrderstatus()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array(
			$db->quoteName('order_status_code', 'value'),
			$db->quoteName('order_status_name', 'text')
		));
		$query->from($db->quoteName('#__redshop_order_status'));
		$query->where($db->quoteName('published') . ' = 1');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Get current redSHOP version
	 *
	 * @return  string
	 */
	public function getCurrentVersion()
	{
		$xmlfile = JPATH_ROOT . '/administrator/components/com_redshop/redshop.xml';
		$version = JText::_('COM_REDSHOP_FILE_NOT_FOUND');

		if (JFile::exists($xmlfile))
		{
			$data    = JInstaller::parseXMLInstallFile($xmlfile);
			$version = $data['version'];
		}

		return $version;
	}

	/**
	 * Get all installed module for redshop
	 *
	 * @return  array<object>
	 *
	 */
	public function getinstalledmodule()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('element') . ' LIKE ' . $db->quote('%mod_redshop%'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Get all installed payment plugins for redshop
	 *
	 * @param   string  $secion  Section
	 *
	 * @return  array<object>
	 *
	 */
	public function getinstalledplugins($secion = 'redshop_payment')
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('*')
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('folder') . ' = ' . $db->quote($secion));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Reset template
	 *
	 */
	public function resetTemplate()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from($db->quoteName('#__redshop_template'));

		$list = $db->setQuery($query)->loadObjectList();

		for ($i = 0, $in = count($list); $i < $in; $i++)
		{
			$data = $list[$i];

			$data->template_name = strtolower($data->template_name);
			$data->template_name = str_replace(" ", "_", $data->template_name);
			$templateFile        = RedshopHelperTemplate::getTemplateFilePath($data->template_section, $data->template_name, true);

			if (JFile::exists($templateFile))
			{
				$template_desc = RedshopHelperTemplate::getInstallSectionTemplate($data->template_name);
				$fp            = fopen($templateFile, "w");
				fwrite($fp, $template_desc);
				fclose($fp);
			}
		}
	}
}
