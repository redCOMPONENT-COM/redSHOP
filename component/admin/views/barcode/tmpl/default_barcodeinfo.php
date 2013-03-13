<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JHTMLBehavior::modal();
$option = JRequest::getVar('option');
$url = JUri::base();

$order_id = JRequest::getVar('order_id');

?>
<div>
	<table class="adminlist">
		<tr>
			<td width="15%"><?php echo JText::_('COM_REDSHOP_NUMBER_OF_VIEWS'); ?> :</td>
			<td>
				<a href='index.php?option=<?php echo $option ?>&view=barcode&order_id=<?php echo $order_id ?>&log=log'><?php echo $this->logData->log?></a>
			</td>

		</tr>
	</table>

</div>
