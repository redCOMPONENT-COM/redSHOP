<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$url = JURI::base();

$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$mid = JRequest::getInt('mid');
$redTemplate = new Redtemplate;

$document = JFactory::getDocument();

$model = $this->getModel('manufacturers');
$manufacturers_template = $model->getManufacturertemplate("manufacturer");

for ($i = 0; $i < count($this->detail); $i++)
{
	if ($this->detail[$i]->manufacturer_id == $mid)
	{
		$link              = JRoute::_('index.php?option=' . $option . '&view=manufacturer_products&mid=' . $this->detail[$i]->manufacturer_id . '&Itemid=' . $Itemid);
		$manufacturer_name = "<a href='" . $link . "'>" . $this->detail[$i]->manufacturer_name . "</a>";

		$manufacturers_data = str_replace("{manufacturer_name}", $manufacturer_name, $manufacturers_template);
		$manufacturers_data = str_replace("{manufacturer_description}", $this->detail[$i]->manufacturer_desc, $manufacturers_data);
		echo "<div style='float:left;'>";

		$manufacturers_data = $redTemplate->parseredSHOPplugin($manufacturers_data);
		echo eval("?>" . $manufacturers_data . "<?php ");
		echo "</div>";
	}
}

?>
<!--Display Pagination start -->
<table cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td valign="top" align="center">
			<?php echo $this->pagination->getPagesLinks(); ?>
			<br/><br/>
		</td>
	</tr>
	<tr>
		<td valign="top" align="center">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</td>
	</tr>
</table>
<!--Display Pagination End -->
