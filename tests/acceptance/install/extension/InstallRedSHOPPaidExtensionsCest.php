<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\AdminManagerJoomla3Steps;

/**
 * Class InstallRedSHOPPaidExtensionsCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */

class InstallRedSHOPPaidExtensionsCest
{

	/**
	 * InstallRedSHOPPaidExtensionsCest constructor.
	 *
	 * @since 2.1.2
	 */
	public function __construct()
	{
		$this->extensionURL   = 'extension url';
		$this->modulesURL     = 'paid-extensions/tests/releases/modules/site/';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';
		$this->modules        = array(
			array(
				'package'  => 'mod_fb_albums.zip',
			),
			array(
				'package'  => 'mod_redcategoryscroller.zip',
			),
			array(
				'package'  => 'mod_redfeaturedproduct.zip',
			),
			array(
				'package'  => 'mod_redmanufacturer.zip',
			),
			array(
				'package'  => 'mod_redmasscart.zip',
			),
			array(
				'package'  => 'mod_redproducts3d.zip',
			),
			array(
				'package'  => 'mod_redproductscroller.zip',
			),
			array(
				'package'  => 'mod_redproducttab.zip',
			),
			array(
				'package'  => 'mod_redshop_aesir_products.zip',
			),
			array(
				'package'  => 'mod_redshop_category_product_filters.zip',
			),
			array(
				'package'  => 'mod_redshop_category_scroller.zip',
			),
			array(
				'package'  => 'mod_redshop_currencies.zip',
			),
			array(
				'package'  => 'mod_redshop_discount.zip',
			),
			array(
				'package'  => 'mod_redshop_logingreeting.zip',
			),
			array(
				'package'  => 'mod_redshop_megamenu.zip',
			),
			array(
				'package'  => 'mod_redshop_newsletter.zip',
			),
			array(
				'package'  => 'mod_redshop_pricefilter.zip',
			),
			array(
				'package'  => 'mod_redshop_productcompare.zip',
			),
			array(
				'package'  => 'mod_redshop_products.zip',
			),
			array(
				'package'  => 'mod_redshop_products_slideshow.zip',
			),
			array(
				'package'  => 'mod_redshop_promote_free_shipping.zip',
			),
			array(
				'package'  => 'mod_redshop_shoppergroup_category.zip',
			),
			array(
				'package'  => 'mod_redshop_shoppergroup_product.zip',
			),
			array(
				'package'  => 'mod_redshop_shoppergrouplogo.zip',
			),
			array(
				'package'  => 'mod_redshop_tags_similar.zip',
			),
			array(
				'package'  => 'mod_redshop_who_bought.zip',
			),
			array(
				'package'  => 'mod_redshop_wishlist.zip',
			),
			array(
				'package'  => 'mod_redweb_facebook_plugins.zip',
			),
		);

		$this->plugin         = array(
			array(
				'package'  => 'plg_acymailing_redshop.zip',
			),
			array(
				'package'  => 'plg_aesir_field_redshop_product.zip',
			),
			array(
				'package'  => 'plg_ajax_xmlcron.zip',
			),
			array(
				'package'  => 'plg_content_redshop_product.zip',
			),
			array(
				'package'  => 'plg_economic_economic.zip',
			),
			array(
				'package'  => 'plg_editors-xtd_product.zip',
			),
			array(
				'package'  => 'plg_logman_redshop.zip',
			),
			array(
				'package'  => 'plg_logman_redshoporder.zip',
			),
			array(
				'package'  => 'plg_redshop_checkout_kerry_express.zip',
			),
			array(
				'package'  => 'plg_redshop_order_esms.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_baokim.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_cielo.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_dibsdx.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_dotpay.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_ingenico.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_mollieideal.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_nganluong.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_paygate.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_paypalcreditcard.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_payson.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_quickbook.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_quickpay.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_2checkout.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_amazoncheckout.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_authorize.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_authorize_dpm.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_banktransfer_discount.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_banktransfer2.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_beanstream.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_braintree.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_cod.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_dibspaymentmethod.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_eantransfer.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_epayv2.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_eway.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_eway3dsecure.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_giropay.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_imglobal.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_moneris.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_moneybooker.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_payflowpro.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_payment_express.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_payoo.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_postfinance.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_rapid_eway.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_sagepay.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_sagepay_vps.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_worldpay.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_postfinance.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_rapid_eway.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_sagepay.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_sagepay_vps.zip',
			),
			array(
				'package'  => 'plg_redshop_payment_rs_payment_worldpay.zip',
			),
			array(
				'package'  => 'plg_redshop_pdf_dompdf.zip',
			),
			array(
				'package'  => 'plg_redshop_pdf_mpdf.zip',
			),
			array(
				'package'  => 'plg_redshop_product_canonical.zip',
			),
			array(
				'package'  => 'plg_redshop_product_CreateColorImage.zip',
			),
			array(
				'package'  => 'plg_redshop_product_custom_field_mapping.zip',
			),
			array(
				'package'  => 'plg_redshop_product_discount_affect_attribute.zip',
			),
			array(
				'package'  => 'plg_redshop_product_discount_rule.zip',
			),
			array(
				'package'  => 'plg_redshop_product_gift.zip',
			),
			array(
				'package'  => 'plg_redshop_product_gls.zip',
			),
			array(
				'package'  => 'plg_redshop_product_google_microdata.zip',
			),
			array(
				'package'  => 'plg_redshop_product_invoicepdf.zip',
			),
			array(
				'package'  => 'plg_redshop_product_postdanmark.zip',
			),
			array(
				'package'  => 'plg_redshop_product_product_alttext.zip',
			),
			array(
				'package'  => 'plg_redshop_product_sh404urls.zip',
			),
			array(
				'package'  => 'plg_redshop_product_shoppergroup_tags.zip',
			),
			array(
				'package'  => 'plg_redshop_product_stock_notifyemail.zip',
			),
			array(
				'package'  => 'plg_redshop_product_stockroom_status.zip',
			),
			array(
				'package'  => 'plg_redshop_product_sync_b2b.zip',
			),
			array(
				'package'  => 'plg_redshop_product_type_bundle.zip',
			),
			array(
				'package'  => 'plg_redshop_product_type_gift.zip',
			),
			array(
				'package'  => 'plg_redshop_product_wss_datafeed.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_bring.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_default_shipping_gls.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_default_shipping_glsbusiness.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_fedex.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_giaohangnhanh.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_postdanmark.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_self_pickup.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_shipper.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_ups.zip',
			),
			array(
				'package'  => 'plg_redshop_shipping_uspsv4.zip',
			),
			array(
				'package'  => 'plg_redshop_user_cmc_integrate.zip',
			),
			array(
				'package'  => 'plg_redshop_user_joomlamailer_integrate.zip',
			),
			array(
				'package'  => 'plg_redshop_user_registration_acymailing.zip',
			),
			array(
				'package'  => 'plg_redshop_vies_registration_rs_vies_registration.zip',
			),
			array(
				'package'  => 'plg_system_agile_crm.zip',
			),
			array(
				'package'  => 'plg_system_itunesreviews.zip',
			),
			array(
				'package'  => 'plg_system_mvcoverride.zip',
			),
			array(
				'package'  => 'plg_system_quickbook.zip',
			),
			array(
				'package'  => 'plg_system_redlightbox_slideshow.zip',
			),
			array(
				'package'  => 'plg_system_redproductzoom.zip',
			),
			array(
				'package'  => 'plg_system_redshop_product_bundle.zip',
			),
			array(
				'package'  => 'plg_system_redshop_send_discountcode.zip',
			),
			array(
				'package'  => 'plg_user_highrise.zip',
			),
			array(
				'package'  => 'plg_user_redshop_avatar.zip',
			),
			array(
				'package'  => 'plg_xmap_com_redshop.zip',
			),
		);
	}

