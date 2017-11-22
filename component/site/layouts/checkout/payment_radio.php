<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$checked = '';
$chckedClass = '';

if ($paymentMethodId === $oneMethod->name || $totalPaymentMethod <= 1)
{
	$checked = "checked";
	$chckedClass = "paymentgtwchecked";
}

?>

<div id="<?php echo $oneMethod->name ?>" class="<?php echo $chckedClass ?>">
	<label class="radio" for="<?php echo $oneMethod->name . $p ?>">
	<input
		type="radio"
		name="payment_method_id"
		id="<?php echo $oneMethod->name . $p ?>"
		value="<?php echo $oneMethod->name ?>"
		<?php echo $checked ?>
		onclick="javascript:onestepCheckoutProcess(this.name, '');"
	/>
	<?php echo JText::_('PLG_' . strtoupper($oneMethod->name))  ?>
	</label>
</div>
