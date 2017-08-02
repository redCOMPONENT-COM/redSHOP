<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class plgEconomicEconomic extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * specific redform plugin parameters
	 *
	 * @var JRegistry object
	 */
	public $_conn = false;

	public $error = 0;

	public $errorMsg = null;

	public $client = '';

	public $termofpayment = null;

	public $contraAccount = 0;

	public $cashbook = 0;

	public $LayoutHandle = null;

	public $UnitHandle;

	public $debtorGroupHandles = null;

	public $ecoparams = null;

	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
		$isEnabled = JPluginHelper::isEnabled('economic');

		if ($isEnabled)
		{
			$this->onEconomicConnection();
		}
		else
		{
			$this->error = 1;
			$this->errorMsg = "Disable Plugin";
		}

		JPlugin::loadLanguage('plg_economic_economic');
	}

	/**
	 * Create e-conomic connection
	 *
	 * @return  void
	 */
	public function onEconomicConnection()
	{
		// Check whether plugin has been unpublished
		if (count($this->params) > 0)
		{
			try
			{
				$soapUrl = 'https://soap.reviso.com/api1/EconomicWebService.asmx?wsdl';

				if ('economic' == $this->params->get('accountType', 'economic'))
				{
					$soapUrl = 'https://api.e-conomic.com/secure/api1/EconomicWebService.asmx?wsdl';
				}

				$this->client = new SoapClient(
					$soapUrl,
					array(
						"trace" => 1,
						"exceptions" => 1,
						"stream_context" => stream_context_create(
							array(
								"http" => array(
									"header" => "X-EconomicAppIdentifier: " . self::getAppIdentifier()
								)
							)
						)
					)
				);
			}
			catch (Exception $exception)
			{
				$this->error = 1;
				echo $this->errorMsg = "Unable to connect soap client - E-conomic Plugin Failure.";
				JError::raiseWarning(21, $exception->getMessage());
			}
			try
			{
				$conn = array(
					'agreementNumber' => $this->params->get('economic_agreement_number', ''),
					'userName'        => $this->params->get('economic_username', ''),
					'password'        => $this->params->get('economic_password', '')
				);
				$this->_conn = $this->client->Connect($conn);
			}
			catch (Exception $exception)
			{
				$this->error = 1;
				echo $this->errorMsg = "e-conomic user is not authenticated. Access denied";

				if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
				{
					JError::raiseWarning(21, "onEconomicConnection:" . $exception->getMessage());
				}
				else
				{
					JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
				}
			}
		}
	}

	/**
	 * Get unique app identifier for e-conomic plugin.
	 *
	 * @see http://techtalk.e-conomic.com/e-conomic-soap-api-now-requires-you-to-specify-a-custom-x-economicappidentifier-header/ X-EconomicAppIdentifier
	 *
	 * @return  string  Unique Identifier string
	 */
	protected static function getAppIdentifier()
	{
		// Getting plugin information
		$manifestFile = simplexml_load_file(__DIR__ . '/economic.xml');

		$appIdentifier = __CLASS__ . '/' . $manifestFile->version
					. ' redshop/' . $manifestFile->redshop
					. ' (http://redcomponent.com/redcomponent/redshop/plugins/economic-accounting; support@redcomponent.com)'
					. ' ' . JFactory::getConfig()->get('sitename');

		return $appIdentifier;
	}

	/**
	 * Method to find debtor number in economic
	 *
	 * @access public
	 * @return array
	 */
	public function Debtor_FindByNumber($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$Handle = $this->client->Debtor_FindByNumber(array('number' => $d ['user_info_id']))->Debtor_FindByNumberResult;

			return $Handle;
		}
		catch (Exception $exception)
		{
			print("<p><i>error msg in Debtor_FindByNumber" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "Debtor_FindByNumber:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to find debtor email in economic.
	 *
	 * @access public
	 * @return array
	 */
	public function Debtor_FindByEmail($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$Handle = $this->client->Debtor_FindByEmail(array('email' => $d ['email']))->Debtor_FindByEmailResult;

			return $Handle;
		}
		catch (Exception $exception)
		{
			print("<p><i>Debtor_FindByEmail:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "Debtor_FindByEmail:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to get debtor group in economic.
	 *
	 * @access public
	 * @return array
	 */
	public function Debtor_GetDebtorGroup($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$debtorHandle = new stdclass;
			$debtorHandle->Number = $d ['user_info_id'];
			$Handle = $this->client->Debtor_GetDebtorGroup(array('debtorHandle' => $debtorHandle))->Debtor_GetDebtorGroupResult;

			return $Handle;
		}
		catch (Exception $exception)
		{
			print("<p><i>Debtor_GetDebtorGroup:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "Debtor_GetDebtorGroup:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to get debtor group
	 *
	 * @access public
	 * @return array
	 */
	public function getDebtorGroup()
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$checkDebtorgrpId = $this->params->get('economic_debtor_group_id', 2);

		if ($this->debtorGroupHandles)
		{
			return $this->debtorGroupHandles;
		}

		$debtorGroupHandles = $this->client->debtorGroup_GetAll()->DebtorGroup_GetAllResult->DebtorGroupHandle;
		$dgrp = array();

		if (is_object($debtorGroupHandles))
		{
			if (isset($debtorGroupHandles->Number))
			{
				$dgrp[] = $debtorGroupHandles->Number;
			}
		}
		else
		{
			for ($i = 0, $in = count($debtorGroupHandles); $i < $in; $i++)
			{
				if ($debtorGroupHandles[$i]->Number)
				{
					$dgrp[] = $debtorGroupHandles[$i]->Number;
				}
			}
		}

		if (count($dgrp) > 0)
		{
			$debtorGroupHandle = new stdclass;

			if (in_array($checkDebtorgrpId, $dgrp))
			{
				$debtorGroupHandle->Number = $checkDebtorgrpId;
			}
			else
			{
				$debtorGroupHandle->Number = $dgrp[0];
			}

			$this->debtorGroupHandles = $debtorGroupHandle;
		}

		return $debtorGroupHandle;
	}

	/**
	 * Method to get term of payment
	 *
	 * @access public
	 * @return array
	 */
	public function getTermOfPayment($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		if (isset($d['economic_payment_terms_id']) && $d['economic_payment_terms_id'] != "" && $d['economic_payment_terms_id'] != 0)
		{
			$checkpaymentId = intval($d['economic_payment_terms_id']);
		}
		else
		{
			$checkpaymentId = $this->params->get('economic_payment_terms', 2);
		}

		if ($this->termofpayment && $this->termofpayment == $checkpaymentId)
		{
			return $this->termofpayment;
		}

		$termsarr = array();
		$termofresultall = $this->client->TermOfPayment_GetAll()->TermOfPayment_GetAllResult;
		$termofpayments = $termofresultall->TermOfPaymentHandle;

		if (is_object($termofpayments))
		{
			if (isset($termofpayments->Id))
			{
				$termsarr[] = $termofpayments->Id;
			}
		}
		else
		{
			for ($i = 0, $in = count($termofpayments); $i < $in; $i++)
			{
				if ($termofpayments[$i]->Id)
				{
					$termsarr[] = $termofpayments[$i]->Id;
				}
			}
		}

		if (count($termsarr) > 0)
		{
			if (in_array($checkpaymentId, $termsarr))
			{
				$this->termofpayment = $checkpaymentId;
			}
			else
			{
				$this->termofpayment = $termsarr[0];
			}
		}

		return $this->termofpayment;
	}

	public function getTermOfPaymentContraAccount($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		if (!$this->termofpayment)
		{
			$this->termofpayment = $this->getTermOfPayment($d);
		}

		try
		{
			$this->contraAccount = 0;

			$termOfPaymentHandle = new stdclass;
			$termOfPaymentHandle->Id = $this->termofpayment;

			$contra_account = $this->client->TermOfPayment_GetContraAccount(array('termOfPaymentHandle' => $termOfPaymentHandle))->TermOfPayment_GetContraAccountResult;

			if (isset($contra_account->Number))
			{
				$this->contraAccount = $contra_account->Number;
			}

			return $this->contraAccount;
		}
		catch (Exception $exception)
		{
			print("<p><i>getTermOfPaymentContraAccount:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "getTermOfPaymentContraAccount:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	public function getCashBookAll()
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$this->cashbook = 1;
			$arr = array();
			$CashBookall = $this->client->CashBook_GetAll()->CashBook_GetAllResult;
			$cashbook = $CashBookall->CashBookHandle;

			if (is_object($cashbook))
			{
				if (isset($cashbook->Number))
				{
					$arr[] = $cashbook->Number;
				}
			}
			else
			{
				for ($i = 0, $in = count($cashbook); $i < $in; $i++)
				{
					if ($cashbook[$i]->Number)
					{
						$arr[] = $cashbook[$i]->Number;
					}
				}
			}

			if (count($arr) > 0)
			{
				$cashbook_number = $this->params->get('economic_cashbook_number', 1);

				if (in_array($cashbook_number, $arr))
				{
					$this->cashbook = $cashbook_number;
				}
				else
				{
					$this->cashbook = $arr[0];
				}
			}

			return $this->cashbook;
		}
		catch (Exception $exception)
		{
			print("<p><i>getCashBookAll:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "getCashBookAll:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to get layout template
	 *
	 * @access public
	 * @return array
	 */
	public function getLayoutTemplate($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		if ($this->LayoutHandle)
		{
			return $this->LayoutHandle;
		}

		$LayoutHandleId = 0;
		$arr = array();
		$resultall = $this->client->TemplateCollection_GetAll()->TemplateCollection_GetAllResult;
		$termofpayments = $resultall->TemplateCollectionHandle;

		if (is_object($termofpayments))
		{
			if (isset($termofpayments->Id))
			{
				$arr[] = $termofpayments->Id;
			}
		}
		else
		{
			for ($i = 0, $in = count($termofpayments); $i < $in; $i++)
			{
				if ($termofpayments[$i]->Id)
				{
					$arr[] = $termofpayments[$i]->Id;
				}
			}
		}

		if (count($arr) > 0)
		{
			if (isset($d['economic_design_layout']) && $d['economic_design_layout'] != "" && $d['economic_design_layout'] != 0)
			{
				$checkId = intval($d['economic_design_layout']);
			}
			else
			{
				$checkId = $this->params->get('economic_layout_id', 19);
			}

			if (in_array($checkId, $arr))
			{
				$LayoutHandleId = $checkId;
			}
			else
			{
				$LayoutHandleId = $arr[0];
			}
		}

		$LayoutHandle = new stdclass;
		$LayoutHandle->Id = $LayoutHandleId;

		$this->LayoutHandle = $LayoutHandle;

		return $LayoutHandle;
	}

	/**
	 * Method to store debtor in economic
	 *
	 * @access public
	 * @return array
	 */
	public function storeDebtor($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$DebtorGroupHandle = $this->getDebtorGroup();

		if (isset($d['eco_user_number']) && $d['eco_user_number'] != "")
		{
			$tmpDebtorGroup = $this->Debtor_GetDebtorGroup($d);

			if (isset($tmpDebtorGroup->Number) && $tmpDebtorGroup->Number == $DebtorGroupHandle->Number)
			{
			}
			else
			{
				$d['eco_user_number'] = '';
			}
		}

		$TermOfPaymentHandle = new stdclass;
		$TermOfPaymentHandle->Id = $this->getTermOfPayment($d);

		$CurrencyHandle = new stdclass;
		$CurrencyHandle->Code = $d ['currency_code'];

		// Changes for store debtor error
		$d ['user_info_id'] = ($d ['eco_user_number'] != "") ? $d ['eco_user_number'] : $d ['user_info_id'];

		if ($d['newuserFlag'])
		{
			$maxDebtor = $this->getMaxDebtor();
			$d ['user_info_id'] = $maxDebtor + 1;
		}

		$Handle = new stdclass;
		$Handle->Number = $d ['user_info_id'];

		$LayoutHandle = $this->getLayoutTemplate($d);

		try
		{
			$userinfo = array
			(
				'Handle'                => $Handle,
				'Number'                => $d ['user_info_id'],
				'DebtorGroupHandle'     => $DebtorGroupHandle,
				'Name'                  => $d['name'],
				'VatZone'               => $d['vatzone'],
				'CINumber'              => $d['vatnumber'],
				'CurrencyHandle'        => $CurrencyHandle,
				'IsAccessible'          => 1,
				'Email'                 => $d ['email'],
				'TelephoneAndFaxNumber' => $d ['phone'],
				'Address'               => $d ['address'],
				'PostalCode'            => $d ['zipcode'],
				'City'                  => $d ['city'],
				'Country'               => $d ['country'],
				'TermOfPaymentHandle'   => $TermOfPaymentHandle,
				'LayoutHandle'          => $LayoutHandle
			);

			// Get Employee to set Our Reference Number
			if ($employeeHandle = $this->employeeFindByNumber($d))
			{
				$userinfo['OurReferenceHandle']	= $employeeHandle;
			}

			if (isset($d['ean_number']) && $d['ean_number'] != "")
			{
				$userinfo = array_merge($userinfo, array('Ean' => $d['ean_number']));
			}

			if (isset($d['maximumcredit']) && $d['maximumcredit'] != 0)
			{
				$userinfo = array_merge($userinfo, array('CreditMaximum' => $d['maximumcredit']));
			}

			if ($d ['eco_user_number'] != "")
			{
				$newDebtorHandle = $this->client->Debtor_UpdateFromData(array("data" => $userinfo))->Debtor_UpdateFromDataResult;
			}
			else
			{
				$newDebtorHandle = $this->client->Debtor_CreateFromData(array("data" => $userinfo))->Debtor_CreateFromDataResult;
			}

			return $newDebtorHandle;
		}
		catch (Exception $exception)
		{
			print("<p><i>storeDebtor:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "storeDebtor:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Get Extra field value for Debtor Reference
	 *
	 * @param   array  $d  User information array
	 *
	 * @return  mixed  User input if found else false
	 */
	protected function getExtraFieldForDebtorRef($d)
	{
		// Get which fields are for employee reference from params
		$extraFieldForDebtorRef = (int) trim($this->params->get('extraFieldForDebtorRef', 0));

		$extraFieldForDebtorCompanyRef = (int) trim($this->params->get('extraFieldForDebtorCompanyRef', 0));

		if ($extraFieldForDebtorRef || $extraFieldForDebtorCompanyRef)
		{
			$usersInfo = JTable::getInstance('user_detail', 'table');
			$usersInfo->load($d['user_info_id']);

			$section = 7;
			$fieldId = $extraFieldForDebtorRef;

			if ($usersInfo->is_company)
			{
				$section = 8;
				$fieldId = $extraFieldForDebtorCompanyRef;
			}

			// Initialiase variables.
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			// Create the base select statement.
			$query->select('data_txt')
				->from($db->qn('#__redshop_fields_data'))
				->where($db->qn('fieldid') . ' = ' . $fieldId)
				->where($db->qn('itemid') . ' = ' . (int) $d['user_info_id'])
				->where($db->qn('section') . ' = ' . $db->q($section));

			// Set the query and load the result.
			$db->setQuery($query);

			try
			{
				return (int) $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				throw new RuntimeException($e->getMessage(), $e->getCode());
			}
		}

		return false;
	}

	/**
	 * Get Employee By Number
	 *
	 * @param   array  $d  User information array
	 *
	 * @return  boolean|object  StdClass Object on success, false on fail.
	 */
	protected function employeeFindByNumber($d)
	{
		$userInput = $this->getExtraFieldForDebtorRef($d);

		// Return false if there is no reference is set
		if (!$userInput)
		{
			return false;
		}

		try
		{
			$employee = $this->client
							->Employee_FindByNumber(
								array(
									"number" => (int) $userInput
								)
							)->Employee_FindByNumberResult;
		}
		catch (Exception $exception)
		{
			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, __METHOD__ . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('COM_REDSHOP_DETAIL_ERROR_MESSAGE_LBL'));
			}
		}

		return $employee;
	}

	public function ProductGroup_FindByNumber($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$productGroup = $this->client->ProductGroup_FindByNumber(array('number' => $d['productgroup_id']))->ProductGroup_FindByNumberResult;

			return $productGroup;
		}
		catch (Exception $exception)
		{
			print("<p><i>ProductGroup_FindByNumber:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "ProductGroup_FindByNumber:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to find product number in economic
	 *
	 * @access public
	 * @return array
	 */
	public function Product_FindByNumber($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$Handle = $this->client->Product_FindByNumber(array('number' => $d ['product_number']))->Product_FindByNumberResult;

			return $Handle;
		}
		catch (Exception $exception)
		{
			print("<p><i>Product_FindByNumber:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "Product_FindByNumber:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to get stock of product in economic
	 *
	 * @access public
	 * @return array
	 */
	public function getProductStock($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$productHandle = new stdclass;
			$productHandle->Number = $d['product_number'];
			$Handle = $this->client->Product_GetInStock(array('productHandle' => $productHandle))->Product_GetInStockResult;

			return $Handle;
		}
		catch (Exception $exception)
		{
			print("<p><i>getProductStock:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "getProductStock:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to get product group
	 *
	 * @access public
	 * @return array
	 */
	public function getProductGroup($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$productGroupHandles = $this->client->ProductGroup_GetAll()->ProductGroup_GetAllResult->ProductGroupHandle;

		for ($i = 0, $in = count($productGroupHandles); $i < $in; $i++)
		{
			if (!$productGroupHandles[$i]->Number)
			{
				$productGroupHandle = new stdclass;
				$productGroupHandle->Number = $productGroupHandles[$i];

				return $productGroupHandle;
				break;
			}

			return $productGroupHandles[$i];
		}

		return $productGroupHandles;
	}

	public function getMaxDebtor()
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$dbt = array();

		try
		{
			$debtors = $this->client->Debtor_GetAll()->Debtor_GetAllResult;
			$debtors = $debtors->DebtorHandle;

			if ($debtors->Number)
			{
				return $debtors->Number;
			}

			for ($i = 0, $in = count($debtors); $i < $in; $i++)
			{
				$dbt[] = $debtors [$i]->Number;
			}

			return max($dbt);
		}
		catch (Exception $exception)
		{
			print("<p><i>getMaxDebtor:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "getMaxDebtor:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	public function getMaxInvoiceNumber()
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$inv = array();
			$invoice = $this->client->Invoice_GetAll()->Invoice_GetAllResult;
			$invoice = $invoice->InvoiceHandle;

			if (is_array($invoice))
			{
				for ($i = 0, $in = count($invoice); $i < $in; $i++)
				{
					$inv[] = $invoice[$i]->Number;
				}
			}
			elseif ($invoice->Number)
			{
				$inv[] = $invoice->Number;
			}
			else
			{
				$inv[] = 0;
			}

			$max = max($inv);

			return $max;
		}
		catch (Exception $exception)
		{
			print("<p><i>getMaxInvoiceNumber: " . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "getMaxInvoiceNumber:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	public function getMaxDraftInvoiceNumber()
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$max = 0;
			$cinv = array();
			$current_invoice = $this->client->CurrentInvoice_GetAll()->CurrentInvoice_GetAllResult;
			$current_invoice = $current_invoice->CurrentInvoiceHandle;

			if (is_array($current_invoice))
			{
				for ($i = 0, $in = count($current_invoice); $i < $in; $i++)
				{
					$cinv[] = $current_invoice[$i]->Id;
				}
			}
			elseif ($current_invoice->Id)
			{
				$cinv[] = $current_invoice->Id;
			}
			else
			{
				$cinv[] = 0;
			}

			$cmax = max($cinv);

			if ($cmax)
			{
				$currentInvoiceHandle = new stdclass;
				$currentInvoiceHandle->Id = $cmax;
				$invoiceData = $this->client
					->CurrentInvoice_GetOtherReference(array('currentInvoiceHandle' => $currentInvoiceHandle))
					->CurrentInvoice_GetOtherReferenceResult;

				if ($invoiceData && is_numeric($invoiceData))
				{
					$max = intval($invoiceData);
				}
			}

			return $max;
		}
		catch (Exception $exception)
		{
			print("<p><i>getMaxDraftInvoiceNumber: " . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "getMaxDraftInvoiceNumber:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	public function getUnitGroup()
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		if ($this->UnitHandle)
		{
			return $this->UnitHandle;
		}

		try
		{
			$UnitHandle = new stdclass;

			$UnitHandleId = 1;
			$arr = array();
			$unitall = $this->client->Unit_GetAll()->Unit_GetAllResult->UnitHandle;

			if (is_array($unitall))
			{
				for ($i = 0, $in = count($unitall); $i < $in; $i++)
				{
					if ($unitall[$i]->Number)
					{
						$arr[] = $unitall[$i]->Number;
					}
				}
			}
			else
			{
				$arr[] = $unitall->Number;
			}

			if (count($arr) > 0)
			{
				$checkId = $this->params->get('economic_units_id', 1);

				if (in_array($checkId, $arr))
				{
					$UnitHandleId = $checkId;
				}
				else
				{
					$UnitHandleId = $arr[0];
				}
			}

			$UnitHandle->Number = $UnitHandleId;
			$this->UnitHandle = $UnitHandle;

			return $UnitHandle;
		}
		catch (Exception $exception)
		{
			print("<p><i>getUnitGroup:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "getUnitGroup:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to store product in economic
	 *
	 * @access public
	 * @return array
	 */
	public function storeProduct($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			if (isset($d['product_group']))
			{
				$productGroupHandle = new stdclass;
				$productGroupHandle->Number = $d['product_group'];
			}
			else
			{
				$productGroupHandle = $this->getProductGroup($d);

				if (!$productGroupHandle->Number)
				{
					$productGroupHandle = new stdclass;
					$productGroupHandle->Number = 1;
				}
			}

			$UnitHandle = $this->getUnitGroup();

			$Handle = new stdclass;
			$Handle->Number = $d ['product_number'];

			$prdinfo = array
			(
				'Handle'             => $Handle,
				'Number'             => $d ['product_number'],
				'ProductGroupHandle' => $productGroupHandle,
				'Name'               => $d['product_name'],
				'BarCode'            => '',
				'SalesPrice'         => $d ['product_price'],
				'CostPrice'          => $d ['product_price'],
				'RecommendedPrice'   => $d ['product_price'],
				'Description'        => $d ['product_s_desc'],
				'UnitHandle'         => $UnitHandle,
				'Volume'             => $d ['product_volume'],
				'IsAccessible'       => 1,
				'InStock'            => $d['product_stock']
			);

			if ($d['eco_prd_number'] != '')
			{
				$prdinfo['BarCode'] = $this->productGetBarCode($Handle);

				return $this->client->Product_UpdateFromData(array('data' => $prdinfo))->Product_UpdateFromDataResult;
			}

			return $this->client->Product_CreateFromData(array('data' => $prdinfo))->Product_CreateFromDataResult;
		}
		catch (Exception $exception)
		{
			print("<p><i>storeProduct:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "storeProduct:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Get product barcode information from e-conomic product
	 *
	 * @param   object  $productHandle  Product Number Handle
	 *
	 * @return  string  Barcode
	 */
	protected function productGetBarCode($productHandle)
	{
		try
		{
			return $this->client->Product_GetBarCode(
								array('productHandle' => $productHandle)
							)->Product_GetBarCodeResult;
		}
		catch (Exception $e)
		{
			print("<p><i>ProductGetBarCode:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "Product_GetBarCode:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to store product group in economic
	 *
	 * @access public
	 * @return array
	 */
	public function storeProductGroup($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$accountHandle = new stdclass;
			$accountHandle->Number = $d['vataccount'];

			$nonaccountHandle = new stdclass;
			$nonaccountHandle->Number = $d['novataccount'];

			$Handle = new stdclass;
			$Handle->Number = $d ['productgroup_id'];

			$prdgrpinfo = array
			(
				'Handle'                                         => $Handle,
				'Number'                                         => $d ['productgroup_id'],
				'Name'                                           => $d['productgroup_name'],
				'AccountForVatLiableDebtorInvoicesCurrentHandle' => $accountHandle,
				'AccountForVatExemptDebtorInvoicesCurrentHandle' => $nonaccountHandle
			);

			if ($d['eco_prdgro_number'] != '')
			{
				$newProductGroupNumber = $this->client->ProductGroup_UpdateFromData(array("data" => $prdgrpinfo))->ProductGroup_UpdateFromDataResult;
			}
			else
			{
				$newProductGroupNumber = $this->client->ProductGroup_CreateFromData(array("data" => $prdgrpinfo))->ProductGroup_CreateFromDataResult;
			}

			return $newProductGroupNumber;
		}
		catch (Exception $exception)
		{
			print("<p><i>error msg storeProductGroup:" . $exception->getMessage() . "</i></p>");
			JError::raiseWarning(21, $exception->getMessage());

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "storeProductGroup:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to get debtor contact handle
	 *
	 * @access public
	 * @return array
	 */
	public function getDebtorContactHandle($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$contacts = $this->client->DebtorContact_FindByExternalId(array('externalId' => $d ['user_info_id']))->DebtorContact_FindByExternalIdResult->DebtorContactHandle;

			if (count($contacts) > 0)
			{
				$contactHandle = new stdclass;

				if (is_array($contacts))
				{
					for ($i = 0, $in = count($contacts); $i < $in; $i++)
					{
						if ($contacts[$i]->Id)
						{
							$contactHandle->Id = $contacts[$i]->Id;
							break;
						}
					}
				}
				else
				{
					$contactHandle->Id = $contacts->Id;
				}

				$d['updateDebtorContact'] = $contactHandle->Id;
				$this->DebtorContact_GetData($d);
			}

			$contactHandle = $this->storeDebtorContact($d);

			return $contactHandle;
		}
		catch (Exception $exception)
		{
			print("<p><i>error msg in getDebtorContactHandle::" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "getDebtorContactHandle:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to get debtor contact handle
	 *
	 * @access public
	 * @return array
	 */
	public function DebtorContact_GetData($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$entityHandle = new stdclass;
			$entityHandle->Id = $d ['updateDebtorContact'];
			$contacts = $this->client->DebtorContact_GetData(array('entityHandle' => $entityHandle))->DebtorContact_GetDataResult;
		}
		catch (Exception $exception)
		{
			print("<p><i>error msg in DebtorContact_GetData::" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "DebtorContact_GetData:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to delete debtor contact handle
	 *
	 * @access public
	 * @return array
	 */
	public function DebtorContact_Delete($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$debtorContactHandle = new stdclass;
			$debtorContactHandle->Id = $d ['user_info_id'];
			$contacts = $this->client->DebtorContact_Delete(array('debtorContactHandle' => $debtorContactHandle));
		}
		catch (Exception $exception)
		{
			print("<p><i>error msg in DebtorContact_Delete::" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "DebtorContact_Delete:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to create debtor contact in economic
	 *
	 * @access public
	 * @return array
	 */
	public function storeDebtorContact($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			if (isset($d['updateDebtorContact']) && $d['updateDebtorContact'] != "")
			{
				$Id = $d ['updateDebtorContact'];
			}
			else
			{
				$Id = $d ['user_info_id'];
			}

			$Handle = new stdclass;
			$Handle->Id = $Id;

			$debtorHandle = new stdclass;
			$debtorHandle->Number = $d ['debtorHandle'];

			$info = array
			(
				'Handle'                        => $Handle,
				'DebtorHandle'                  => $debtorHandle,
				'Id'                            => $Id,
				'Number'                        => $Id,
				'Name'                          => $d['name'],
				'Email'                         => $d['email'],
				'TelephoneNumber'               => $d['phone'],
				'ExternalId'                    => $d['user_info_id'],
				'IsToReceiveEmailCopyOfOrder'   => 0,
				'IsToReceiveEmailCopyOfInvoice' => 1
			);

			if (isset($d['updateDebtorContact']) && $d['updateDebtorContact'] != "")
			{
				$contactHandle = $this->client->DebtorContact_UpdateFromData(array('data' => $info))->DebtorContact_UpdateFromDataResult;
			}
			else
			{
				$contactHandle = $this->client->DebtorContact_CreateFromData(array('data' => $info))->DebtorContact_CreateFromDataResult;
			}

			return $contactHandle;
		}
		catch (Exception $exception)
		{
			print("<p><i>storeDebtorContact:: " . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "storeDebtorContact:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to create invoice in economic
	 *
	 * @access public
	 * @return array
	 */
	public function createInvoice($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$CurrencyHandle = new stdclass;
		$CurrencyHandle->Code = $d ['currency_code'];

		$TermOfPaymentHandle = new stdclass;
		$TermOfPaymentHandle->Id = $this->getTermOfPayment($d);

		$debtorHandle = new stdclass;
		$debtorHandle->Number = $d['debtorHandle'];

		try
		{
			if (isset($d['setAttname']) && $d['setAttname'] == 1)
			{
				$debtorContactHandle = $this->getDebtorContactHandle($d);

				$this->client->Debtor_SetAttention(array('debtorHandle' => $debtorHandle, 'valueHandle' => $debtorContactHandle));
			}

			$this->client->Debtor_SetVatZone(array('debtorHandle' => $debtorHandle, 'value' => $d['vatzone']));

			$invoiceHandle = $this->client->CurrentInvoice_Create(array('debtorHandle' => $debtorHandle))->CurrentInvoice_CreateResult;

			$this->client->CurrentInvoice_SetCurrency(array('currentInvoiceHandle' => $invoiceHandle, 'valueHandle' => $CurrencyHandle));

			$this->client->CurrentInvoice_SetTermOfPayment(array('currentInvoiceHandle' => $invoiceHandle, 'valueHandle' => $TermOfPaymentHandle));

			$this->client->CurrentInvoice_SetIsVatIncluded(array('currentInvoiceHandle' => $invoiceHandle, 'value' => $d['isvat']));

			$this->client->CurrentInvoice_SetOtherReference(array('currentInvoiceHandle' => $invoiceHandle, 'value' => $d['order_number']));

			// Get Employee to set Our Reference Number
			if ($employeeHandle = $this->employeeFindByNumber($d))
			{
				$this->client->CurrentInvoice_SetOurReference2(
					array(
						'currentInvoiceHandle' => $invoiceHandle,
						'valueHandle'          => $employeeHandle
					)
				);
			}

			$reference = '';

			if (isset($d['order_number']) && $d['order_number'] != "")
			{
				$reference .= JText::_('COM_REDSHOP_ORDER_NUMBER') . ': ' . $d['order_number'];
			}

			if (isset($d['requisition_number']) && $d['requisition_number'] != "")
			{
				$reference .= chr(13) . '' . JText::_('COM_REDSHOP_REQUISITION_NUMBER') . ': ' . $d['requisition_number'];
			}

			if (isset($d['customer_note']) && $d['customer_note'] != "")
			{
				$reference .= chr(13) . '' . JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL') . ': ' . $d['customer_note'];
			}

			$this->client->CurrentInvoice_SetTextLine1(array('currentInvoiceHandle' => $invoiceHandle, 'value' => $reference));

			return $invoiceHandle;
		}
		catch (Exception $exception)
		{
			print("<p><i>createInvoice:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "createInvoice:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	public function deleteInvoice($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$invoiceHandle = new stdclass;
		$invoiceHandle->Id = $d ['invoiceHandle'];

		try
		{
			$this->client->CurrentInvoice_Delete(array('currentInvoiceHandle' => $invoiceHandle));
		}
		catch (Exception $exception)
		{
		}
	}

	/**
	 * Method to set Delivery Address in economic
	 *
	 * @access public
	 * @return array
	 */
	public function setDeliveryAddress($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$invoiceHandle = new stdclass;
			$invoiceHandle->Id = $d['invoiceHandle'];

			if ($d ['address_ST'] != '')
			{
				$this->client
					->CurrentInvoice_SetDeliveryAddress(array('currentInvoiceHandle' => $invoiceHandle, 'value' => $d ['address_ST']));
			}

			if ($d ['name_ST'] != '')
			{
			}

			if ($d ['city_ST'] != '')
			{
				$this->client
					->CurrentInvoice_SetDeliveryCity(array('currentInvoiceHandle' => $invoiceHandle, 'value' => $d ['city_ST']));
			}

			if ($d ['country_ST'] != '')
			{
				$this->client
					->CurrentInvoice_SetDeliveryCountry(array('currentInvoiceHandle' => $invoiceHandle, 'value' => $d ['country_ST']));
			}

			if ($d ['zipcode_ST'] != '')
			{
				$this->client
					->CurrentInvoice_SetDeliveryPostalCode(array('currentInvoiceHandle' => $invoiceHandle, 'value' => $d ['zipcode_ST']));
			}
		}
		catch (Exception $exception)
		{
			print("<p><i>setDeliveryAddress:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "setDeliveryAddress:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to create invoice line in economic
	 *
	 * @access public
	 * @return array
	 */
	public function createInvoiceLine($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$order_item_id = $d ['order_item_id'];
		$invoiceHandle = new stdclass;
		$invoiceHandle->Id = $d['invoiceHandle'];

		$Handle = new stdclass;
		$Handle->Id = $d['invoiceHandle'];
		$Handle->Number = $order_item_id;

		$UnitHandle = $this->getUnitGroup();

		$ProductHandle = new stdclass;
		$ProductHandle->Number = $d ['product_number'];

		try
		{
			$info = array
			(
				'Handle'            => $Handle,
				'InvoiceHandle'     => $invoiceHandle,
				'Number'            => $order_item_id,
				'Id'                => $d['invoiceHandle'],
				'Description'       => $d ['product_name'],
				'DeliveryDate'      => $d ['delivery_date'],
				'UnitHandle'        => $UnitHandle,
				'ProductHandle'     => $ProductHandle,
				'UnitNetPrice'      => $d['product_price'],
				'Quantity'          => $d['product_quantity'],
				'DiscountAsPercent' => 0,
				'UnitCostPrice'     => $d['product_price'],
				'TotalMargin'       => $d['product_price'],
				'TotalNetAmount'    => $d['product_price'],
				'MarginAsPercent'   => 1
			);

			if (isset($d['updateInvoice']) && $d['updateInvoice'] == 1)
			{
				$invoiceLineNumber = $this->client
					->CurrentInvoiceLine_UpdateFromData(array('data' => $info))->CurrentInvoiceLine_UpdateFromDataResult;
			}
			else
			{
				$invoiceLineNumber = $this->client
					->CurrentInvoiceLine_CreateFromData(array('data' => $info))->CurrentInvoiceLine_CreateFromDataResult;
			}

			return $invoiceLineNumber;
		}
		catch (Exception $exception)
		{
			print("<p><i>createInvoiceLine:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "createInvoiceLine:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to create invoice Array in economic.
	 *
	 * @param $d
	 * @param $darray
	 *
	 * @return null|string
	 */
	public function createInvoiceLineArray($d, $darray)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$order_item_id = $d ['order_item_id'];
		$invoiceHandle = new stdclass;
		$invoiceHandle->Id = $d['invoiceHandle'];

		$Handle = new stdclass;
		$Handle->Id = $d['invoiceHandle'];
		$Handle->Number = $order_item_id;

		$UnitHandle = $this->getUnitGroup();

		try
		{
			for ($i = 0, $in = count($darray); $i < $in; $i++)
			{
				$ProductHandle = new stdclass;
				$ProductHandle->Number = $darray[$i]['product_number'];
				$info[] = array(
					'CurrentInvoiceLineData' => array
					(
						'Handle'            => $Handle,
						'InvoiceHandle'     => $invoiceHandle,
						'Number'            => $order_item_id,
						'Id'                => $d['invoiceHandle'],
						'Description'       => $darray[$i]['product_name'],
						'DeliveryDate'      => $darray[$i]['delivery_date'],
						'UnitHandle'        => $UnitHandle,
						'ProductHandle'     => $ProductHandle,
						'UnitNetPrice'      => $darray[$i]['product_price'],
						'Quantity'          => $darray[$i]['product_quantity'],
						'DiscountAsPercent' => 0,
						'UnitCostPrice'     => $darray[$i]['product_price'],
						'TotalMargin'       => $darray[$i]['product_price'],
						'TotalNetAmount'    => $darray[$i]['product_price'],
						'MarginAsPercent'   => 1
					)
				);
			}

			if (isset($d['updateInvoice']) && $d['updateInvoice'] == 1)
			{
				$invoiceLineNumber = $this->client
					->CurrentInvoiceLine_UpdateFromDataArray(array('dataArray' => $info))->CurrentInvoiceLine_UpdateFromDataArrayResult;
			}
			else
			{
				$invoiceLineNumber = $this->client
					->CurrentInvoiceLine_CreateFromDataArray(array('dataArray' => $info))->CurrentInvoiceLine_CreateFromDataArrayResult;
			}

			return $invoiceLineNumber;
		}
		catch (Exception $exception)
		{
			print("<p><i>createInvoiceLineArray:" . $exception->getMessage() . "</i></p>");


			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "createInvoiceLineArray:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to check invoice draft in economic
	 *
	 * @access public
	 * @return array
	 */
	public function checkDraftInvoice($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$invoiceHandle = new stdclass;
		$invoiceHandle->Id = $d['invoiceHandle'];

		try
		{
			$invoiceData = $this->client->CurrentInvoice_GetData(array('entityHandle' => $invoiceHandle))->CurrentInvoice_GetDataResult;

			return $invoiceData;
		}
		catch (Exception $exception)
		{
			print("<p><i>checkDraftInvoice:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "checkDraftInvoice:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to check invoice booked in economic
	 *
	 * @access public
	 * @return array
	 */
	public function checkBookInvoice($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$bookInvoiceData = $this->client->Invoice_FindByOtherReference(array('otherReference' => $d['order_number']))->Invoice_FindByOtherReferenceResult;

			return $bookInvoiceData;
		}
		catch (Exception $exception)
		{
			print("<p><i>checkBookInvoice:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "checkBookInvoice:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to update invoice in economic
	 *
	 * @access public
	 * @return array
	 */
	public function updateInvoiceDate($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$invoiceHandle = new stdclass;
		$invoiceHandle->Id = $d['invoiceHandle'];

		try
		{
			$invoiceNumber = $this->client
				->CurrentInvoice_SetDate(array('currentInvoiceHandle' => $invoiceHandle, 'value' => $d['invoiceDate']))
				->CurrentInvoice_SetDateResponse;

			return $invoiceNumber;
		}
		catch (Exception $exception)
		{
			print("<p><i>updateInvoiceDate:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "updateInvoiceDate:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to book invoice
	 *
	 * @access public
	 * @return array
	 */
	public function bookInvoice($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		// Send pdf from economic
		$pdf = '';

		$bookHandle = new stdclass;
		$bookHandle->Number = $d['bookinvoice_number'];

		if ($bookHandle)
		{
			$pdf = $this->Invoice_GetPdf($bookHandle);

			// Cashbook entry
			$makeCashbook = (int) $this->params->get('economicUseCashbook', 1);

			if ($makeCashbook && $d['amount'] > 0)
			{
				$this->createCashbookEntry($d, $bookHandle);
			}

			// Cashbook Entry for Creditor Payment to Paypal
			if($makeCashbook && isset($d['order_transfee']) && $this->params->get('economicCreditorNumber', false))
			{
				$this->createCashbookEntryCreditorPayment($d, $bookHandle);
			}
		}

		return $pdf;
	}

	/**
	 * Method to find current book invoice number in economic.
	 *
	 * @param $d
	 *
	 * @return null|string
	 */
	public function CurrentInvoice_BookWithNumber($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$invoiceHandle     = new stdclass;
		$invoiceHandle->Id = $d['invoiceHandle'];

		try
		{
			$info = array(
				'currentInvoiceHandle' => $invoiceHandle,
				'number'               => $d['order_number']
			);

			$bookHandle = $this->client->CurrentInvoice_BookWithNumber($info)->CurrentInvoice_BookWithNumberResult;

			return $bookHandle;
		}
		catch (Exception $exception)
		{
			print("<p><i>CurrentInvoice_BookWithNumber:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "CurrentInvoice_BookWithNumber:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to find current book invoice in economic
	 *
	 * @access public
	 * @return array
	 */
	public function CurrentInvoice_Book($d)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$invoiceHandle = new stdclass;
		$invoiceHandle->Id = $d['invoiceHandle'];

		try
		{
			$info = array(
				'currentInvoiceHandle' => $invoiceHandle,
			);

			$bookHandle = $this->client->CurrentInvoice_Book($info)->CurrentInvoice_BookResult;

			return $bookHandle;
		}
		catch (Exception $exception)
		{
			print("<p><i>CurrentInvoice_Book:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "CurrentInvoice_Book:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to get pdf invoice
	 *
	 * @access public
	 * @return array
	 */
	public function Invoice_GetPdf($invoiceHandle)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		try
		{
			$pdf = $this->client->Invoice_GetPdf(array('invoiceHandle' => $invoiceHandle))->Invoice_GetPdfResult;

			return $pdf;
		}
		catch (Exception $exception)
		{
			print("<p><i>Invoice_GetPdf:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "Invoice_GetPdf:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to create cash book entry in economic
	 *
	 * @access public
	 * @return array
	 */
	public function createCashbookEntry($d, $bookHandle)
	{
		// Cashbook entry
		$makeCashbook = (int) $this->params->get('economicUseCashbook', 1);

		if (!$makeCashbook)
		{
			return;
		}

		if ($this->error)
		{
			return $this->errorMsg;
		}

		$cashbooknumber = intval($this->getCashBookAll());
		$contraaccount = intval($this->getTermOfPaymentContraAccount($d));

		$cashBookHandle = new stdclass;
		$cashBookHandle->Number = $cashbooknumber;

		$debtorHandle = new stdclass;
		$debtorHandle->Number = $d['debtorHandle'];

		$contraAccountHandle = new stdclass;
		$contraAccountHandle->Number = $contraaccount;

		$CurrencyHandle = new stdclass;
		$CurrencyHandle->Code = $d ['currency_code'];

		try
		{
			if ($contraaccount)
			{
				$info = array(
					'cashBookHandle'      => $cashBookHandle,
					'debtorHandle'        => $debtorHandle,
					'contraAccountHandle' => $contraAccountHandle
				);
			}
			else
			{
				$info = array(
					'cashBookHandle' => $cashBookHandle,
					'debtorHandle'   => $debtorHandle
				);
			}

			$cashBookEntryHandle = $this->client->CashBookEntry_CreateDebtorPayment($info)->CashBookEntry_CreateDebtorPaymentResult;

			$this->client->CashBookEntry_SetAmount(array('cashBookEntryHandle' => $cashBookEntryHandle, 'value' => (0 - $d ['amount'])));

			$this->client->CashBookEntry_SetDebtorInvoiceNumber(array('cashBookEntryHandle' => $cashBookEntryHandle, 'value' => $bookHandle->Number));

			$this->client
				->CashBookEntry_SetText(
					array(
						'cashBookEntryHandle' => $cashBookEntryHandle,
						'value' => 'INV (' . $bookHandle->Number . ') ORDERID (' . $d ['order_id'] . ') CUST (' . $d['name'] . ')'
					)
				);

			$this->client->CashBookEntry_SetCurrency(array('cashBookEntryHandle' => $cashBookEntryHandle, 'valueHandle' => $CurrencyHandle));

			$this->client->CashBook_Book(array('cashBookHandle' => $cashBookHandle));
		}
		catch (Exception $exception)
		{

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}

	/**
	 * Method to create cash book entry in economic for Merchant Fees
	 *
	 * @param   array   $d           Information about booking invoice
	 * @param   Object  $bookHandle  SOAP Object of the e-conomic current book invoice
	 *
	 * @return  void
	 */
	public function createCashbookEntryCreditorPayment($d, $bookHandle)
	{
		if ($this->error)
		{
			return $this->errorMsg;
		}

		$cashBookHandle              = new stdclass;
		$cashBookHandle->Number      = intval($this->getCashBookAll());

		$debtorHandle                = new stdclass;
		$debtorHandle->Number        = $this->params->get('economicCreditorNumber');

		$contraaccount               = intval($this->getTermOfPaymentContraAccount($d));

		$contraAccountHandle         = new stdclass;
		$contraAccountHandle->Number = $contraaccount;

		$CurrencyHandle              = new stdclass;
		$CurrencyHandle->Code        = $d ['currency_code'];

		try
		{
			if ($contraaccount)
			{
				$info = array(
					'cashBookHandle'      => $cashBookHandle,
					'creditorHandle'      => $debtorHandle,
					'contraAccountHandle' => $contraAccountHandle
				);
			}
			else
			{
				$info = array(
					'cashBookHandle' => $cashBookHandle,
					'creditorHandle' => $debtorHandle
				);
			}

			$cashBookEntryHandle = $this->client->CashBookEntry_CreateCreditorPayment($info)
												->CashBookEntry_CreateCreditorPaymentResult;

			$this->client->CashBookEntry_SetAmount(
				array(
					'cashBookEntryHandle' => $cashBookEntryHandle,
					'value'               => $d['order_transfee']
				)
			);

			$this->client->CashBookEntry_SetCreditor(
				array(
					'cashBookEntryHandle' => $cashBookEntryHandle,
					'valueHandle'         => $debtorHandle
				)
			);

			$this->client->CashBookEntry_SetText(
				array(
					'cashBookEntryHandle' => $cashBookEntryHandle,
					'value'               => JText::_('COM_REDSHOP_ECONOMIC_CREDITOR_TEXT')
				)
			);

			$this->client->CashBookEntry_SetCurrency(
				array(
					'cashBookEntryHandle' => $cashBookEntryHandle,
					'valueHandle'         => $CurrencyHandle
				)
			);

			$this->client->CashBook_Book(
				array(
					'cashBookHandle' => $cashBookHandle
				)
			);
		}
		catch ( Exception $exception )
		{
			print("<p><i>createCashbookEntry:" . $exception->getMessage() . "</i></p>");

			if (Redshop::getConfig()->get('DETAIL_ERROR_MESSAGE_ON'))
			{
				JError::raiseWarning(21, "createCashbookEntry:" . $exception->getMessage());
			}
			else
			{
				JError::raiseWarning(21, JText::_('DETAIL_ERROR_MESSAGE_LBL'));
			}
		}
	}
}
