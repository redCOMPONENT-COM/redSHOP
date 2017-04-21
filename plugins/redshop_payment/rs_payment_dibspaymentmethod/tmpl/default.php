<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Plugin.Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<form action="<?php echo $dibsurl ?>" id='dibscheckout' name="dibscheckout" target="myNewWin" method="post">
	<?php foreach ($formData as $name => $value): ?>
		<input type="hidden" name="<?php echo $name ?>" value="<?php echo urlencode($value) ?>"/>
	<?php endforeach; ?>
	<?php echo $postString; ?>
	<input type="hidden" name="accepturl" value="<?php echo $acceptUrl; ?>"/>
	<input type="hidden" name="cancelurl" value="<?php echo $cancelUrl; ?>"/>
</form>
<script type="text/javascript">
	function redirectOutput() {
		var w = window.open('', 'Popup_Window', "width=700,height=500,toolbar=1");
		document.dibscheckout.target = 'Popup_Window';
		document.dibscheckout.submit();
		return true;
	}

	window.onload = redirectOutput;
</script>
