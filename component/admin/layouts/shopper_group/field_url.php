<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
/**
 * Layout variables
 * ======================================
 *
 * @var  object $label
 * @var  int    $shopperGroupId
 * @var  array  $displayData
 */
extract($displayData);
?>
<div class="form-group row-fluid ">
	<?php echo $label ?>
	<div class="col-md-10">
		<a href="<?php
		echo JURI::root(
			) . "index.php?option=com_redshop&view=login&layout=portal&protalid=" . $shopperGroupId . "&Itemid=" . Redshop::getConfig(
			)->get('PORTAL_LOGIN_ITEMID'); ?>"
		   target="_blank"><?php
			echo JURI::root(
				) . "index.php?option=com_redshop&view=login&layout=portal&protalid=" . $shopperGroupId . "&Itemid=" . Redshop::getConfig(
				)->get('PORTAL_LOGIN_ITEMID'); ?></a>
	</div>
</div>