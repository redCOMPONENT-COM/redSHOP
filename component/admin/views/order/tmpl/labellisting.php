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
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'shipping.php' );
$shippinghelper	 		= new shipping();
$option = JRequest::getVar('option');
$download = JRequest::getVar('download');
if($download)
{
	$oid = JRequest::getInt('oid');
	$baseURL = JURI::root();

	$name = 'label_'.$oid.'.pdf';
	$tmp_name = JPATH_COMPONENT_ADMINISTRATOR.DS.'/assets/lables/'.$name;

	$tmp_type = strtolower(JFile::getExt($name));

	switch( $tmp_type )
	{
	  case "pdf": $ctype="application/pdf"; break;
	  default: $ctype="application/force-download";
	}

	header("Pragma: public");
	header('Expires: 0');
	header("Content-Type: $ctype");
	header('Content-Length: ' . filesize($tmp_name));
	header('Content-Disposition: attachment; filename='.basename($name));

    ob_clean();
    flush();
	readfile($tmp_name);exit;
}
?>
<form action="<?php echo JRoute::_('index.php?option='.$option.'&view=order'); ?>" method="post" name="adminForm" >
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th class="title" width="10%">
				<?php echo JHTML::_('grid.sort', 'ORDER_ID', 'order_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title" width="10%">
				<?php echo JHTML::_('grid.sort', 'ORDER_NUMBER', 'order_number', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ORDER_DATE', 'cdate', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="10%"></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->orders ); $i < $n; $i++)
	{
		$row = &$this->orders[$i];
        $row->id = $row->order_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=order_detail&task=edit&cid[]='. $row->order_id );
		$dlink  = JRoute::_( 'index.php?option='.$option.'&view=order&layout=labellisting&download=1&oid='. $row->order_id );
		$plink  = JURI::base().'components/com_redshop/assets/lables/label_'.$row->order_id.'.pdf';
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center" class="order" width="5%">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
				<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_ORDER' ); ?>"><?php echo $row->order_id; ?></a>
			</td>
			<td align="center"><?php echo $row->order_number; ?></td>
			<td align="center"><?php echo $row->cdate; ?></td>
			<td align="center"><?php
				$details = explode ( "|", $shippinghelper->decryptShipping( str_replace(" ","+",$row->ship_method_id) ));
				if(strstr($details[0],'default_shipping') )
				{
					if(file_exists (JPATH_SITE.'/administrator/components/com_redshop/assets'.DS.'lables'.DS.'label_'.$row->order_id.'.pdf' ))
					{	?>
				<a href="<?php echo $dlink; ?>"><?php echo JText::_('DOWNLOAD');?></a>
				<a href="<?php echo $plink; ?>" target="_blank"><?php echo JTEXT::_("OPEN_AND_PRINT");?></a>
			<?php	}
				}	?></td>
		</tr>
<?php	$k = 1 - $k;
	}?>
	<tfoot>
		<td colspan="9">
			<?php  echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>
</div>


<input type="hidden" name="return" value="order" />
<input type="hidden" name="layout" value="labellisting" />
<input type="hidden" name="view" value="order" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
