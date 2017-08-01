<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/25/2017
 * Time: 3:51 PM
 */

namespace AcceptanceTester;


class ConfigurationManageJoomla3Steps extends AdminManagerJoomla3Steps
{
    public function featureUsedStockRoom()
    {
        $I = $this;
        $I->amOnPage(\ConfigurationManageJ3Page::$URL);
        $I->click("Feature Settings");
        $I->waitForElement(['xpath' => "//h3[text()='Rating']"], 60);
        $I->waitForElement(['xpath' => "//h3[text()='Stockroom']"], 60);
        $I->click(\ConfigurationManageJ3Page::$stockRoomYes);
        $I->click("Save");
    }

    public function featureOffStockRoom()
    {
        $I = $this;
        $I->amOnPage(\ConfigurationManageJ3Page::$URL);
        $I->click("Feature Settings");
        $I->waitForElement(['xpath' => "//h3[text()='Rating']"], 60);
        $I->waitForElement(['xpath' => "//h3[text()='Stockroom']"], 60);
        $I->click(\ConfigurationManageJ3Page::$stockRoomNo);
        $I->click("Save");
    }


    public function featureEditInLineYes()
    {
        $I = $this;
        $I->amOnPage(\ConfigurationManageJ3Page::$URL);
        $I->click("Feature Settings");
        $I->waitForElement(['xpath' => "//h3[text()='Inline Edit']"], 60);
        $I->waitForElement(['xpath' => "//h3[text()='Stockroom']"], 60);
        $I->click(\ConfigurationManageJ3Page::$eidtInLineYes);
        $I->click("Save");
    }

    public function featureEditInLineNo()
    {
        $I = $this;
        $I->amOnPage(\ConfigurationManageJ3Page::$URL);
        $I->click("Feature Settings");
        $I->waitForElement(['xpath' => "//h3[text()='Inline Edit']"], 60);
        $I->waitForElement(['xpath' => "//h3[text()='Stockroom']"], 60);
        $I->click(\ConfigurationManageJ3Page::$editInLineNo);
        $I->click("Save");
    }

    public function featureComparisonNo()
    {
        $I = $this;
        $I->amOnPage(\ConfigurationManageJ3Page::$URL);
        $I->click("Feature Settings");
        $I->waitForElement(['xpath' => "//h3[text()='Comparison']"], 60);
        $I->waitForElement(['xpath' => "//h3[text()='Stockroom']"], 60);
        $I->click(\ConfigurationManageJ3Page::$comparisonNo);
        $I->click("Save");
    }

    public function featureComparisonYes()
    {
        $I = $this;
        $I->amOnPage(\ConfigurationManageJ3Page::$URL);
        $I->click("Feature Settings");
        $I->waitForElement(['xpath' => "//h3[text()='Comparison']"], 60);
        $I->waitForElement(['xpath' => "//h3[text()='Stockroom']"], 60);
        $I->click(\ConfigurationManageJ3Page::$comparisonYes);
        $I->click("Save");
    }


    //Price

    public function featurePriceNo()
    {
        $I = $this;
        $I->amOnPage(\ConfigurationManageJ3Page::$URL);
        $I->click("Price");
        $I->waitForElement(['xpath' => "//h3[text()='Main Price Settings']"], 60);
        $I->click(\ConfigurationManageJ3Page::$showPriceNo);
        $I->click("Save");
    }

    public function featurePriceYes()
    {
        $I = $this;
        $I->amOnPage(\ConfigurationManageJ3Page::$URL);
        $I->click("Price");
        $I->waitForElement(['xpath' => "//h3[text()='Main Price Settings']"], 60);
        $I->click(\ConfigurationManageJ3Page::$showPriceYes);
        $I->click("Save");
    }

}