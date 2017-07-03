<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template.Extra_Info
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<form action='<?php echo $braintreeUrl ?>' method='post' name='braintreefrm' id='braintreefrm'>
	<?php foreach ($postVariables as $name => $value): ?>
		<input type='hidden' name='$name' value='<?php echo $value ?>' />
	<?php endforeach ?>
</form>
<script type='text/javascript'>
	document.braintreefrm.submit();
</script>