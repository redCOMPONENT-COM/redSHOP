<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

use Joomla\Registry\Registry;

/**
 * Class RedshopModelConfiguration
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0
 */
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
	 * Method for save configuration
	 *
	 * @param   array  $data  Array of data.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0
	 *
	 * @throws  Exception
	 */
	public function store($data)
	{
		$input = JFactory::getApplication()->input;

		$this->fileUpload($data);

		// Product Detail Lightbox close button Image End
		// Save the HTML tags into the tables
		$data["welcomepage_introtext"]         = $input->get('welcomepage_introtext', '', 'RAW');
		$data["category_frontpage_introtext"]  = $input->get('category_frontpage_introtext', '', 'RAW');
		$data["registration_introtext"]        = $input->get('registration_introtext', '', 'RAW');
		$data["registration_comp_introtext"]   = $input->get('registration_comp_introtext', '', 'RAW');
		$data["vat_introtext"]                 = $input->get('vat_introtext', '', 'RAW');
		$data["welcomepage_introtext"]         = $input->get('welcomepage_introtext', '', 'RAW');
		$data["product_expire_text"]           = $input->get('product_expire_text', '', 'RAW');
		$data["cart_reservation_message"]      = $input->get('cart_reservation_message', '', 'RAW');
		$data["with_vat_text_info"]            = $input->get('with_vat_text_info', '', 'RAW');
		$data["without_vat_text_info"]         = $input->get('without_vat_text_info', '', 'RAW');
		$data["show_price_user_group_list"]    = implode(",", $data['show_price_user_group_list']);
		$data["show_price_shopper_group_list"] = is_array($data['show_price_shopper_group_list']) ?
			implode(",", $data['show_price_shopper_group_list']) : $data['show_price_shopper_group_list'];
		$data["show_price_user_group_list"]    = $data["show_price_user_group_list"] ? $data["show_price_user_group_list"] : '';
		$data["show_price_shopper_group_list"] = $data["show_price_shopper_group_list"] ? $data["show_price_shopper_group_list"] : '';

		if ($data['image_quality_output'] <= 10)
		{
			$data['image_quality_output'] = 90;
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
	 * @return  void
	 *
	 * @since   2.0.7
	 *
	 * @throws  Exception
	 */
	private function fileUpload(&$data)
	{
		$files = JFactory::getApplication()->input->files;

		// Product Default Image upload
		$productImg = $files->get('productImg');
		$this->fileUploadProgress($data["product_default_image"], $productImg['name'], 'productdefault', $productImg['tmp_name'], 'product/');

		// 	Watermark Image upload
		$watermarkImg = $files->get('watermarkImg');
		$this->fileUploadProgress($data["watermark_image"], $watermarkImg['name'], 'watermark', $watermarkImg['tmp_name'], 'product/');

		// Shopper Group default portal upload
		$defaultPortalLogo = $files->get('default_portal_logo');
		$this->fileUploadProgress($data["default_portal_logo"], $defaultPortalLogo['name'], null, $defaultPortalLogo['tmp_name'], 'shopperlogo/');
		$data["default_portal_logo"] = empty($data["default_portal_logo"]) ? $data['default_portal_logo_tmp'] : $data['default_portal_logo'];

		// Product image which is out of stock
		$productOutOfStockImg = $files->get('productoutofstockImg');
		$this->fileUploadProgress(
			$data["product_outofstock_image"], $productOutOfStockImg['name'], null, $productOutOfStockImg['tmp_name'], 'product/'
		);

		// Category Default Image upload
		$categoryImg = $files->get('categoryImg');
		$this->fileUploadProgress($data["category_default_image"], $categoryImg['name'], null, $categoryImg['tmp_name'], 'category/');

		// Cart image upload
		$cartImage = $files->get('cartimg');
		$this->fileUploadProgress($data["addtocart_image"], $cartImage['name'], null, $cartImage['tmp_name']);

		// Quote image
		$quoteImage = $files->get('quoteimg');
		$this->fileUploadProgress($data["requestquote_image"], $quoteImage['name'], null, $quoteImage['tmp_name']);

		// Cart delete image upload
		$cartDelete = $files->get('cartdelete');
		$this->fileUploadProgress($data["addtocart_delete"], $cartDelete['name'], null, $cartDelete['tmp_name']);

		// Cart update image upload
		$cartUpdate = $files->get('cartupdate');
		$this->fileUploadProgress($data["addtocart_update"], $cartUpdate['name'], null, $cartUpdate['tmp_name']);

		// Pre Order image upload
		$preOrderImage = $files->get('file_pre_order_image');
		$this->fileUploadProgress($data["pre_order_image"], $preOrderImage['name'], null, $preOrderImage['tmp_name']);

		// Image next link
		$imageNext = $files->get('imgnext');
		$this->fileUploadProgress($data["image_next_link"], $imageNext['name'], null, $imageNext['tmp_name']);

		// Image previous link
		$imagePrev = $files->get('imgpre');
		$this->fileUploadProgress($data["image_previous_link"], $imagePrev['name'], null, $imagePrev['tmp_name']);

		// Product Detail Lightbox close button Image Start
		$imageSlimBox = $files->get('imgslimbox');
		$this->fileUploadProgress(
			$data["product_detail_lighbox_close_button_image"], $imageSlimBox['name'], null, $imageSlimBox['tmp_name'], 'slimbox/'
		);
	}

	/**
	 * Method for process upload file
	 *
	 * @param   string  $return        Return data reference
	 * @param   string  $source        Source data
	 * @param   string  $sourceClean   Clean string.
	 * @param   string  $tmpFile       Temporary upload file path.
	 * @param   string  $folderPrefix  Is category path.
	 *
	 * @return  void
	 *
	 * @since   2.0.7
	 */
	private function fileUploadProgress(&$return, $source = '', $sourceClean = null, $tmpFile = '', $folderPrefix = '')
	{
		$allowedDefaultExt = array ('jpg', 'jpeg', 'gif', 'png');

		if (empty($source) || !in_array(JFile::getExt($source), $allowedDefaultExt))
		{
			return;
		}

		$return = RedshopHelperMedia::cleanFileName($source, $sourceClean);
		$dest   = REDSHOP_FRONT_IMAGES_RELPATH . $folderPrefix . $return;

		// Delete old file
		if (JFile::exists($dest))
		{
			JFile::delete($dest);
		}

		JFile::upload($tmpFile, $dest);
	}

	/**
	 * @param   array  $data  Data for prepare
	 *
	 * @return  boolean
	 *
	 */
	public function configurationPrepare($data)
	{
		$this->configData = $this->redConfiguration->prepareConfigData($data);

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
	 *
	 *
	 * @throws   Exception
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

	/**
	 * Get Shop Currency Support
	 *
	 * @param   string  $currency  Separated countries
	 *
	 * @return  array              Array for Shop country
	 *
	 * @since   2.0.0
	 */
	public function getCurrencies($currency = '')
	{
		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('code', 'value'))
			->select($db->qn('name', 'text'))
			->from($db->qn('#__redshop_currency'))
			->order($db->qn('name'));

		if ($currency)
		{
			$query->where($db->qn('code') . ' IN (' . $currency . ')');
		}

		return $db->setQuery($query)->loadObjectList();
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
		$query = 'SELECT n.template_id,n.body,n.subject FROM #__redshop_newsletter AS n '
			. 'LEFT JOIN #__redshop_template AS nt ON n.template_id=nt.id '
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
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$newsletterId = $data['default_newsletter'];
		$mailfrom     = $data['news_mail_from'];
		$mailfromname = $data['news_from_name'];
		$to           = $data['newsletter_test_email'];
		$uri          = JURI::getInstance();
		$url          = $uri->root();

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
						$thumbUrl    = RedshopHelperMedia::getImagePath(
							$product->product_full_image,
							'',
							'thumb',
							'product',
							Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
							Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
						$thumbImage  = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/"
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
		$data1   = $content;
		Redshop\Mail\Helper::imgInMail($data1);

		$to    = trim($to);
		$today = time();

		// Replacing the tags with the values
		$name = explode('@', $to);

		// Insert columns.
		$columns = array('newsletter_id', 'subscription_id', 'subscriber_name', 'user_id', 'read', 'date');
		$values = array((int) $newsletterId, '0', $db->quote($name[0]), 0, 0, $db->quote($today));

		$query->insert($db->qn('#__redshop_newsletter_tracker'))
			->columns($db->qn($columns))
			->values(implode(',', $values));

		$db->setQuery($query)->execute();

		$content  = '<img  src="' . $url . 'index.php?option=com_redshop&view=newsletter&task=tracker&tmpl=component&tracker_id=' . $db->insertid() . '" />';
		$content .= str_replace("{username}", $name[0], $data1);
		$content  = str_replace("{email}", $to, $content);

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
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array(
			$db->qn('order_status_code', 'value'),
			$db->qn('order_status_name', 'text')
			)
		);
		$query->from($db->qn('#__redshop_order_status'));
		$query->where($db->qn('published') . ' = 1');

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
			->from($db->qn('#__extensions'))
			->where($db->qn('element') . ' LIKE ' . $db->quote('%mod_redshop%'));

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
			->from($db->qn('#__extensions'))
			->where($db->qn('folder') . ' = ' . $db->quote($secion));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Reset template
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function resetTemplate()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_template'));

		$list = $db->setQuery($query)->loadObjectList();

		if (empty($list))
		{
			return;
		}

		foreach ($list as $template)
		{
			$table = RedshopTable::getAdminInstance('Template', array('ignore_request' => true), 'com_redshop');

			$table->bind((array) $template);
			$table->templateDesc = RedshopHelperTemplate::getDefaultTemplateContent($table->section);
			$table->store();
		}
	}
}
