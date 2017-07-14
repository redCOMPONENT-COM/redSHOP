<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$input = JFactory::getApplication()->input;

if ($input->getInt('ccinfo', 0) == 1)
{
	$post           = $input->post->getArray();
	$post['Itemid'] = $input->getInt('Itemid');

	$this->getOrderAndCcdata("rs_payment_braintree", $post);
}
else
{
	$this->getCredicardForm("rs_payment_braintree", $data);
}
?>
<script type="text/javascript" src="<?php echo JURI::base() ?>media/com_redshop/js/credit_card.js">
</script>
