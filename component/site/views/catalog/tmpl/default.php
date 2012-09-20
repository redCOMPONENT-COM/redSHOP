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
defined ('_JEXEC') or die ('restricted access');
JHTML::_('behavior.tooltip');
 
$url= JURI::base();
$option = JRequest::getVar('option');
if($this->params->get('show_page_title',1)) { 
if ( $this->params->get('page_title')): ?>
		<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		     <?php echo $this->escape($this->params->get('page_title')); ?>
		</h1>
<?php 
	endif; 
?>
<?php } ?>
<form action="" method="post" name="frmcatalog">
<?php 
echo $this->data;
?>
<input type="hidden" name="view" value="catalog" id="view" />
<input type="hidden" name="task" value="catalog_send"/>
<input type="hidden" name="option" id="option" value="<?php echo $option; ?>" />
</form> 