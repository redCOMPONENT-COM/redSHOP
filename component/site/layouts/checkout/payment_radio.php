<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * ==========================
 * @var   array   $displayData        Display data.
 * @var   object  $oneMethod          Payment method data.
 * @var   string  $paymentMethodId    Current payment method ID.
 * @var   integer $totalPaymentMethod Total payment methods.
 * @var   integer $index              Index
 * @var   boolean $checked            Checked payment or not.
 */

extract($displayData);

$checkedClass = $checked ? 'paymentgtwchecked' : '';

$lang = JFactory::getLanguage();
$lang->load('plg_redshop_payment_rs_payment_banktransfer', JPATH_ADMINISTRATOR, 'en-GB', true);
$lang->load('plg_redshop_payment_rs_payment_paypal', JPATH_ADMINISTRATOR, 'en-GB', true);
?>

<div id="<?php echo $oneMethod->name ?>" class="<?php echo $checkedClass ?>">
    <label class="radio" for="<?php echo $oneMethod->name . $index ?>">
        <input
                type="radio"
                name="payment_method_id"
                id="<?php echo $oneMethod->name . $index ?>"
                value="<?php echo $oneMethod->name ?>"
			<?php echo $checked ? 'checked="checked"' : '' ?>
                onclick="javascript:onestepCheckoutProcess(this.name, '');"
        />
		<?php echo JText::_('PLG_' . strtoupper($oneMethod->name)) ?>
    </label>
</div>
