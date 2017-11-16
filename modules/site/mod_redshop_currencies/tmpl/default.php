<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_currencies
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<?php echo $textBefore ?>
<?php if (!empty($currencies)): ?>
<form action="" method="post">
    <br/>
	<?php echo JHTML::_('select.genericlist', $currencies, 'product_currency', 'class="inputbox span12" size="1" ', 'value', 'text', $activeCurrency) ?>
    <input class="button btn btn-small" type="submit" name="submit" value="<?php echo JText::_('MOD_REDSHOP_CURRENCIES_CHANGE_CURRENCY') ?>" />
</form>
<?php endif; ?>
