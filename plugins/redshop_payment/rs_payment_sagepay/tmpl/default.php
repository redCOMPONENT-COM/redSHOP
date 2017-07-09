<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<form action="<?php echo $strRedirecturl ?>" method="post" name="frmsagepay">
	<?php foreach ($postVariables as $name => $value): ?>
		<input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>" />
	<?php endforeach ?>
</form>
<script>
	document.frmsagepay.submit();
</script>
