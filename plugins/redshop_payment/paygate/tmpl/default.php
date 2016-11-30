<?php
/**
 * @package     Redshop.Plugins
 * @subpackage  PlgRedshop_PaymentPaygate
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;
$lang = JFactory::getLanguage();

JFactory::getDocument()->addScriptDeclaration('
			jQuery(document).ready(function($) {
				jQuery("#paygateform").submit();
			});
		');
?>
<form action="https://www.paygate.co.za/paywebv2/process.trans" method="post" id="paygateform">
	<?php foreach ($checksumSource as $name => $value): ?>
		<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>" />
	<?php endforeach ?>
</form>
