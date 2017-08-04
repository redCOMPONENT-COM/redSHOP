<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.stripe
 * @copyright   Copyright (C) 2008-2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

$mollie  = $displayData['mollie'];
$data    = $displayData['data'];
$params  = $displayData['params'];
$issuers = $mollie->issuers->all();
$options = array();

$defaultOption        = new stdClass;
$defaultOption->value = '';
$defaultOption->text  = JText::_('PLG_REDSHOP_PAYMENT_MOLLIEIDEAL_DEFAULT_SELECT_BANK');

// Setting default option for null.
$options[] = $defaultOption;

foreach ($issuers as $issuer)
{
	$options[] = JHtml::_('select.option', htmlspecialchars($issuer->id), htmlspecialchars($issuer->name));
}

?>
<h3>
	<?php echo JText::_('PLG_REDSHOP_PAYMENT_MOLLIEIDEAL_STEP_HEADER'); ?>
    <img src="http://www.mollie.nl/images/icons/ideal-25x22.gif" alt=""/>
</h3>
<div>
	<form method="post">
		<input type="hidden" name="stap" value="2"/>
		<label><?php echo JText::_('PLG_REDSHOP_PAYMENT_MOLLIEIDEAL_SELECT_BANK'); ?></label>
		<?php echo JHtml::_('select.genericlist', $options, 'issuer'); ?>
        <input type="hidden" name="step" value="2"/>
		<div>
			<input type="submit" value="<?php echo JText::_('PLG_REDSHOP_PAYMENT_MOLLIEIDEAL_NEXTBUTTON'); ?>"/>
		</div>
	</form>
</div>
