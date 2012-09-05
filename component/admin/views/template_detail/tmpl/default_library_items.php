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
JHTML::_('behavior.tooltip');
$model = $this->getModel('template_detail');

$title = JText::_('COM_REDSHOP_CATEGORY_TEXTLIBRARY_ITEMS' );
			echo $this->pane->startPane( 'stat-pane' );
			echo $this->pane->startPanel( $title, 'events' );?>
		<table class="adminlist">
		<tr><td>
	<?php 	$tags=$model->availabletexts('category');
			for($i=0;$i<count($tags);$i++)
			{
				echo '<span style="margin-left:10px;"><a href="#" onclick="mce_editor_0.document.activeElement.innerHTML += \'{'.$tags[$i]->text_name.'}\'; return false; ">{'.$tags[$i]->text_name.'} -- '.$tags[$i]->text_desc.'</a></span>';
			}	?>
		</td></tr>
		</table>
	<?php  	echo $this->pane->endPanel();
			$title = JText::_('COM_REDSHOP_NEWSLETTER_TEXTLIBRARY_ITEMS' );
			echo $this->pane->startPanel( $title, 'events' );	?>
		<table class="adminlist">
		<tr><td>
	<?php	$tags=$model->availabletexts('newsletter');
			for($i=0;$i<count($tags);$i++)
			{
				echo '<span style="margin-left:10px;"><a href="#" onclick="mce_editor_0.document.activeElement.innerHTML += \'{'.$tags[$i]->text_name.'}\'; return false; ">{'.$tags[$i]->text_name.'} -- '.$tags[$i]->text_desc.'</a></span>';
			}	?>
		</td></tr>
		</table>
	<?php	echo $this->pane->endPanel();
			$title = JText::_('COM_REDSHOP_PRODUCT_TEXTLIBRARY_ITEMS' );
			echo $this->pane->startPanel( $title, 'events' );	?>
		<table class="adminlist">
		<tr><td>
	<?php	$tags=$model->availabletexts('product');
			for($i=0;$i<count($tags);$i++)
			{
				echo '<span style="margin-left:10px;"><a href="#" onclick="mce_editor_0.document.activeElement.innerHTML += \'{'.$tags[$i]->text_name.'}\'; return false; ">{'.$tags[$i]->text_name.'} -- '.$tags[$i]->text_desc.'</a></span>';
			}	?>
		</td></tr>
		</table>
	<?php	echo $this->pane->endPanel();
			echo $this->pane->endPane();?>