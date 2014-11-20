<?php
/**
 * @package    RedSHOP.Installer
 *
 * @copyright  Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Script file of redSHOP component
 *
 * @package  RedSHOP.Installer
 *
 * @since    1.2
 */
class Com_RedshopInstallerScript
{
	/**
	 * Status of the installation
	 *
	 * @var  [type]
	 */
	public $status = null;

	public $installer = null;

	/**
	 * Method to install the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function install($parent)
	{
		// Install extensions
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);

		// $parent is the class calling this method

		JLoader::import('redshop.library');
		JLoader::load('RedshopHelperAdminTemplate');

		$this->com_install('install');

		$this->handleCSSFile();
	}

	/**
	 * method to uninstall the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
		// Uninstall extensions
		$this->uninstallLibraries($parent);
		$this->uninstallModules($parent);
		$this->uninstallPlugins($parent);
	}

	/**
	 * method to update the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function update($parent)
	{
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);

		JLoader::import('redshop.library');
		JLoader::load('RedshopHelperAdminTemplate');
		$this->com_install('update');

		$this->handleCSSFile();
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   object  $type    type of change (install, update or discover_install)
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function preflight($type, $parent)
	{
		if ($type == "update")
		{
			$this->updateschema();
		}
	}

	/**
	 * method to update schema table
	 *
	 * @return void
	 */
	public function updateschema()
	{
		$db = JFactory::getDbo();
		$db->setQuery("SELECT extension_id FROM #__extensions WHERE element ='com_redshop' AND type = 'component'");
		$component_Id = $db->loadResult();

		if ($component_Id != "" && $component_Id != "0")
		{
			$db->setQuery("SELECT * FROM #__schemas WHERE extension_id ='" . $component_Id . "'");
			$total_result = $db->loadResult();

			if (count($total_result) == 0)
			{
				$insert_schema = "insert into #__schemas set extension_id='" . $component_Id . "',version_id='1.1.10'";
				$db->setQuery($insert_schema);
				$db->query();
			}
		}
	}

	/**
	 * Method to run after an install/update/uninstall method
	 *
	 * @param   object  $type    type of change (install, update or discover_install)
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function postflight($type, $parent)
	{
		// Install Module and Plugin
		$installer  = $parent->getParent();
		$source     = $installer->getPath('source');
		$pluginPath = $source . '/plugins';

		if ($type == 'update')
		{
			$lang = JFactory::getLanguage();
			$lang->load('com_redshop', JPATH_ADMINISTRATOR);
			JModel::addIncludePath(JPATH_SITE . '/administrator/components/com_redshop/models');
			$model = JModelLegacy::getInstance('Update', 'RedshopModel');
			$model->checkUpdateStatus();
		}
	}

	/**
	 * Handle redSHOP Demo CSS file for Demo Content
	 *
	 * @return  void
	 */
	private function handleCSSFile()
	{
		$categoryTemplate = JPATH_SITE . '/components/com_redshop/views/category/tmpl/category/category_template_column.php';

		if (file_exists($categoryTemplate))
		{
			$demoCSS    = JPATH_SITE . '/components/com_redshop/assets/css/redshop-update.css';
			$redSHOPCSS = JPATH_SITE . '/components/com_redshop/assets/css/redshop.css';
			unlink($redSHOPCSS);
			rename($demoCSS, $redSHOPCSS);
		}
	}

