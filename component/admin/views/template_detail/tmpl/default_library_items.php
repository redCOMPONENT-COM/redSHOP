<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JHTML::_('behavior.tooltip');
$model = $this->getModel('template_detail');

$title = JText::_('COM_REDSHOP_CATEGORY_TEXTLIBRARY_ITEMS');
echo $this->pane->startPane('stat-pane');
echo $this->pane->startPanel($title, 'events');?>
	<table class="adminlist">
		<tr>
			<td>
				<?php    $tags = $model->availabletexts('category');
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;"><a href="#" onclick="mce_editor_0.document.activeElement.innerHTML += \'{' . $tags[$i]->text_name . '}\'; return false; ">{' . $tags[$i]->text_name . '} -- ' . $tags[$i]->text_desc . '</a></span>';
				}    ?>
			</td>
		</tr>
	</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_NEWSLETTER_TEXTLIBRARY_ITEMS');
echo $this->pane->startPanel($title, 'events');    ?>
	<table class="adminlist">
		<tr>
			<td>
				<?php    $tags = $model->availabletexts('newsletter');
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;"><a href="#" onclick="mce_editor_0.document.activeElement.innerHTML += \'{' . $tags[$i]->text_name . '}\'; return false; ">{' . $tags[$i]->text_name . '} -- ' . $tags[$i]->text_desc . '</a></span>';
				}    ?>
			</td>
		</tr>
	</table>
<?php    echo $this->pane->endPanel();
$title = JText::_('COM_REDSHOP_PRODUCT_TEXTLIBRARY_ITEMS');
echo $this->pane->startPanel($title, 'events');    ?>
	<table class="adminlist">
		<tr>
			<td>
				<?php    $tags = $model->availabletexts('product');
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;"><a href="#" onclick="mce_editor_0.document.activeElement.innerHTML += \'{' . $tags[$i]->text_name . '}\'; return false; ">{' . $tags[$i]->text_name . '} -- ' . $tags[$i]->text_desc . '</a></span>';
				}    ?>
			</td>
		</tr>
	</table>
<?php    echo $this->pane->endPanel();
echo $this->pane->endPane();?>