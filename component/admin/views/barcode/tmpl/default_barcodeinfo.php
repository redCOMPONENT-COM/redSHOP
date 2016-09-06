<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JHTMLBehavior::modal();
$url = JURI::base();

$order_id = JRequest::getVar('order_id');

?>
<div>
	<table class="adminlist table table-striped">
		<tr>
			<td width="15%"><?php echo JText::_('COM_REDSHOP_NUMBER_OF_VIEWS'); ?> :</td>
			<td>
				<a href='index.php?option=com_redshop&view=barcode&order_id=<?php echo $order_id ?>&log=log'><?php echo $this->logData->log?></a>
			</td>

		</tr>
	</table>

</div>
