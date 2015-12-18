<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$uri  = JURI::getInstance();
$url  = $uri->root();
$user = JFactory::getUser();
$db   = JFactory::getDbo();

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperHelper');

$firstname    = $data['billinginfo']->firstname;
$lastname     = $data['billinginfo']->lastname;
$country_code = $data['billinginfo']->country_code;
$zipcode      = $data['billinginfo']->zipcode;
$city         = $data['billinginfo']->city;
$state_code   = $data['billinginfo']->state_code;
$address      = $data['billinginfo']->address;
$phone        = $data['billinginfo']->phone;
?>

<?php
if (isset($_GET["status"]) && ($_GET["status"] == "OK"))
{
	echo "<span style=\"font-weight: bold\">Dziękujemy za dokonanie wpłaty przy użyciu serwisu Dotpay</span><br /><br />";
}
else
{
	$paymentUrl = 'https://ssl.dotpay.pl/test_payment/';

	if (!$this->params->get('testMode'))
	{
		$paymentUrl = 'https://ssl.dotpay.pl/pay.php';
	}

	$formInput = array(
		'id'          => $this->params->get("dotpay_customer_id"),
		'amount'      => $data['carttotal'],
		'currency'    => CURRENCY_CODE,
		'control'     => $orderData['dotpay_control'],
		'description' => 'Payment for order ' . $data['order_id'],
		'lang'        => 'pl',
		'type'        => 0,
		'firstname'   => $firstname,
		'lastname'    => $lastname,
		'email'       => $user_email,
		'control'     => $data['order_id'],
		'url'         => JURI::base() . 'index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_dotpay&orderid=' . $data['order_id'],
		'urlc'        => JURI::base() . 'index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_dotpay&orderid=' . $data['order_id'],
		'city'        => $city,
		'postcode'    => $zipcode,
		'phone'       => $phone,
		'country'     => $country_code,
		'street'      => $address,
		'street_n1'   => $user_address2,
		'api_version' => 'dev'
	);
?>
	<strong>To make a payment, click on the image below:</strong>
	<form action="<?php echo $paymentUrl; ?>" method="post"id="dotpay">
		<div style="text-align: center; margin-top: 25px; margin-bottom: 25px;">
			<input type="image" name="submit"
			       src="<?php echo JURI::base() ?>plugins/redshop_payment/rs_payment_dotpay/dotpay.jpg" border="0"
			       alt="Zapłać przez Dotpay">
		</div>

		<?php foreach ($formInput as $name => $value) :?>
			<input type="hidden" name="id" value="<?php echo $this->params->get("dotpay_customer_id"); ?>"/>
		<?php endforeach; ?>
	</form>
	<script>
		document.getElementById("dotpay").submit();
	</script>
<?php
}
