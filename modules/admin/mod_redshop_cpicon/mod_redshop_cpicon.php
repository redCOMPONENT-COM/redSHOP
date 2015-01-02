<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  mod_redshop_cpicon
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/modules/mod_redshop_cpicon/styles.css');

?>
<div id="cpanel">
	<div class="icon-wrapper">
		<div class="icon">
			<a href="index.php?option=com_redshop">
				<img src="components/com_redshop/assets/images/redshopcart48.png" alt="redSHOP"><span>redSHOP</span></a>
		</div>
	</div>
</div>
