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
$model = $this->getModel('barcode');

//print_r($this->logData);

?>
<div>
	<table class="adminlist">
		<thead>
		<tr>
			<th><?php echo JText::_('COM_REDSHOP_ORDER_ID'); ?></th>

			<th><?php echo JText::_('COM_REDSHOP_USER'); ?></th>
			<th><?php echo JText::_('COM_REDSHOP_DATE_AND_TIME'); ?></th>

		</tr>
		</thead>
		<?php
		$k = 0;
		for ($i = 0, $n = count($this->logDetail); $i < $n; $i++)
		{
			$row = & $this->logDetail[$i];
			$row->id = $row->log_id;
			$user_id = $row->user_id;

			$user_name = $model->getUser($user_id);
			// print_r($user_name);
			?>
			<tr class="<?php echo "row$k"; ?>">

				<td align="center"><?php echo $row->order_id; ?></td>

				<td align="center"><?php echo $user_name->name; ?></td>
				<td align="center"><?php echo $row->search_date; ?></td>

			</tr>
			</tr>
			<?php    $k = 1 - $k;
		}    ?>

	</table>
</div>

</div>
