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
$option = JRequest::getVar('option');

$newsid=JRequest::getInt('newsid',0);
$model = $this->getModel('newsletter_detail');

$newsletter_list = $model->getNewsletterList();

$sel = array();
$sel[0]->newsletter_id = 0;
$sel[0]->text = JText::_('SELECT');
$newsletter_list = array_merge($sel,$newsletter_list);
$selnewsletter = JHTML::_('select.genericlist',$newsletter_list,'newsid','class="inputbox" size="1" onchange="document.adminForm.submit();" ','newsletter_id','text',$newsid);

$returnstring = $model->getNewsletterTracker($newsid);

$querystring = $returnstring[0];
$title = $returnstring[1];

$data = "";
$rowdata = array(); 
for ($i=0;$i<count($querystring);$i++)
{	
	$rowdata[] = "['".$querystring[$i]->xdata."',".$querystring[$i]->ydata."]";
}
$rowdata = implode(",",$rowdata);
$data .= "[$rowdata]";

?>
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
	data.addColumn('string', '<?php echo JText::_('NEWSLETTER');?>');
	data.addColumn('number', '<?php echo JText::_('NO_OF_READ_NEWSLETTER');?>');
	data.addRows(<?php echo $data;?>);
	
	//Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart(document.getElementById('newsletter_statistics_pie'));
	chart.draw(data, {width: 800, height: 640, is3D: true, title: '<?php echo $title;?>'});
}
function submitbutton(pressbutton) 
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
}
</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform">
<legend><?php echo JText::_( 'STATISTICS_FILTER' ); ?></legend>
	<table class="admintable">
		<tr><td width="100" align="right" class="key"><?php echo JText::_( 'NEWSLETTER' ); ?>:</td>
			<td><?php echo $selnewsletter;?></td></tr>
	</table>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'NEWSLETTER_STATISTICS' ); ?></legend>
	<div style="float:left;" id="newsletter_statistics_pie"></div>
	<div id="newsletter_statistics_column"></div>
</fieldset>
<input type="hidden" name="view" value="newsletter_detail" />
<input type="hidden" name="layout" value="statistics" />
<input type="hidden" name="option" value="<?php echo $option;?>" />
<input type="hidden" name="task" value="" />
</form>