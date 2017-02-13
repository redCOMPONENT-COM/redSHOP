<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<table class="table table-bordered">
	<thead>
	<tr>
		<th width="1">#</th>
		<th>Task</th>
		<th>Status</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->steps as $i => $step): ?>
		<tr>
			<td><?php echo $i + 1 ?></td>
			<td><?php echo $step['text'] ?><br /><?php echo $step['func'] ?></td>
			<td>
				<p class="text-muted">Not run</p>
				<img src="components/com_redshop/assets/images/ajax-loader.gif" class="hidden loader" />
			</td>
			<td><button class="btn btn-info btn-large"><i class="fa fa-cog"></i>&nbsp;Run</button></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
