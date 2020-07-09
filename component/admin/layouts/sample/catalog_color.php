<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * Layout variables
 * ======================================
 *
 * @var  object $lists       List catalog color
 * @var  array  $displayData Layout data.
 */
extract($displayData);

$uri = JURI::getInstance();
$url = $uri->root();

?>
<table cellpadding="0" cellspacing="5" border="0">
	<tr>
		<th><?php
            echo JText::_('COM_REDSHOP_COLOUR_CODE'); ?> : <input class="inputbox" type="text"
		                                                          name="color_code_1"
		                                                          id="color_code_1"/>
		</th>
		<td>
			<div id="colorSelector">
				<div style="background-color: #0000ff"></div>
			</div>
		</td>
		<td><?php
            echo JText::_('COM_REDSHOP_OR'); ?></td>
		<td><?php
            echo JText::_('COM_REDSHOP_COLOUR_IMAGE'); ?> :
		</td>
		<td><?php
            $ilink = JRoute::_(
                'index.php?option=com_media&view=images&tmpl=component&e_name=text'
            ); ?>
			<div class="button2-left">
				<div class="image"><a class="joom-box" title="Image" href="<?php
                    echo $ilink; ?>"
				                      rel="{handler: 'iframe', size: {x: 570, y: 400}}">Image</a></div>
			</div>
		</td>
		<th>
			<input type="button" name="addvalue" id="addvalue" class="button"
			       Value="<?php
                   echo JText::_('COM_REDSHOP_ADD_COLOR'); ?>"
			       onclick="addNewcolor('extra_table');"/>
			<input type="hidden" name="catalog_image" id="catalog_image"/>
		</th>
		<th>
			<div id="image_dis">
				<img src="" id="image_display" style="display:none;" border="0"/>
			</div>
		</th>
	</tr>

</table>
<table cellpadding="0" cellspacing="5" border="0" id="extra_table">
	<tr>
		<th><?php
            echo JText::_('COM_REDSHOP_COLOUR_IMAGE'); ?></th>
		<th><?php
            echo JText::_('COM_REDSHOP_DELETE'); ?></th>
	</tr>
    <?php
    for ($j = 0; $j < count($lists); $j++) {
        echo '<tr>';
        if ($lists[$j]->is_image == 0) {
            echo '<td><input type="hidden" name="is_image[]" value="0" id="is_image[]"><div style=" width:100px:height:100px;background-color:' . $lists[$j]->code_image . ';">&nbsp;</div></td>';
        } else {
            echo '<td><input type="hidden" name="is_image[]" value="1" id="is_image[]"><img src="' . $url . $lists[$j]->code_image . '" border="0" /></td>';
        }

        echo '<td><input type="hidden" name="code_image[]" value="' . $lists[$j]->code_image . '" id="code_image[]"><input value="Delete" onclick="deletecolor(this)" class="button" type="button" /><input type="hidden" name="colour_id[]" id="colour_id[]"></td>';
        echo '</tr>';
    }
    ?>
</table>

<input type="hidden" value="0" name="total_extra" id="total_extra">

<script>
	function jInsertEditorText(text, editor) {

		if (text) {
			var path_url = "<?php echo $url;?>";
			var fpath = text.split('<img src="');
			var path = fpath[1].split('"');
			document.getElementById("catalog_image").value = path[0];
			main_path = path_url + path[0];
			document.getElementById("image_display").style.display = "block";
			document.getElementById("image_display").src = main_path;
		} else {
			document.getElementById("catalog_image").value = "";
			document.getElementById("image_display").src = "";
		}

	}
</script>