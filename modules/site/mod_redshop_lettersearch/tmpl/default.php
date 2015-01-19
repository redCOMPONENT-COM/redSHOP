<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_lettersearch
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

?>
<table cellspacing='5' cellpadding='5' border='0' width='100%'>
	<tr>
		<?php
		$option = JRequest::getCmd('option');
		$Itemid = JRequest::getInt('Itemid');
		$letter = JRequest::getString('letter');
		$j = 1;

		for($i = 0; $i < count($getcharacters); $i++)
		{
		$moddiv = (int) ($j % $number_of_columns);
		if ($letter == $getcharacters[$i]->chars)
		{
			$active = 'class="current"';
		}
		else
		{
			$active = '';
		}
		?>
		<td <?php echo $active; ?> ><a
				href='<?php echo JRoute::_('index.php?option=com_redshop&view=category&letter=' . $getcharacters[$i]->chars . '&modulename=' . urlencode($module->title) . '&layout=searchletter&Itemid=' . $Itemid) ?>'><?php echo $getcharacters[$i]->chars; ?></a>
		</td>

		<?php
		if($moddiv == 0)
		{
		?>
	</tr>
	<tr>
	<?php
	}
	$j++;
	}
	?></table>
