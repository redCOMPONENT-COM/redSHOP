<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_ajax_migrate_redshop
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<form>
	<input type="text" name="data">
	<input type="submit" class="add" value="<?php echo JText::_('MOD_SESSION_INPUT_ADD') ?>" />
	<input type="submit" class="delete" value="<?php echo JText::_('MOD_SESSION_INPUT_DELETE') ?>" />
	<input type="submit" class="destroy" value="<?php echo JText::_('MOD_SESSION_INPUT_DESTROY') ?>" />
</form>
<div class="status"></div>
