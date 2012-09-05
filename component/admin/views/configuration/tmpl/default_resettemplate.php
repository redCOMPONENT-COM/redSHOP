<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

echo JText::_('COM_REDSHOP_RESET_TEMPLATE_DESC');

?>

<form action="<?php echo 'index.php?option=com_redshop&view=configuration&task=resetTemplate'; ?>" method="post" name="adminForm2" >
<input type="submit" value="<?php echo JTEXT::_("GO");?>" onclick="return confirm('<?php echo JText::_("ARE_YOU_SURE_YOU_WANT_TO_CONTINUE");?>')">
<input type="hidden" name="task" value="resetTemplate">
</form>