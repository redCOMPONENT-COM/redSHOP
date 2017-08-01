<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo JText::_('COM_REDSHOP_RESET_TEMPLATE_DESC');

?>

<form action="<?php echo 'index.php?option=com_redshop&view=configuration&task=resetTemplate'; ?>" method="post"
      name="adminForm2">
	<input type="submit" value="<?php echo JTEXT::_("COM_REDSHOP_GO"); ?>"
	       onclick="return confirm('<?php echo JText::_("COM_REDSHOP_ARE_YOU_SURE_YOU_WANT_TO_CONTINUE"); ?>')">
	<input type="hidden" name="task" value="resetTemplate">
</form>
