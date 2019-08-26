<?php

use AcceptanceTester\AdminManagerJoomla3Steps;
use Administrator\plugins\PluginPaymentManagerJoomla;

/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


class ModuleMultiCurrencies
{
    public function __construct()
    {
        //install module
        $this->extensionURL   = 'extension url';
        $this->pluginName     = 'Redshop Multi Currencies';
        $this->pluginURL      = 'paid-extensions/tests/releases/modules/';
        $this->pakage         = 'mod_redshop_currencies.zip';
    }

    public function __before(AcceptanceTester $I)
    {
        $I->doAdministratorLogin();
    }

    public function installPlugin(AdminManagerJoomla3Steps $I, $scenario)
    {
        $I->wantTo("install plugin Multi Currencies");
        $I->installExtensionPackageFromURL($this->extensionURL, $this->pluginURL, $this->pakage);
        $I->waitForText(AdminJ3Page:: $messageInstallPluginSuccess, 120, AdminJ3Page::$idInstallSuccess);
        $I->wantTo('Enable Plugin Curencies in Administrator');
        $I->enablePlugin($this->pluginName);
    }

}