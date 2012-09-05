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
defined ( '_JEXEC' ) or die ( 'restricted access' );
$user = JFactory::getUser();
$option = JRequest::getVar('option');
$start = $this->pagination->limitstart;
$end = $this->pagination->limit;

//$querystring = $this->redshopviewer;
//$title = JText::_('TOTAL_VISITORS');
//
//$data = "";
//$rowdata = array(); 
//for ($i=0;$i<count($querystring);$i++)
//{
//	$querystring[$i]->viewdate = ($this->filteroption) ? $querystring[$i]->viewdate : $title;
//	$rowdata[] = "['".$querystring[$i]->viewdate."',".$querystring[$i]->viewer."]";
//}
//$rowdata = implode(",",$rowdata);
//$data .= "[$rowdata]";

?>
<?php /*?>
<script language="javascript" type="text/javascript">

//Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['corechart']});

//Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

//Callback that creates and populates a data table,
//instantiates the pie chart, passes in the data and
//draws it.
function drawChart() 
{
	//Create our data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', '<?php echo JText::_('DATE');?>');
	data.addColumn('number', '<?php echo $title;?>');
	data.addRows(<?php echo $data;?>);
	
	//Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart(document.getElementById('rs_statistics_pie'));
	chart.draw(data, {width: 800, height: 640, is3D: true, title: '<?php echo $title;?>'});
}
</script>
<?php */?>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<div id="editcell">
<table width="100%">
	<tr><td><?php echo JText::_('FILTER').": ".$this->lists['filteroption'];?></td></tr>
	<?php /*<tr><td><?php echo JText::_('STARTDATE');?></td>				
		<td><?php echo JHTML::_('calendar', $this->startdate , 'startdate', 'startdate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'15',  'maxlength'=>'19'));?></td></tr>
	<tr><td><?php echo JText::_('ENDDATE');?></td>
		<td><?php echo JHTML::_('calendar', $this->enddate , 'enddate', 'enddate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'15',  'maxlength'=>'19'));?></td></tr>
	<tr><td colspan="2"><input type="submit" name="filter" value=<?php echo JText::_('SUBMIT');?> /></td></tr><?php */?>
</table>
<!--<fieldset class="adminform">
	<legend><?php echo JText::_( 'STATISTIC' ); ?></legend>
	<div style="float:left;" id="rs_statistics_pie"></div>
	<div id="rs_statistics_column"></div>
</fieldset>
	-->
	<table class="adminlist">
	<thead>
		<tr><th width="60%" align="center"><?php if($this->filteroption) {echo JText::_('DATE');}else{echo JText::_('#');}?></th>
			<th width="40%" align="center"><?php echo JText::_( 'TOTAL_VISITORS' ); ?></th></tr></thead>
<?php   for ($i=$start,$j=0;$i<($start+$end);$i++,$j++)
		{
			$row = &$this->redshopviewer[$i];
			if(!is_object($row))
			{
				break;
			}?>
		<tr><td align="center"><?php if($this->filteroption) {echo $row->viewdate;}else{echo JText::_('#');}?></td>
       		<td align="center"><?php echo $row->viewer;?></td></tr>
	<?php }?>
		<tfoot><td colspan="2"><?php echo $this->pagination->getListFooter(); ?></td></tfoot>
	</table>
	</div>
<input type="hidden" name="view" value="statistic" />
<input type="hidden" name="layout" value="<?php echo $this->layout;?>" />
<input type="hidden" name="boxchecked" value="0" />
</form>
