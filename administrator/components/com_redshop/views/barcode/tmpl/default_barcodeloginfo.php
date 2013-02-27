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
	for ($i=0,$n=count($this->logDetail);$i<$n;$i++)
	{
		$row = &$this->logDetail[$i];
        $row->id = $row->log_id;
        $user_id = $row->user_id;

         $user_name =$model -> getUser($user_id);
        // print_r($user_name);
		?>
	    <tr class="<?php echo "row$k"; ?>">

	      <td align="center"><?php echo $row->order_id; ?></td>

		  <td align="center"><?php echo $user_name->name; ?></td>
		  <td align="center"><?php echo $row->search_date; ?></td>

		</tr>
	</tr>
<?php	$k = 1 - $k;
	}	?>

	</table>
</div>

</div>
