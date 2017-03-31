<?php defined('_JEXEC') or die;

/**
 * File       default.php
 * Created    5/22/13 6:43 AM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU General Public License version 2, or later.
 */

?>
<form>
	<input type="text" name="data">
	<input type="submit" class="add" value="<?php echo JText::_('MOD_SESSION_INPUT_ADD') ?>" />
	<input type="submit" class="delete" value="<?php echo JText::_('MOD_SESSION_INPUT_DELETE') ?>" />
	<input type="submit" class="destroy" value="<?php echo JText::_('MOD_SESSION_INPUT_DESTROY') ?>" />
</form>
<div class="status"></div>