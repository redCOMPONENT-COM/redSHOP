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

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
$url = &JURI :: root();
$xml_path = $url."components".DS."com_redshop".DS."assets".DS."document".DS."gbase".DS."product.xml";
?>
<table class="adminlist">
	<tr>
		<th><?php echo JText::_('GBASE_SITE_XML_PATH'); ?> :
		<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_GBASE_SITE_XML_PATH' ), JText::_( 'GBASE_SITE_XML_PATH' ), 'tooltip.png', '', '', false); ?>
		</th>
		<td><?php echo $xml_path; ?>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('DOWNLOAD_XML'); ?> :</th>
		<td><a href="index2.php?option=com_redshop&view=integration&task=gbasedownload"><?php echo "product.xml"; ?></a></td>
	</tr>
</table>