	/**
	 * Main redSHOP installer Events
	 *
	 * @param   string  $type  type of change (install, update or discover_install)
	 *
	 * @return  void
	 */
	private function com_install($type)
	{
		$db = JFactory::getDbo();

		// The redshop.cfg.php creation or update
		$this->redshopHandleCFGFile();

		// Syncronise users
		$this->userSynchronization();

		// Demo content insert

		// Start template demo content
		$redtemplate = new Redtemplate;
		$q           = "INSERT IGNORE INTO `#__redshop_template` (`template_id`, `template_name`, `template_section`, `template_desc`, `published`) VALUES
					(8, 'grid', 'category', '" . $redtemplate->getInstallSectionTemplate('grid') . "', 1),
					(5, 'list', 'category', '" . $redtemplate->getInstallSectionTemplate('list') . "', 1),
					(26, 'product2', 'product', '" . $redtemplate->getInstallSectionTemplate('product2') . "', 1),
					(9, 'product', 'product', '" . $redtemplate->getInstallSectionTemplate('product') . "', 1),
					(29, 'newsletter1', 'newsletter', '" . $redtemplate->getInstallSectionTemplate('newsletter1') . "', 1),
					(10, 'cart', 'cart', '" . $redtemplate->getInstallSectionTemplate('cart') . "', 1),
					(11, 'review', 'review', '" . $redtemplate->getInstallSectionTemplate('review') . "', 1),
					(13, 'manufacturer_listings', 'manufacturer','" . $redtemplate->getInstallSectionTemplate('manufacturer_listings') . "', 1),
					(14, 'manufacturer_products', 'manufacturer_products','" . $redtemplate->getInstallSectionTemplate('manufacturer_products') . "', 1),
					(15, 'order_list', 'order_list', '" . $redtemplate->getInstallSectionTemplate('order_list') . "', 1),
					(16, 'order_detail', 'order_detail', '" . $redtemplate->getInstallSectionTemplate('order_detail') . "', 1),
					(23, 'related_products', 'related_product', '" . $redtemplate->getInstallSectionTemplate('related_products') . "', 1),
					(17, 'order_receipt', 'order_receipt', '" . $redtemplate->getInstallSectionTemplate('order_receipt') . "', 1),
					(18, 'manufacturer_detail', 'manufacturer_detail', '" . $redtemplate->getInstallSectionTemplate('manufacturer_detail') . "', 1),
					(22, 'frontpage_category', 'frontpage_category', '" . $redtemplate->getInstallSectionTemplate('frontpage_category') . "', 1),
					(24, 'add_to_cart1', 'add_to_cart', '" . $redtemplate->getInstallSectionTemplate('add_to_cart1') . "', 1),
					(25, 'add_to_cart2', 'add_to_cart', '" . $redtemplate->getInstallSectionTemplate('add_to_cart2') . "', 1),
					(27, 'accessory', 'accessory_template', '" . $redtemplate->getInstallSectionTemplate('accessory') . "', 1),
					(28, 'attributes', 'attribute_template', '" . $redtemplate->getInstallSectionTemplate('attributes') . "', 1),
					(100,'my_account_template','account_template','" . $redtemplate->getInstallSectionTemplate('my_account_template') . "',1),
					(101, 'catalog', 'catalog', '" . $redtemplate->getInstallSectionTemplate('catalog') . "', 1),
					(102, 'catalog_sample', 'product_sample', '" . $redtemplate->getInstallSectionTemplate('catalog_sample') . "', 1),
					(103, 'wishlist_list','wishlist_template','" . $redtemplate->getInstallSectionTemplate('wishlist_list') . "',1),
					(105,'wishlist_mail','wishlist_mail_template','" . $redtemplate->getInstallSectionTemplate('wishlist_mail') . "',1),
					(115,'wrapper','wrapper_template','" . $redtemplate->getInstallSectionTemplate('wrapper') . "',1),
					(125,'giftcard_listing','giftcard_list','" . $redtemplate->getInstallSectionTemplate('giftcard_listing') . "',1),
					(135,'giftcard','giftcard','" . $redtemplate->getInstallSectionTemplate('giftcard') . "',1),
					(110, 'ask_question', 'ask_question_template', '" . $redtemplate->getInstallSectionTemplate('ask_question') . "', 1),
					(111, 'ajax_cart_box', 'ajax_cart_box', '" . $redtemplate->getInstallSectionTemplate('ajax_cart_box') . "', 1),
					(112, 'ajax_cart_detail_box', 'ajax_cart_detail_box', '" . $redtemplate->getInstallSectionTemplate('ajax_cart_detail_box') . "', 1),
					(200, 'shipping_pdf', 'shipping_pdf', '" . $redtemplate->getInstallSectionTemplate('shipping_pdf') . "', 1),
					(251, 'order_print', 'order_print', '" . $redtemplate->getInstallSectionTemplate('order_print') . "', 1),
					(252, 'clicktell_sms_message', 'clicktell_sms_message', '" . $redtemplate->getInstallSectionTemplate('clicktell_sms_message') . "', 1),
					(260, 'redproductfinder', 'redproductfinder', '" . $redtemplate->getInstallSectionTemplate('redproductfinder') . "', 1),
					(265, 'quotation_detail', 'quotation_detail', '" . $redtemplate->getInstallSectionTemplate('quotation_detail') . "', 1),
					(334, 'newsletter_products', 'newsletter_product', '" . $redtemplate->getInstallSectionTemplate('newsletter_products') . "', 1),
				    (280, 'catalogue_cart', 'catalogue_cart', '" . $redtemplate->getInstallSectionTemplate('catalogue_cart') . "', 1),
					(281, 'catalogue_order_detail', 'catalogue_order_detail', '" . $redtemplate->getInstallSectionTemplate('catalogue_order_detail') . "', 1),
					(282, 'catalogue_order_receipt', 'catalogue_order_receipt', '" . $redtemplate->getInstallSectionTemplate('catalogue_order_receipt') . "', 1),
					(289, 'empty_cart', 'empty_cart', '" . $redtemplate->getInstallSectionTemplate('empty_cart') . "', 1),
					(320, 'compare_product', 'compare_product', '" . $redtemplate->getInstallSectionTemplate('compare_product') . "', 1),
					(353, 'payment_method', 'redshop_payment', '" . $redtemplate->getInstallSectionTemplate('payment_method') . "', 1),
					(354, 'shipping_method', 'redshop_shipping', '" . $redtemplate->getInstallSectionTemplate('shipping_method') . "', 1),
					(355, 'shipping_box', 'shippingbox', '" . $redtemplate->getInstallSectionTemplate('shippingbox') . "',1),
					(356, 'category_product_template', 'categoryproduct', '" . $redtemplate->getInstallSectionTemplate('category_product_template') . "', 1),
					(357, 'change_cart_attribute_template', 'change_cart_attribute', '" . $redtemplate->getInstallSectionTemplate('change_cart_attribute_template') . "', 1),
					(358, 'onestep_checkout', 'onestep_checkout', '" . $redtemplate->getInstallSectionTemplate('onestep_checkout') . "', 1),
					(359, 'attributes_listing1', 'attributewithcart_template', '" . $redtemplate->getInstallSectionTemplate('attributes_listing1') . "', 1),
					(360, 'checkout', 'checkout', '" . $redtemplate->getInstallSectionTemplate('checkout') . "',1),
					(371, 'product_content', 'product_content_template', '" . $redtemplate->getInstallSectionTemplate('product_content') . "',1),
				    (372, 'quotation_cart_template', 'quotation_cart', '" . $redtemplate->getInstallSectionTemplate('quotation_cart') . "',1),
					(370, 'quotation_request_template', 'quotation_request', '" . $redtemplate->getInstallSectionTemplate('quotation_request_template') . "',1),
					(450, 'billing_template', 'billing_template', '" . $redtemplate->getInstallSectionTemplate('billing_template') . "',1),
					(451, 'shipping_template', 'shipping_template', '" . $redtemplate->getInstallSectionTemplate('shipping_template') . "',1),
					(452, 'shippment_invoice_template', 'shippment_invoice_template', '" . $redtemplate->getInstallSectionTemplate('shippment_invoice_template') . "',1),
					(460, 'private_billing_template', 'private_billing_template', '" . $redtemplate->getInstallSectionTemplate('private_billing_template') . "',1),
					(461, 'company_billing_template', 'company_billing_template', '" . $redtemplate->getInstallSectionTemplate('company_billing_template') . "',1),
	                (550, 'stock_note', 'stock_note', '" . $redtemplate->getInstallSectionTemplate('stock_note') . "',1)";
		$db->setQuery($q);
		$db->query();

		// TEMPLATE MOVE DB TO  FILE

		$db = JFactory::getDbo();
		$q  = "SELECT * FROM #__redshop_template";
		$db->setQuery($q);
		$list = $db->loadObjectList();

		for ($i = 0; $i < count($list); $i++)
		{
			$data = & $list[$i];

			$red_template        = new Redtemplate;
			$tname               = $data->template_name;
			$data->template_name = strtolower($data->template_name);
			$data->template_name = str_replace(" ", "_", $data->template_name);
			$tempate_file        = $red_template->getTemplatefilepath($data->template_section, $data->template_name, true);

			if (!is_file($tempate_file))
			{
				$fp = fopen($tempate_file, "w");
				fwrite($fp, $data->template_desc);
				fclose($fp);
			}

			if (is_file($tempate_file))
			{
				$template_desc = file_get_contents($tempate_file);

				if (!strstr($template_desc, '{product_subtotal}') && !strstr($template_desc, '{product_subtotal_excl_vat}'))
				{
					if (strstr($template_desc, '{subtotal}') || strstr($template_desc, '{order_subtotal}'))
					{
						$template_desc = str_replace("{subtotal}", "{product_subtotal}", $template_desc);
						$template_desc = str_replace("{order_subtotal}", "{product_subtotal}", $template_desc);
					}

					if (strstr($template_desc, '{subtotal_excl_vat}') || strstr($template_desc, '{order_subtotal_excl_vat}'))
					{
						$template_desc = str_replace("{subtotal_excl_vat}", "{product_subtotal_excl_vat}", $template_desc);
						$template_desc = str_replace("{order_subtotal_excl_vat}", "{product_subtotal_excl_vat}", $template_desc);
					}
				}

				if (!strstr($template_desc, '{shipping_excl_vat}'))
				{
					if (strstr($template_desc, '{shipping}'))
					{
						$template_desc = str_replace('{shipping}', '{shipping_excl_vat}', $template_desc);
					}

					if (strstr($template_desc, '{shipping_with_vat}'))
					{
						$template_desc = str_replace('{shipping_with_vat}', '{shipping}', $template_desc);
					}
				}

				$fp = fopen($tempate_file, "w");
				fwrite($fp, $template_desc);
				fclose($fp);

			}

			if ($data->template_id)
			{
				if ($data->template_name != $tname)
				{
					$uquery = "UPDATE `#__redshop_template` SET template_name ='" . $data->template_name . "' "
						. "WHERE template_id='" . $data->template_id . "'";
					$db->setQuery($uquery);
					$db->query();
				}
			}
		}

		// TEMPLATE MOVE DB TO  FILE END

		// For Blank component id in menu table-admin menu error solution

		$q_ext = "select * from `#__extensions` where name = 'redshop' and element = 'com_redshop' and type='component'";
		$db->setQuery($q_ext);
		$list_ext = $db->loadObjectList();
		$data     = & $list_ext[0];

		if (count($data) > 0)
		{
			$extension_id = $data->extension_id;

			if ($extension_id == "")
			{
				$extension_id = "1";
			}

			$uquery_ext = "UPDATE `#__menu` SET component_id ='.$extension_id.' "
				. " WHERE  menutype = 'main' and path = 'redshop' and type='component'";
			$db->setQuery($uquery_ext);
			$db->query();
		}

		?>
		<center>
			<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
				<tr>
					<td valign="top">
						<img src="<?php echo 'components/com_redshop/assets/images/261-x-88.png'; ?>" alt="redSHOP Logo"
						     align="left">
					</td>
					<td valign="top" width="100%">
						<strong>redSHOP</strong><br/>
						<font class="small">by <a href="http://www.redcomponent.com"
						                          target="_blank">redcomponent.com </a><br/></font>
						<font class="small">
							Released under the terms and conditions of the <a
								href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GNU General Public
								License</a>.
						</font>

						<p>Remember to check for updates on:
							<a href="http://redcomponent.com/" target="_new"><img
									src="http://images.redcomponent.com/redcomponent.jpg" alt=""></a>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<form action="index.php" method="post" name="installDemoContent">
							<?php if ($type != 'update'): ?>
							<input type="button" name="save" id="installDemoContentsave" value="Configuration Wizard"
							       onclick="submitWizard('save');"/>
							<input type="button" name="content" value="install Demo Content"
							       onclick="submitWizard('content');"/>
							<input type="button" name="cancel" value="Cancel" onclick="submitWizard('cancel');"/>
							<?php else: ?>
							<input type="button" name="update" value="Optimize RedSHOP tables"
								   onclick="submitWizard('update');"/>
							<?php endif; ?>
							<input type="hidden" name="option" value="com_redshop">
							<input type="hidden" name="task" value="">
							<input type="hidden" name="wizard" value="1">
						</form>
						<script type="text/javascript">

							var ind = new Number(1);

							//window.onload = gotoconfigwizard();

							function gotoconfigwizard() {
								if (ind == 5) {
									submitWizard('save');
								} else {
									setTimeout("gotoconfigwizard()", 1000);
								}

								document.getElementById('installDemoContentsave').value = "Configuration Wizard " + ind++;

							}

							function submitWizard(task) {
								if (task == 'save') {
									document.installDemoContent.wizard.value = 1;
								}

								if (task == 'content') {
									document.installDemoContent.wizard.value = 0;
									document.installDemoContent.task.value = 'demoContentInsert';
								}

								if (task == 'update') {
									document.installDemoContent.wizard.value = 0;
									document.installDemoContent.task.value = 'update.refresh';
								}

								if (task == 'cancel') {
									document.installDemoContent.wizard.value = 0;
								}

								document.installDemoContent.submit();
							}
						</script>
					</td>
				</tr>
			</table>
		</center>
		<?php
		// Install the sh404SEF router files
		JLoader::import('joomla.filesystem.file');
		JLoader::import('joomla.filesystem.folder');
		$sh404sefext   = JPATH_SITE . '/components/com_sh404sef/sef_ext';
		$sh404sefmeta  = JPATH_SITE . '/components/com_sh404sef/meta_ext';
		$sh404sefadmin = JPATH_SITE . '/administrator/components/com_sh404sef';
		$redadmin      = JPATH_SITE . '/administrator/components/com_redshop/extras';

		// Check if sh404SEF is installed
		if (JFolder::exists(JPATH_SITE . '/components/com_sh404sef'))
		{
			// Copy the plugin
			if (!JFile::copy($redadmin . '/sh404sef/sef_ext/com_redshop.php', $sh404sefext . '/com_redshop.php'))
			{
				echo JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_EXTENSION_PLUGIN_FILE');
			}

			if (!JFile::copy($redadmin . '/sh404sef/meta_ext/com_redshop.php', $sh404sefmeta . '/com_redshop.php'))
			{
				echo JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_META_PLUGIN_FILE');
			}

			if (!JFile::copy($redadmin . '/sh404sef/language/com_redshop.php', $sh404sefadmin . '/language/plugins/com_redshop.php'))
			{
				echo JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_PLUGIN_LANGUAGE_FILE');
			}
		}
	}

