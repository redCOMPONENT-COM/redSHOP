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
jimport('joomla.html.pane');
$pane = @JPane::getInstance('sliders', array('startOffset' => '-1'));

echo $pane->startPane('stat-pane');

?>
<table class="adminlist" cellpadding="0" cellspacing="0">
	<tr>
		<th align="right">
			<button onclick="window.print();"><?php echo JText::_('COM_REDSHOP_PRINT');?></button>
			<button onclick="export_data();"><?php echo JText::_('COM_REDSHOP_EXPORT');?></button>
		</th>
	</tr>
	<tr>
		<th><?php echo JText::_('COM_REDSHOP_STOCKROOM_CONTAINER_NAME'); ?></th>
	</tr>
</table>
<?php
for ($i = 0; $i < count($this->lists); $i++)
{
	$model = $this->getModel('stockroom_detail');
	$product = $model->stock_product($this->lists[$i]->container_id);
	echo $pane->startPanel($this->lists[$i]->container_name, $this->lists[$i]->container_name);
	echo '<table class="adminlist">';
	for ($p = 0; $p < count($product); $p++)
	{
		echo'<tr>
				<td>' . $product[$p]->product_name . '
				</td>
			</tr>';
	}
	echo'</table>';
	echo $pane->endPanel();
}
echo $pane->endPane();
?>
<script language="javascript" type="text/javascript">

	function export_data() {

		document.location.href = "index.php?option=com_redshop&view=stockroom_detail&task=export_data";
	}

</script>
