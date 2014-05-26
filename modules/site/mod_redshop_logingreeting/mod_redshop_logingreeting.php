<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_logingreeting
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/order.php';
$order_function = new order_functions;
$user = JFactory::getUser();
$mainparam = $params->def('logging_greeting', 1);
$maintext = $params->def('greeting_text', 1);
$document = JFactory::getDocument();
$document->addStyleSheet("modules/mod_redshop_logingreeting/css/logingreeting.css");
?>
<div id="mod_logingreeting">
	<?php
	if ($user->id != '')
	{
		if ($mainparam == 0)
		{
			?>
			<div class="logingreeting"><?php echo $maintext;?> <?php echo $user->username;?></div>
		<?php }
		else
		{ ?>
			<div
				class="loginname"><?php echo $maintext;?> <?php echo $order_function->getUserFullname($user->user_id);?></div>
		<?php
		}
	}
	?>
</div>