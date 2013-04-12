<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$url = JURI :: root();
$xml_path = $url . "components/com_redshop/assets/document/gbase/product.xml";
?>
<table class="adminlist">
	<tr>
		<th><?php echo JText::_('COM_REDSHOP_GBASE_SITE_XML_PATH'); ?> :
			<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_GBASE_SITE_XML_PATH'), JText::_('COM_REDSHOP_GBASE_SITE_XML_PATH'), 'tooltip.png', '', '', false); ?>
		</th>
		<td><?php echo $xml_path; ?>
		</td>
	</tr>
	<tr>
		<th><?php echo JText::_('COM_REDSHOP_DOWNLOAD_XML'); ?> :</th>
		<td>
			<a href="index.php?tmpl=component&option=com_redshop&view=integration&task=gbasedownload"><?php echo "product.xml"; ?></a>
		</td>
	</tr>
</table>
