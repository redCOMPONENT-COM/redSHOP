<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getVar('option', '', 'request', 'string');
$k = 0;
$subscriptions = $this->model->checkUserIsSubscriber();
$today = time();
?>
<div id="editcell">
	<script type="text/javascript">
		function ajax_update_cdate(id)
		{
			var new_date      = document.getElementById('textbox'+id).value;
			var messagelement = document.getElementById('message'+id);
			var today         = new Date().getTime() / 1000;
			var new_date_unix = new Date(new_date).getTime() / 1000
			if (new_date_unix <= today)
			{
				alert("<?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_ERROR_ENDDATE', true);?>");
				return false;
			}
			else
			{
				var req = new Request({
							      method: 'post',
							      url: 'index.php?option=<?php echo $option; ?>&view=product_detail&task=update_cdate',
							      data: { 'id':id, 'convertedcdate':new_date },
							      onRequest: function() { messagelement.innerHTML = '<?php echo JText::_('COM_REDSHOP_SAVING_DATE');  ?>'; },
							      onComplete: function(response) { messagelement.innerHTML = '<?php echo JText::_('COM_REDSHOP_DATE_SAVED');  ?>'; }
							    }).send();
			}
		}     		
    </script> 
<table class="adminlist">
<thead>
	<tr>
		<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
		<th width="5%"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_ID'); ?></th>
		<th width="5%"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_NAME'); ?></th>
		<th width="5%"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD'); ?></th>
		<th width="5%"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_UNIT'); ?></th>
		<th width="5%"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_END_DATE'); ?></th>
		<th width="5%"><?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_STATUS'); ?></th>
	</tr>
</thead>
<tbody>
<?php foreach ($subscriptions as $subscription)
{
?>
		<tr>
			<td align="center"><?php echo $this->pagination->getRowOffset($k);?></td>
			<td align="center"><?php echo $subscription->subscription_id;?></td>
			<td align="center"><?php echo $subscription->product_name;?></td>
            <td align="center"><?php echo $subscription->subscription_period; ?></td>
            <td align="center"><?php echo $subscription->subscription_period_unit; ?></td>
            <td align="center">
            	<?php
					$edate_converted = $this->config->convertDateFormat($subscription->end_date_subscription);

					if ($subscription->end_date_subscription < $today)
					{
						echo '<input id="textbox' . $subscription->id . '" class="inputbox" type="text" maxlength="10" size="7" value="' . $edate_converted . '" title="' . $edate_converted . '" disabled >';
						echo '<input id="button' . $subscription->id . '" class="button" type="button" onclick="javascript:ajax_update_cdate(' . $subscription->id . ')" value="' . JText::_('COM_REDSHOP_SAVE_DATE') . '" disabled>';
					}
					else
					{
						echo '<input id="textbox' . $subscription->id . '" class="inputbox" type="text" maxlength="10" size="7" value="' . $edate_converted . '" title="' . $edate_converted . '" >';
						echo '<input id="button' . $subscription->id . '" class="button" type="button" onclick="javascript:ajax_update_cdate(' . $subscription->id . ')" value="' . JText::_('COM_REDSHOP_SAVE_DATE') . '" >';

					}

					echo '<span id="message' . $subscription->id . '" style="font-size:10px""></span>';
					echo '<br/><br/>';
				?>
            </td>
            <td align="center">
            	<?php 
					if ($subscription->end_date_subscription < $today)
					{
						echo '<span style="color:red">' . JText::_('COM_REDSHOP_SUBSCRIPTION_EXPIRED') . '</span>';
					}
					else
					{
						echo '<span style="color:green">' . JText::_('COM_REDSHOP_SUBSCRIPTION_AVAILABLE') . '</span>';
					}

					$k++;
				?>
            </td>
        </tr>
<?php
}
?>
</tbody>
<tfoot><td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td></tfoot>
</table>
</div>