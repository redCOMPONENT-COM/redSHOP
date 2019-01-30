<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$url = JURI::base();
$app = JFactory::getApplication();

$Itemid      = $app->input->getInt('Itemid');
$mid         = $app->input->getInt('mid');
$redTemplate = Redtemplate::getInstance();

$document = JFactory::getDocument();

$manufacturerTemplate = RedshopHelperTemplate::getTemplate("manufacturer");
$manufacturerTemplate = !empty($manufacturerTemplate) ? $manufacturerTemplate[0]->template_desc : '';

for ($i = 0; $i < count($this->detail); $i++)
{
	if ($this->detail[$i]->manufacturer_id == $mid)
	{
		$link              = JRoute::_('index.php?option=com_redshop&view=manufacturer_products&mid=' . $this->detail[$i]->id . '&Itemid=' . $Itemid);
		$manufacturer_name = "<a href='" . $link . "'>" . $this->detail[$i]->name . "</a>";

		$manufacturers_data = str_replace("{manufacturer_name}", $manufacturer_name, $manufacturerTemplate);
		$manufacturers_data = str_replace("{manufacturer_description}", $this->detail[$i]->description, $manufacturers_data);
		echo "<div style='float:left;'>";

		$manufacturers_data = RedshopHelperTemplate::parseRedshopPlugin($manufacturers_data);
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
