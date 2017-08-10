<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


$uri = JURI::getInstance();
$url = $uri->root();
$comment = JFactory::getApplication()->input->get('filter');

?>


<table cellpadding="2" cellspacing="2" border="0" width="100%">
	<tr>
		<td></td>
	</tr>
	<tr>
		<td>
			<table cellpadding="2" cellspacing="2" border="0" width="200">
				<?php

				for ($i = 0; $i < count($this->sample); $i++)
				{

					$sample_data = $this->sample[$i];
					echo'<tr><th>' . JText::_('COM_REDSHOP_SAMPLE_NAME');
					echo '</th><td>';
					echo $sample_data->sample_name;
					echo '</td></tr>';
					echo'<tr><td></td>';
					if ($sample_data->is_image == 0)
						echo '<td width="100"><div style="width:200px:height:200px;background-color:' . $sample_data->code_image . ';">&nbsp;&nbsp;<br><br><br></div></td>';
					else
						echo '<td><img src="' . $url . $sample_data->code_image . '" border="0" /></td>';

					echo '</tr>';
				}
				?></table>
		</td>
	</tr>

</table>
