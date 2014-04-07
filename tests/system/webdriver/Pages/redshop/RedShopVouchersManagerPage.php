<?php
/**
 * @package     RedSHOP
 * @subpackage  Page
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Page class for the back-end Vouchers Redshop.
 *
 * @package     RedShop.Test
 * @subpackage  Webdriver
 * @since       1.0
 */
class RedShopVouchersManagerPage extends AdminManagerPage
{
	/**
	 * XPath string used to uniquely identify this page
	 *
	 * @var    string
	 *
	 * @since    1.0
	 */
	protected $waitForXpath = "//h2[contains(text(),'Voucher Management')]";

	/**
	 * URL used to uniquely identify this page
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $url = 'administrator/index.php?option=com_redshop&view=voucher';

	/**
	 * Function to add a new voucher
	 *
	 * @param   string  $voucherCode    Code for the new Voucher
	 *
	 * @param   string  $voucherAmount  Amount for the Voucher
	 *
	 * @param   string  $voucherLeft    No. of Vouchers Left
	 *
	 * @return RedShopVouchersManagerPage
	 */
	public function addVoucher($voucherCode = '123', $voucherAmount = '100', $voucherLeft = '10')
	{
		$elementObject = $this->driver;
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('add')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='voucher_code']"));
		$voucherCodeField = $elementObject->findElement(By::xPath("//input[@id='voucher_code']"));
		$voucherCodeField->clear();
		$voucherCodeField->sendKeys($voucherCode);
		$voucherAmountField = $elementObject->findElement(By::xPath("//input[@id='amount']"));
		$voucherAmountField->clear();
		$voucherAmountField->sendKeys($voucherAmount);
		$voucherLeftField = $elementObject->findElement(By::xPath("//input[@id='voucher_left']"));
		$voucherLeftField->clear();
		$voucherLeftField->sendKeys($voucherLeft);
		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//h2[contains(text(),'Voucher Management')]"), 10);
	}

	/**
	 * Function to Edit a Voucher
	 *
	 * @param   string  $field        Field which is to be Edited
	 *
	 * @param   string  $newValue     New Value for the Field
	 *
	 * @param   string  $voucherCode  Code for the Voucher
	 *
	 * @return RedShop2VouchersManagerPage
	 */
	public function editVoucher($field, $newValue, $voucherCode)
	{
		$elementObject = $this->driver;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $voucherCode . "')]"), 10);
		$row = $this->getRowNumber($voucherCode) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-edit']/a"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='voucher_code']"), 10);

		switch ($field)
		{
			case "Voucher Code":
				$voucherCodeField = $elementObject->findElement(By::xPath("//input[@id='voucher_code']"));
				$voucherCodeField->clear();
				$voucherCodeField->sendKeys($newValue);
				break;
			case "Voucher Amount":
				$voucherAmountField = $elementObject->findElement(By::xPath("//input[@id='amount']"));
				$voucherAmountField->clear();
				$voucherAmountField->sendKeys($newValue);
				break;
			case "Voucher Left":
				$voucherLeftField = $elementObject->findElement(By::xPath("//input[@id='voucher_left']"));
				$voucherLeftField->clear();
				$voucherLeftField->sendKeys($newValue);
				break;
		}

		$elementObject->findElement(By::xPath("//a[@onclick=\"Joomla.submitbutton('save')\"]"))->click();
		$elementObject->waitForElementUntilIsPresent(By::xPath("//h2[contains(text(),'Voucher Management')]"), 10);
	}

	/**
	 * Function to Delete a Voucher
	 *
	 * @param   string  $voucherCode  Code of the Voucher which is to be deleted
	 *
	 * @return RedShopVouchersManagerPage
	 */
	public function deleteVoucher($voucherCode)
	{
		$elementObject = $this->driver;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//tbody/tr/td[3]/a[contains(text(),'" . $voucherCode . "')]"), 10);
		$row = $this->getRowNumber($voucherCode) - 1;
		$elementObject->waitForElementUntilIsPresent(By::xPath("//input[@id='cb" . $row . "']"), 10);
		$elementObject->findElement(By::xPath("//input[@id='cb" . $row . "']"))->click();
		$elementObject->findElement(By::xPath("//li[@id='toolbar-delete']/a"))->click();
	}

	/**
	 * Function to Search for a Voucher
	 *
	 * @param   string  $voucherCode  Code of the Voucher
	 *
	 * @return bool True or False depending on the Value
	 */
	public function searchVoucher($voucherCode)
	{
		$row = $this->getRowNumber($voucherCode);

		if ($row > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function to get State of a Voucher
	 *
	 * @param   string  $voucherCode  Code of the Voucher
	 *
	 * @return string  Depending on the Value
	 */
	public function getState($voucherCode)
	{
		$elementObject = $this->driver;
		$row = $this->getRowNumber($voucherCode);
		$text = $elementObject->findElement(By::xPath("//tbody/tr[" . $row . "]/td[9]//a"))->getAttribute(@onclick);

		if (strpos($text, 'unpublish') > 0)
		{
			$result = 'published';
		}

		if (strpos($text, 'publish') > 0)
		{
			$result = 'unpublished';
		}

		return $result;
	}
}
