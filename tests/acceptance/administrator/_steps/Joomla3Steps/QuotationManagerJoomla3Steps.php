<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class QuotationManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class QuotationManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
    /**
     * Function to add a New Quotation
     *
     * @return void
     */
    public function addQuotation()
    {
        $I = $this;
        $I->amOnPage(\QuotationManagerPage::$URL);
        $I->click(\QuotationManagerPage::$newButton);
        $I->click(\QuotationManagerPage::$cancelButton);
    }
}
