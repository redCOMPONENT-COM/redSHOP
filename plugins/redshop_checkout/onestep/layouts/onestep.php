<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<label class="radio-inline">
	<input type="radio" name="togglerchecker" class="toggler" onclick="getBillingTemplate(this);" value="2" billing_type="ean"/>
	<?php echo JText::_('PLG_REDSHOP_CHECKOUT_ONESTEP_EAN');?>
</label>