	/**
	 * Install Extension function
	 * @param   AdminManagerJoomla3Steps $I
	 * @return  void
	 * @since   2.1.2
	 * @throws  \Exception
	 */
	public function installPaidExtensionsModule(AdminManagerJoomla3Steps $I)
	{
		$I->doAdministratorLogin();
		$length = count($this->modules);
		$I->wantToTest($length);
		for($x = 0;  $x < $length; $x ++ )
		{
			$modules  =  $this->modules[$x];
			$I->installExtensionPackageFromURL($this->extensionURL, $this->modulesURL, $modules['package']);
			$I->waitForText(AdminJ3Page:: $messageInstallModuleSuccess, 120, AdminJ3Page::$idInstallSuccess);
		}
	}

	/**
	 * Install Extension function
	 * @param   AdminManagerJoomla3Steps $I
	 * @return  void
	 * @since   2.1.2
	 * @throws  \Exception
	 */
	public function installPaidExtensionsPlugin(AdminManagerJoomla3Steps $I)
	{
		$I->doAdministratorLogin();
		$length = count($this->plugin);
		$I->wantToTest($length);
		for($x = 0;  $x < $length; $x ++ )
		{
			$plugin  =  $this->plugin[$x];
			$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $plugin['package']);
			$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
		}
	}
}