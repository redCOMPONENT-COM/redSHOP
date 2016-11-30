<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.QuickBook
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

?>
<div class="input-append">
	<a href="#getCertificateModal" role="button" id="get_connection_button" class="btn btn-primary" data-toggle="modal">
		<?php echo JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_GENERATE_CERTIFICATE') ?>
	</a>
	<?php echo $parentInput; ?>
</div>
