<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class InstallPaidExtensionsCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    2.1.2
 */

class InstallPaidExtensionsCest
{

    /**
     * InstallAesirCest constructor.
     *
     * @since 5.10.0
     */
    public function __construct()
    {
        $this->packageModules = 'mod_fb_albums.zip';
        $this->packagePlugin = 'plg_acymailing_redshop.zip';
        $this->extensionPath = 'extension folder';
    }

    /**
     * Install Extension function
     * @param   AcceptanceTester $I
     * @return  void
     * @since   1.0.0
     * @throws  \Exception
     */
    public function installPaidExtensionsModule(AcceptanceTester $I)
    {
        $I = $this;
        $I->amOnPage(\AdminJ3Page::$installURL);
        $I->waitForElement(\AdminJ3Page::$link, 30);
        $I->click(\AdminJ3Page::$link);
        $path = $I->getConfig( $this->extensionPath) .'tests/extension/paid-extensions/tests/releases/modules/site'.  $this->packageModules;
        $I->wantToTest($path);
        $I->comment($path);
        try {
            $I->waitForElementVisible(\AdminJ3Page::$urlID, 10);
        } catch (\Exception $e) {
            $I->click(\AdminJ3Page::$link);
            $I->waitForElementVisible(\AdminJ3Page::$urlID, 10);
        }
        $I->fillField(\AdminJ3Page::$urlID, $path);
        $I->waitForElement(\AdminJ3Page::$installButton, 30);
        $I->click(\AdminJ3Page::$installButton);
        $I->waitForText('Installation of the module was successful.', 120, ['id' => 'system-message-container']);
    }

    /**
     * Install Extension function
     * @param   AcceptanceTester $I
     * @return  void
     * @since   1.0.0
     * @throws  \Exception
     */
    public function installPaidExtensionsPlugin(AcceptanceTester $I)
    {
        $I = $this;
        $I->amOnPage(\AdminJ3Page::$installURL);
        $I->waitForElement(\AdminJ3Page::$link, 30);
        $I->click(\AdminJ3Page::$link);
        $path = $I->getConfig( $this->extensionPath) .'tests/extension/paid-extensions/tests/releases/plugins'.  $this->packagePlugin;
        $I->wantToTest($path);
        $I->comment($path);
        try {
            $I->waitForElementVisible(\AdminJ3Page::$urlID, 10);
        } catch (\Exception $e) {
            $I->click(\AdminJ3Page::$link);
            $I->waitForElementVisible(\AdminJ3Page::$urlID, 10);
        }
        $I->fillField(\AdminJ3Page::$urlID, $path);
        $I->waitForElement(\AdminJ3Page::$installButton, 30);
        $I->click(\AdminJ3Page::$installButton);
        $I->waitForText('Installation of the plugin was successful.', 120, ['id' => 'system-message-container']);
    }
}