	/**
	 * User synchronization
	 *
	 * @return  void
	 */
	private function userSynchronization()
	{
		require_once JPATH_SITE . "/administrator/components/com_redshop/helpers/redshop.cfg.php";
		JLoader::import('redshop.library');
		JLoader::load('RedshopHelperUser');

		JTable::addIncludePath(JPATH_SITE . '/administrator/components/com_redshop/tables');

		$userhelper = new rsUserhelper;
		$cnt        = $userhelper->userSynchronization();
	}

	/**
	 * Update/create configuration file
	 *
	 * @return  void
	 */
	private function redshopHandleCFGFile()
	{
		JLoader::import('redshop.library');
		JLoader::load('RedshopHelperAdminConfiguration');

		// Include redshop.cfg.php file for cfg variables
		$cfgfile = JPATH_SITE . "/administrator/components/com_redshop/helpers/redshop.cfg.php";

		if (file_exists($cfgfile))
		{
			$configData = JFile::read($cfgfile);
			$configData = str_replace('<?php', '', $configData);
			$configData = str_replace('?>', '', $configData);
			$configData = "<?php" . $configData;

			JFile::write($cfgfile, $configData);

			require_once $cfgfile;
		}

		$Redconfiguration = new Redconfiguration;

		// Declaration
		$cfgarr = array();

		/*
		 * Check before update $cfgarr
		 * for variable is defined or not?
		 *
		 * Example:
		 * if (!defined("TESTING"))
		 * {
		 * 		$cfgarr["TESTING"] = 3.14;
		 * }
		 */
		if (!defined("UPDATE_MAIL_ENABLE"))
		{
			$cfgarr["UPDATE_MAIL_ENABLE"] = 1;
		}

		if (!defined("DISCOUNT_TYPE"))
		{
			$cfgarr["DISCOUNT_TYPE"] = 3;
		}

		if (!defined("ENABLE_BACKENDACCESS"))
		{
			$cfgarr["ENABLE_BACKENDACCESS"] = 0;
		}

		if (!defined("WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART"))
		{
			$cfgarr["WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART"] = 0;
		}

		if (!defined("ADDTOCART_BEHAVIOUR"))
		{
			$cfgarr["ADDTOCART_BEHAVIOUR"] = 1;
		}

		if (!defined("SHOPPER_GROUP_DEFAULT_UNREGISTERED") && defined("SHOPPER_GROUP_DEFAULT_PRIVATE"))
		{
			$cfgarr["SHOPPER_GROUP_DEFAULT_UNREGISTERED"] = SHOPPER_GROUP_DEFAULT_PRIVATE;
		}

		if (!defined("INDIVIDUAL_ADD_TO_CART_ENABLE"))
		{
			$cfgarr["INDIVIDUAL_ADD_TO_CART_ENABLE"] = 0;
		}

		if (!defined("PRODUCT_ADDIMG_IS_LIGHTBOX"))
		{
			$cfgarr["PRODUCT_ADDIMG_IS_LIGHTBOX"] = 1;
		}

		if (!defined("POSTDK_CUSTOMER_NO"))
		{
			$cfgarr["POSTDK_CUSTOMER_NO"] = 1;
		}

		if (!defined("POSTDK_INTEGRATION"))
		{
			$cfgarr["POSTDK_INTEGRATION"] = 0;
		}

		if (!defined("POSTDK_CUSTOMER_PASSWORD"))
		{
			$cfgarr["POSTDK_CUSTOMER_PASSWORD"] = '';
		}

		if (!defined("ENABLE_SEF_NUMBER_NAME"))
		{
			$cfgarr["ENABLE_SEF_NUMBER_NAME"] = '';
		}

		if (!defined("UNIT_DECIMAL"))
		{
			$cfgarr["UNIT_DECIMAL"] = '';
		}

		if (!defined("ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC"))
		{
			$cfgarr["ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC"] = 0;
		}

		if (!defined("CATEGORY_DESC_MAX_CHARS"))
		{
			$cfgarr["CATEGORY_DESC_MAX_CHARS"] = '';
		}

		if (!defined("CATEGORY_DESC_END_SUFFIX"))
		{
			$cfgarr["CATEGORY_DESC_END_SUFFIX"] = '';
		}

		if (!defined("DEFAULT_QUOTATION_MODE_PRE"))
		{
			$cfgarr["DEFAULT_QUOTATION_MODE_PRE"] = '0';
		}

		if (!defined("SHOW_PRICE_PRE"))
		{
			$cfgarr["SHOW_PRICE_PRE"] = '1';
		}

		if (!defined("QUICKLINK_ICON"))
		{
			$cfgarr["QUICKLINK_ICON"] = '';
		}

		if (!defined("DISPLAY_STOCKROOM_ATTRIBUTES"))
		{
			$cfgarr["DISPLAY_STOCKROOM_ATTRIBUTES"] = '';
		}

		if (!defined("DISPLAY_NEW_ORDERS"))
		{
			$cfgarr["DISPLAY_NEW_ORDERS"] = '0';
		}

		if (!defined("DISPLAY_NEW_CUSTOMERS"))
		{
			$cfgarr["DISPLAY_NEW_CUSTOMERS"] = '0';
		}

		if (!defined("DISPLAY_STATISTIC"))
		{
			$cfgarr["DISPLAY_STATISTIC"] = '0';
		}

		if (!defined("EXPAND_ALL"))
		{
			$cfgarr["EXPAND_ALL"] = '0';
		}

		if (!defined("NOOF_THUMB_FOR_SCROLLER"))
		{
			$cfgarr["NOOF_THUMB_FOR_SCROLLER"] = '3';
		}

		if (!defined("POSTDANMARK_ADDRESS"))
		{
			$cfgarr["POSTDANMARK_ADDRESS"] = 'address';
		}

		if (!defined("POSTDANMARK_POSTALCODE"))
		{
			$cfgarr["POSTDANMARK_POSTALCODE"] = '13256';
		}

		if (!defined("SEND_CATALOG_REMINDER_MAIL"))
		{
			$cfgarr["SEND_CATALOG_REMINDER_MAIL"] = '0';
		}

		if (!defined("AJAX_CART_DISPLAY_TIME"))
		{
			$cfgarr["AJAX_CART_DISPLAY_TIME"] = '3000';
		}

		if (!defined("PAYMENT_CALCULATION_ON"))
		{
			$cfgarr["PAYMENT_CALCULATION_ON"] = 'subtotal';
		}

		if (!defined("IMAGE_QUALITY_OUTPUT"))
		{
			$cfgarr["IMAGE_QUALITY_OUTPUT"] = '100';
		}

		if (!defined("DEFAULT_NEWSLETTER"))
		{
			$cfgarr["DEFAULT_NEWSLETTER"] = '1';
		}

		if (!defined("DETAIL_ERROR_MESSAGE_ON"))
		{
			$cfgarr["DETAIL_ERROR_MESSAGE_ON"] = '1';
		}

		if (!defined("MANUFACTURER_TITLE_MAX_CHARS"))
		{
			$cfgarr["MANUFACTURER_TITLE_MAX_CHARS"] = '';
		}

		if (!defined("MANUFACTURER_TITLE_END_SUFFIX"))
		{
			$cfgarr["MANUFACTURER_TITLE_END_SUFFIX"] = '';
		}

		if (!defined("WRITE_REVIEW_IS_LIGHTBOX"))
		{
			$cfgarr["WRITE_REVIEW_IS_LIGHTBOX"] = '0';
		}

		if (!defined("SPECIAL_DISCOUNT_MAIL_SEND"))
		{
			$cfgarr["SPECIAL_DISCOUNT_MAIL_SEND"] = '1';
		}

		if (!defined("WATERMARK_PRODUCT_ADDITIONAL_IMAGE"))
		{
			$cfgarr["WATERMARK_PRODUCT_ADDITIONAL_IMAGE"] = '0';
		}

		if (!defined("ACCESSORY_AS_PRODUCT_IN_CART_ENABLE"))
		{
			$cfgarr["ACCESSORY_AS_PRODUCT_IN_CART_ENABLE"] = '0';
		}

		if (!defined("ATTRIBUTE_SCROLLER_THUMB_WIDTH"))
		{
			$cfgarr["ATTRIBUTE_SCROLLER_THUMB_WIDTH"] = '50';
		}

		if (!defined("ATTRIBUTE_SCROLLER_THUMB_HEIGHT"))
		{
			$cfgarr["ATTRIBUTE_SCROLLER_THUMB_HEIGHT"] = '50';
		}

		if (!defined("NOOF_SUBATTRIB_THUMB_FOR_SCROLLER"))
		{
			$cfgarr["NOOF_SUBATTRIB_THUMB_FOR_SCROLLER"] = '3';
		}

		if (!defined("COMPARE_PRODUCT_THUMB_WIDTH"))
		{
			$cfgarr["COMPARE_PRODUCT_THUMB_WIDTH"] = '70';
		}

		if (!defined("COMPARE_PRODUCT_THUMB_HEIGHT"))
		{
			$cfgarr["COMPARE_PRODUCT_THUMB_HEIGHT"] = '70';
		}

		if (!defined("CATEGORY_TITLE_MAX_CHARS"))
		{
			$cfgarr["CATEGORY_TITLE_MAX_CHARS"] = '';
		}

		if (!defined("CATEGORY_TITLE_END_SUFFIX"))
		{
			$cfgarr["CATEGORY_TITLE_END_SUFFIX"] = '';
		}

		if (!defined("PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE"))
		{
			$cfgarr["PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE"] = '';
		}

		if (!defined("USE_ENCODING"))
		{
			$cfgarr["USE_ENCODING"] = '0';
		}

		if (!defined("CREATE_ACCOUNT_CHECKBOX"))
		{
			$cfgarr["CREATE_ACCOUNT_CHECKBOX"] = '0';
		}

		if (!defined("SHOW_QUOTATION_PRICE"))
		{
			$cfgarr["SHOW_QUOTATION_PRICE"] = '0';
		}

		if (!defined("CHILDPRODUCT_DROPDOWN"))
		{
			$cfgarr["CHILDPRODUCT_DROPDOWN"] = 'product_name';
		}

		if (!defined("ENABLE_ADDRESS_DETAIL_IN_SHIPPING"))
		{
			$cfgarr["ENABLE_ADDRESS_DETAIL_IN_SHIPPING"] = '0';
		}

		if (!defined("PURCHASE_PARENT_WITH_CHILD"))
		{
			$cfgarr["PURCHASE_PARENT_WITH_CHILD"] = '0';
		}

		if (!defined("CALCULATION_PRICE_DECIMAL"))
		{
			$cfgarr["CALCULATION_PRICE_DECIMAL"] = '4';
		}

		if (!defined("REQUESTQUOTE_IMAGE"))
		{
			$cfgarr["REQUESTQUOTE_IMAGE"] = 'requestquote.gif';
		}

		if (!defined("REQUESTQUOTE_BACKGROUND"))
		{
			$cfgarr["REQUESTQUOTE_BACKGROUND"] = 'requestquotebg.jpg';
		}

		if (!defined("SHOW_PRODUCT_DETAIL"))
		{
			$cfgarr["SHOW_PRODUCT_DETAIL"] = 1;
		}

		if (!defined("WEBPACK_ENABLE_EMAIL_TRACK"))
		{
			$cfgarr["WEBPACK_ENABLE_EMAIL_TRACK"] = 1;
		}

		if (!defined("WEBPACK_ENABLE_SMS"))
		{
			$cfgarr["WEBPACK_ENABLE_SMS"] = 1;
		}

		if (!defined("REQUIRED_VAT_NUMBER"))
		{
			$cfgarr["REQUIRED_VAT_NUMBER"] = 1;
		}

		if (!defined("ACCESSORY_PRODUCT_IN_LIGHTBOX"))
		{
			$cfgarr["ACCESSORY_PRODUCT_IN_LIGHTBOX"] = 0;
		}

		if (!defined("PRODUCT_PREVIEW_IMAGE_WIDTH"))
		{
			$cfgarr["PRODUCT_PREVIEW_IMAGE_WIDTH"] = 100;
		}

		if (!defined("PRODUCT_PREVIEW_IMAGE_HEIGHT"))
		{
			$cfgarr["PRODUCT_PREVIEW_IMAGE_HEIGHT"] = 100;
		}

		if (!defined("CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH"))
		{
			$cfgarr["CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH"] = 100;
		}

		if (!defined("CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT"))
		{
			$cfgarr["CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT"] = 100;
		}

		if (!defined("DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA"))
		{
			$cfgarr["DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA"] = 1;
		}

		if (!defined("SEND_MAIL_TO_CUSTOMER"))
		{
			$cfgarr["SEND_MAIL_TO_CUSTOMER"] = 1;
		}

		if (!defined("AJAX_DETAIL_BOX_WIDTH"))
		{
			$cfgarr["AJAX_DETAIL_BOX_WIDTH"] = 500;
		}

		if (!defined("AJAX_DETAIL_BOX_HEIGHT"))
		{
			$cfgarr["AJAX_DETAIL_BOX_HEIGHT"] = 600;
		}

		if (!defined("AJAX_BOX_WIDTH"))
		{
			$cfgarr["AJAX_BOX_WIDTH"] = 500;
		}

		if (!defined("AJAX_BOX_HEIGHT"))
		{
			$cfgarr["AJAX_BOX_HEIGHT"] = 150;
		}

		if (!defined("MEDIA_ALLOWED_MIME_TYPE"))
		{
			$cfgarr["MEDIA_ALLOWED_MIME_TYPE"] = 'bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls';
		}

		if (!defined("ORDER_MAIL_AFTER"))
		{
			$cfgarr["ORDER_MAIL_AFTER"] = 0;
		}

		if (!defined("STATISTICS_ENABLE"))
		{
			$cfgarr["STATISTICS_ENABLE"] = 1;
		}

		if (!defined("AUTO_GENERATE_LABEL"))
		{
			$cfgarr["AUTO_GENERATE_LABEL"] = 1;
		}

		if (!defined("GENERATE_LABEL_ON_STATUS"))
		{
			$cfgarr["GENERATE_LABEL_ON_STATUS"] = "S";
		}

		$Redconfiguration->manageCFGFile($cfgarr);
	}

