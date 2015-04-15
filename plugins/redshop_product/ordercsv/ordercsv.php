<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Order CSV export and send email after order update
 *
 * @since  1.3.3.1
 */
class PlgRedshop_Productordercsv extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * CSV file path
	 *
	 * @var  string
	 */
	private static $csvFilePath = '';

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   11.1
	 */
	public function __construct(&$subject, $config = array())
	{
		self::$csvFilePath = __DIR__ . '/order.csv';

		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * This method will trigger when redSHOP order status will be updated.
	 *
	 * @param   object  $data  Order Status Information
	 *
	 * @return  void
	 */
	public function onAjaxOrderStatusUpdate($orderId, $data, &$response)
	{
		if ($data['order_status_all'] !== 'S')
		{
			return '';
		}

		$app = JFactory::getApplication();

		$index    = $app->getUserState("com_redshop.order.batch.ordercsv.currentIndex", 0);
		$html     = '';
		$csvData  = '';

		if ($index == 0)
		{
			$csvData  = '"Felt 1","Felt 2","Felt 3","Felt 4","Felt 5","Felt 6","Felt 7","Felt 8","Felt 9","Felt 10","Felt 11"' . "\n";
		}

		$orderInfo = $this->getOrderDetail($orderId);

		$line   = array();
		$line[] = $orderId;
		$line[] = "HEAD";
		$line[] = $orderInfo->firstname;
		$line[] = $orderInfo->lastname;
		$line[] = $orderInfo->address;
		$line[] = $orderInfo->zipcode;
		$line[] = $orderInfo->city;
		$line[] = $orderInfo->country_code;
		$line[] = $orderInfo->phone;
		$line[] = $orderInfo->user_email;
		$line[] = "webshop";

		// Generate First line
		$csvData .= '"' . implode('","', $line) . '"' . "\n";
		unset($line);

		$line     = array();
		$line[] = $orderId;
		$line[] = "HEAD";
		$line[] = $orderInfo->cdate;
		$line[] = $orderInfo->order_total;
		$line[] = $orderInfo->order_shipping;
		$line[] = $orderInfo->customer_note;
		$line[] = $orderInfo->order_payment_name;

		// Get Shipping Information
		$shippingHelper      = new shipping;
		$orderShippingMethod = explode("|", $shippingHelper->decryptShipping(str_replace(" ", "+", $orderInfo->ship_method_id)));

		$shippingMethodName = '';

		if (count($orderShippingMethod) > 0)
		{
			$shippingMethodName = $orderShippingMethod[1];
		}

		$line[] = $shippingMethodName;
		$line[] = $orderInfo->order_subtotal;

		// Generate Second CSV line
		$csvData .= '"' . implode('","', $line) . '"' . "\n";
		unset($line);

		// Get Order Items detail.
		$orderItems = $this->getOrderItems($orderId);

		for ($i = 0, $n = count($orderItems); $i < $n; $i++)
		{
			$orderItem = $orderItems[$i];

			$line     = array();
			$line[] = $orderId;
			$line[] = "LINE";
			$line[] = $orderItem->order_item_sku;
			$line[] = $orderItem->order_item_name;
			$line[] = $orderItem->product_item_price;
			$line[] = $orderItem->product_quantity;

			// Generate Order Item CSV line
			$csvData .= '"' . implode('","', $line) . '"' . "\n";
			unset($line);
		}

		// Write CSV data in to File.
		$this->writeCsvFile($csvData);

		if ($index == count($data['cid']) - 1)
		{
			$html = $this->sendCSVFile();
			$index = 0;
		}
		else
		{
			$index++;
		}

		// Set current index in user state
		$app->setUserState("com_redshop.order.batch.ordercsv.currentIndex", $index);

		$response['message'] = $response['message'] . '<li class="success text-success">' . $html . '</li>';

		return $html;
	}

	/**
	 * Get CSV File path
	 *
	 * @return  string  CSV File Path
	 */
	private function getCsvFile()
	{
		return self::$csvFilePath;
	}

	/**
	 * Read Order CSV File
	 *
	 * @return  string  CSV Data
	 */
	private function readCsvFile()
	{
		if (JFile::exists(self::$csvFilePath))
		{
			return file_get_contents(self::$csvFilePath);
		}
	}

	/**
	 * Write Order CSV File from buffer
	 *
	 * @param   string  $buffer  String CSV Buffer
	 *
	 * @return  void
	 */
	private function writeCsvFile($buffer)
	{
		if ($this->readCsvFile() === true)
		{
			$csvData = $this->readCsvFile() . $buffer;
		}
		else
		{
			$csvData = $buffer;
		}

		file_put_contents(self::$csvFilePath, $csvData, FILE_APPEND | LOCK_EX);
	}

	/**
	 * Send CSV File in E-mail
	 *
	 * @return  string  Success Message
	 */
	private function sendCSVFile()
	{
		$fromEmail = $this->params->get('send_email_from', 'no-email');
		$fromName  = $this->params->get('from_name', '');
		$to        = $this->params->get('send_email_to', 'no-email');
		$subject   = $this->params->get('email_subject', '');
		$msg       = $this->params->get('email_message', '');

		$attachment = array();
		$attachment[] = $this->getCsvFile();

		if (JFactory::getMailer()->sendMail($fromEmail, $fromName, $to, $subject, $msg, 1, null, null, $attachment))
		{
			unlink($this->getCsvFile());

			return JText::_('PLG_REDSHOP_PRODUCT_ORDERCSV_SEND_EMAIL');
		}
	}

	/**
	 * Get Order Information
	 *
	 * @param   integer  $orderId  Order Information Id
	 *
	 * @return  object   Order Information
	 */
	private function getOrderDetail($orderId)
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('o.*, oui.*, op.order_payment_name')
			->from($db->qn('#__redshop_orders') . ' AS o')
			->leftjoin($db->qn('#__redshop_order_users_info') . ' AS oui ON oui.order_id = o.order_id')
			->leftjoin($db->qn('#__redshop_order_payment') . ' AS op ON op.order_id = o.order_id')
			->where($db->qn('o.order_id') . ' = ' . $db->q($orderId))
			->where($db->qn('oui.address_type') . ' = ' . $db->q('BT'));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$result = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $result;
	}

	/**
	 * Get Order Items using Order ID
	 *
	 * @param   integer  $orderId  Order Information Id
	 *
	 * @return  array    Order Items
	 */
	private function getOrderItems($orderId)
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('oi.*')
			->from($db->qn('#__redshop_order_item') . ' AS oi')
			->where($db->qn('oi.order_id') . ' = ' . $db->q($orderId));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$result = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $result;
	}
}
