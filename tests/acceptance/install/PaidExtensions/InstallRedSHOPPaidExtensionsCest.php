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
		$this->packageModules = 'mod_fb_albums.zip';
		$this->packagePlugin  = 'plg_acymailing_redshop.zip';
		$this->extensionURL   = 'extension url';
		$this->modulesURL     = 'paid-extensions/tests/releases/modules/site/';
		$this->pluginURL      = 'paid-extensions/tests/releases/plugins/';

        $this->modules          = array(
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
            ), array(
                'package'  => 'mod_redshop_category_scroller.zip',
            ),
            array(
                'package'  => 'mod_redshop_currencies.zip',
            ), array(
                'package'  => 'mod_redshop_discount.zip',
            ),
            array(
                'package'  => 'mod_redshop_logingreeting.zip',
            ),
            array(
                'package'  => 'mod_redshop_megamenu.zip',
            ), array(
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
            ), array(
                'package'  => 'mod_redshop_wishlist.zip',
            ),
            array(
                'package'  => 'mod_redweb_facebook_plugins.zip',
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
		$I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->packagePlugin);
		$I->waitForText(AdminJ3Page::$messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
	}
}