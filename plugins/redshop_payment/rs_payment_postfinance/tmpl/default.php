<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JFactory::getDocument()->addScriptDeclaration('
	window.onload = function(){
		sendPostFinanace();
	};

	var sendPostFinanace = function(){
		document.postfinanacefrm.submit();
	};
');

?>
<form id='postfinanacefrm' name='postfinanacefrm' action='<?php echo $postfinanceurl; ?>' method='post'>
	<input type="button" onclick="sendPostFinanace()" value="Submit">
	<?php foreach ($postVariables as $name => $value): ?>
		<input type='hidden' name='<?php echo $name; ?>' value='<?php echo $value; ?>' />
	<?php endforeach; ?>
</form>