	/**
	 * Module installer
	 *
	 * @param   string  $module  Module name
	 * @param   string  $source  Source install folder
	 *
	 * @return  boolean
	 */
	private function redshopInstallModule($module, $source)
	{
		$path = $source . '/plugins/' . $module;

		$installer = new JInstaller;
		$result    = $installer->install($path);

		if ($result)
		{
			// Get a db instance
			$db    = JFactory::getDbo();
			$query = "UPDATE #__extensions SET position='icon', ordering=9, enabled=1 WHERE element=" . $db->Quote($module);
			$db    = JFactory::getDbo();
			$db->setQuery($query);
			$db->query();
		}
		else
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Error installing redSHOP module: ' . $module);
		}

		return $result;
	}

	/**
	 * Get the common JInstaller instance used to install all the extensions
	 *
	 * @return JInstaller The JInstaller object
	 */
	public function getInstaller()
	{
		if (is_null($this->installer))
		{
			$this->installer = new JInstaller;
		}

		return $this->installer;
	}

	/**
	 * Install the package libraries
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function installLibraries($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->libraries->library)
		{
			foreach ($nodes as $node)
			{
				$extName = $node->attributes()->name;
				$extPath = $src . '/libraries/' . $extName;
				$result  = 0;

				if (is_dir($extPath))
				{
					$result = $installer->install($extPath);
				}

				$this->_storeStatus('libraries', array('name' => $extName, 'result' => $result));
			}
		}
	}

	/**
	 * Install the package modules
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function installModules($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->modules->module)
		{
			foreach ($nodes as $node)
			{
				$extName   = $node->attributes()->name;
				$extClient = $node->attributes()->client;
				$extPath   = $src . '/modules/' . $extClient . '/' . $extName;
				$result    = 0;

				if (is_dir($extPath))
				{
					$result = $installer->install($extPath);
				}

				$this->_storeStatus('modules', array('name' => $extName, 'client' => $extClient, 'result' => $result));
			}
		}
	}

	/**
	 * Install the package libraries
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function installPlugins($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->plugins->plugin)
		{
			foreach ($nodes as $node)
			{
				$extName  = $node->attributes()->name;
				$extGroup = $node->attributes()->group;
				$extPath  = $src . '/plugins/' . $extGroup . '/' . $extName;
				$result   = 0;

				if (is_dir($extPath))
				{
					$result = $installer->install($extPath);
				}

				// Store the result to show install summary later
				$this->_storeStatus('plugins', array('name' => $extName, 'group' => $extGroup, 'result' => $result));

				// Enable the installed plugin
				if ($result)
				{
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->update($db->quoteName("#__extensions"));
					$query->set("enabled=1");
					$query->where("type='plugin'");
					$query->where("element=" . $db->quote($extName));
					$query->where("folder=" . $db->quote($extGroup));
					$db->setQuery($query);
					$db->query();
				}
			}
		}
	}

	/**
	 * Uninstall the package libraries
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function uninstallLibraries($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->libraries->library)
		{
			foreach ($nodes as $node)
			{
				$extName = $node->attributes()->name;
				$extPath = $src . '/libraries/' . $extName;
				$result  = 0;

				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='library'")
					->where("element=" . $db->quote($extName));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $installer->uninstall('library', $extId);
				}

				// Store the result to show install summary later
				$this->_storeStatus('libraries', array('name' => $extName, 'result' => $result));
			}
		}
	}

	/**
	 * Uninstall the package modules
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function uninstallModules($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->modules->module)
		{
			foreach ($nodes as $node)
			{
				$extName   = $node->attributes()->name;
				$extClient = $node->attributes()->client;
				$extPath   = $src . '/modules/' . $extClient . '/' . $extName;
				$result    = 0;

				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='module'")
					->where("element=" . $db->quote($extName));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $installer->uninstall('module', $extId);
				}

				// Store the result to show install summary later
				$this->_storeStatus('modules', array('name' => $extName, 'client' => $extClient, 'result' => $result));
			}
		}
	}

	/**
	 * Uninstall the package plugins
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function uninstallPlugins($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->plugins->plugin)
		{
			foreach ($nodes as $node)
			{
				$extName  = $node->attributes()->name;
				$extGroup = $node->attributes()->group;
				$extPath  = $src . '/plugins/' . $extGroup . '/' . $extName;
				$result   = 0;

				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='plugin'")
					->where("element=" . $db->quote($extName))
					->where("folder=" . $db->quote($extGroup));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $installer->uninstall('plugin', $extId);
				}

				// Store the result to show install summary later
				$this->_storeStatus('plugins', array('name' => $extName, 'group' => $extGroup, 'result' => $result));
			}
		}
	}

	/**
	 * Store the result of trying to install an extension
	 *
	 * @param   string  $type    Type of extension (libraries, modules, plugins)
	 * @param   array   $status  The status info
	 *
	 * @return void
	 */
	private function _storeStatus($type, $status)
	{
		// Initialise status object if needed
		if (is_null($this->status))
		{
			$this->status = new stdClass;
		}

		// Initialise current status type if needed
		if (!isset($this->status->{$type}))
		{
			$this->status->{$type} = array();
		}

		// Insert the status
		array_push($this->status->{$type}, $status);
	}
}
