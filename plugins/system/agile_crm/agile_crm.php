<?php
/**
 * @package     redFORM
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');

/**
 * Integrates redFORM with Agle CRM
 *
 * @package  redFORM.Plugin
 * @since    3.0
 */
class PlgSystemAgile_Crm extends JPlugin
{
	/**
	 * constructor
	 *
	 * @param   object  $subject  subject
	 * @param   array   $params   params
	 */
	public function __construct($subject, $params)
	{
		parent::__construct($subject, $params);
		$this->loadLanguage();
	}

	/**
	 * Called when order is created
	 *
	 * @param   object  $data  redSHOP data
	 *
	 * @return void
	 */
	public function afterOrderCreated($data)
	{
		if (empty($data))
		{
			return;
		}

		$this->createContact($data);
		$this->createDeal($data);
	}

	/**
	 * Called when order is notify
	 *
	 * @param   object  $data  redSHOP data
	 *
	 * @return void
	 */
	public function afterOrderNotify($data)
	{
		$session = JFactory::getSession();
		$dealId  = $session->get('deal_id');

		if ($data->order_payment_status_code == 'Paid')
		{
			return $this->updateDeal($dealId, "Won");
		}
		else
		{
			return $this->updateDeal($dealId, "Lost");
		}

		$session->clear('deal_id');
	}

	/**
	 * Function to create a contact on Agile CRM
	 *
	 * @param   array  $data  redSHOP data
	 *
	 * @return  void
	 */
	public function createContact($data)
	{
		$orderFunctions = order_functions::getInstance();
		$orderDetail    = $orderFunctions->getOrderDetails($data->order_id);
		$billing        = $orderFunctions->getBillingAddress($orderDetail->user_id);

		$address = array(
			"address" => $billing->address,
			"city"    => $billing->city,
			"state"   => $billing->state_code,
			"country" => $billing->country_code
		);

		$contactEmail = $billing->user_email;

		$exist = $this->curlWrap("contacts/search/email/" . $contactEmail, null, "GET", "application/json");

		if (!empty($exist))
		{
			$json = json_decode($exist);
			$this->contact_id = $json->id;

			return;
		}

		$contactJson = array(
			"properties" => array(
				array(
					"name"  => "first_name",
					"value" => $billing->firstname,
					"type"  => "SYSTEM"
					),
				array(
					"name"  => "last_name",
					"value" => $billing->lastname,
					"type"  => "SYSTEM"
					),
				array(
					"name"  => "email",
					"value" => $contactEmail,
					"type"  => "SYSTEM"
					),  
				array(
					"name"  => "address",
					"value" => json_encode($address),
					"type"  => "SYSTEM"
					),
				array(
					"name"  => "company",
					"value" => $billing->company_name,
					"type"  => "SYSTEM"
					),
				array(
					"name"  => "phone",
					"value" => $billing->phone,
					"type"  => "SYSTEM"
				)
			)
		);

		$contactJson = json_encode($contactJson);

		$return = $this->curlWrap("contacts", $contactJson, "POST", "application/json");
		$json = json_decode($return);
		$this->contact_id = $json->id;
	}

	/**
	 * Function to create a deal on Agile CRM
	 *
	 * @param   array  $data  redSHOP data
	 *
	 * @return  void
	 */
	public function createDeal($data)
	{
		$orderFunctions = order_functions::getInstance();
		$orderDetail    = $orderFunctions->getOrderDetails($data->order_id);
		$itemDetail     = $orderFunctions->getOrderItemDetail($data->order_id);
		$product        = array();

		foreach ($itemDetail as $key => $item)
		{
			$product[] = $item->order_item_name;
		}
		
		$opportunityJson = array(
			"name"           => $orderDetail->order_id . ' - ' . implode(' - ', $product),
			"description"    => $orderDetail->customer_note,
			"expected_value" => $data->order_total,
			"milestone"      => $this->params->get('milestone', 'New'),
			"probability"    => $this->params->get('probability', 50),
			"owner_id"       => $this->params->get('owner', 0),
			"close_date"     => time(),
			"contact_ids"    => array($this->contact_id),
			"deal_source_id" => $this->params->get('deal_source', 0),
			"tags"           => $product,
			"tagsWithTime"   => $product,
			"custom_data" => array(
				array(
				  "name"=>"Channel",
				  "value"=>"redSHOP"
				)
			)
		);

		$opportunityJson = json_encode($opportunityJson);

		$return = $this->curlWrap("opportunity", $opportunityJson, "POST", "application/json");
		$json = json_decode($return);
		
		return JFactory::getSession()->set('deal_id', $json->id);
	}

	/**
	 * update Deal from Agile CRM
	 *
	 * @param   int     $dealId  deal ID
	 * @param   string  $status  status
	 *
	 * @return object
	 */
	protected function updateDeal($dealId, $status)
	{
		$opportunityJson = array(
			"id"        => $dealId,
			"milestone" => $status
		);

		$opportunityJson = json_encode($opportunityJson);

		$return = $this->curlWrap("opportunity/partial-update", $opportunityJson, "PUT", "application/json");
	}

	/**
	 * Send data to Agile CRM.
	 *
	 * @param   string  $entity       Entity
	 * @param   string  $data         redSHOP json data
	 * @param   string  $method       Method 
	 * @param   string  $contentType  Content type
	 *
	 * @return  void
	 */
	public function curlWrap($entity, $data, $method, $contentType) 
	{
		if ($contentType == NULL) 
		{
		    $contentType = "application/json";
		}

		$agileUrl = "https://" . $this->params->get('domain') . ".agilecrm.com/dev/api/" . $entity;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);

		switch ($method) 
		{
			case "POST":
				$url = $agileUrl;
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case "GET":
				$url = $agileUrl;
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				break;
			case "PUT":
				$url = $agileUrl;
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case "DELETE":
				$url = $agileUrl;
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
			default:
				break;
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    "Content-type : $contentType;", 'Accept : application/json'
		));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->params->get('email') . ':' . $this->params->get('api_key'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$output = curl_exec($ch);
		curl_close($ch);

		return $output;
	}
}
