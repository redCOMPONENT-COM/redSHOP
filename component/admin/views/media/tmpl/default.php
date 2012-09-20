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

defined ( '_JEXEC' ) or die ( 'Restricted access' );

JHTMLBehavior::modal();

jimport('joomla.filesystem.file');

$producthelper = new producthelper();
$uri =& JURI::getInstance();
$url= $uri->root();

//--------- For Add Media Detail ---------------
$option = JRequest::getVar('option');
$showbuttons=JRequest::getCmd('showbuttons');
$section_id = JRequest::getCmd('section_id');
$media_section = JRequest::getCmd('media_section');

$model = $this->getModel ( 'media' );
$sectionadata = array();
$sectiona_primary_image = "";
$section_name = "";
$directory = $media_section;
if($showbuttons==1)
{
	switch($media_section)
	{
		case "product";
			$sectionadata =$producthelper->getProductById($section_id);
			$section_name = $sectionadata->product_name;
			$sectiona_primary_image = $sectionadata->product_full_image;
			$directory = $media_section;
			break;
		case "property";
			$sectionadata =$producthelper->getAttibuteProperty($section_id);
			$section_name = $sectionadata[0]->property_name;
			$sectiona_primary_image = $sectionadata[0]->property_main_image;
			$directory = 'property';
			break;
		case "subproperty";
			$sectionadata =$producthelper->getAttibuteSubProperty($section_id);
			$section_name = $sectionadata[0]->subattribute_color_name;
			// Main Image
			$sectiona_primary_image = $sectionadata[0]->subattribute_color_main_image;
			$directory = 'subproperty';
			break;	
	}
}
?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton)
    {
	    form.task.value=pressbutton;
	}
	if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') || (pressbutton=='copy') || (pressbutton=='edit') || (pressbutton=='defaultmedia')|| (pressbutton=='saveorder')||(pressbutton=='orderup') ||(pressbutton=='orderdown'))
	{
		form.view.value="media_detail";
	}
	try {
		form.onsubmit();
		}
	catch(e){}
	form.submit();
}
</script>
<?php
if($showbuttons==1)
{?>
<fieldset>
	<div style="float: right">
		<button type="button" onclick="submitform('add');">
			<?php echo JText::_( 'ADD' ); ?>
		</button>
		<button type="button" onclick="submitform('edit');">
			<?php echo JText::_( 'EDIT' ); ?>
		</button>
		<?php 
		// Main Image
		if($media_section=='product' || $media_section=='property'  || $media_section=='subproperty')
		{?>
		<button type="button" onclick="submitform('defaultmedia');">
			<?php echo JText::_( 'DEFAULT_MEDIA' ); ?>
		</button>
	<?php 	}?>	
		<button type="button" onclick="submitform('remove');">
			<?php echo JText::_( 'DELETE' ); ?>
		</button>
		<button type="button" onclick="submitform('publish');">
			<?php echo JText::_( 'PUBLISH' ); ?>
		</button>
		<button type="button" onclick="submitform('unpublish');">
			<?php echo JText::_( 'UNPUBLISH' ); ?>
		</button>
		<button type="button" onclick="window.parent.location.reload();">
			<?php echo JText::_( 'CANCEL' ); ?>
		</button>
	</div>
	<div class="configuration"><?php echo JText::_( 'ADD_MEDIA' ); ?></div>
</fieldset>
<?php	
	$action = 'index3.php?option=' . $option;
}
else
{
	$action = 'index.php?option=' . $option;
}?>
<form action="<?php echo $action;?>" method="post" name="adminForm">
<div id="editcell">
<?php 
if($showbuttons !=1)
{?>
	<table class="adminlist">		 
		<tr><td valign="top" align="right" class="key"><?php echo JText::_( 'MEDIA_TYPE' ); ?>:<?php echo $this->lists['type']; ?>&nbsp;
			<?php echo JText::_( 'MEDIA_SECTION' ); ?>:<?php echo $this->lists['section']; ?>&nbsp;
			<button onclick="this.form.getElementById('media_type').value='0';this.form.getElementById('media_section').value='0';this.form.submit();"><?php echo JTEXT::_('RESET');?></button>
			</td>
		</tr>
	</table>
<?php }?>

<table class="adminlist">
	<thead>	
		<tr><th width="5%"><?php echo JText::_ ( 'NUM' );?></th>
			<th width="5%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count ( $this->media );?>);" /></th>
			<th width="15%" class="title"><?php		if($showbuttons==1)
					echo JTEXT::_ ('MEDIA_NAME' );
				else
					echo JHTML::_ ( 'grid.sort', 'MEDIA_NAME', 'media_name', $this->lists ['order_Dir'], $this->lists ['order'] );	?></th>
			<th width="10%"><?php if($showbuttons==1)
					echo JTEXT::_ ( 'MEDIA_TYPE');
				else
					echo JHTML::_ ( 'grid.sort', 'MEDIA_TYPE', 'media_type', $this->lists ['order_Dir'], $this->lists ['order'] );	?></th>
 	<?php	if($showbuttons==1){	?>
		 	<th width="10%"><?php	echo JTEXT::_ ( 'ADDITIONAL_DOWNLOAD_FILES');	?></th><?php }?>
		 	<th width="15%"><?php	if($showbuttons==1)
					echo JTEXT::_ ( 'MEDIA_ALTERNATE_TEXT');
				else
					echo JHTML::_ ( 'grid.sort', 'MEDIA_ALTERNATE_TEXT', 'media_alternate_text', $this->lists ['order_Dir'], $this->lists ['order'] );?></th>
			<th width="10%"><?php	if($showbuttons==1)
					echo JTEXT::_ ( 'MEDIA_SECTION');
				else
					echo JHTML::_ ( 'grid.sort', 'MEDIA_SECTION', 'media_section', $this->lists ['order_Dir'], $this->lists ['order'] );	?>
			</th><?php
			// Main Image
				if($showbuttons==1 && ($media_section == 'product' || $media_section=='property'  || $media_section=='subproperty')) {	?>
			<th width="5%" class="title">
					<?php echo JTEXT::_ ('PRIMARY_MEDIA' );?>
			</th>
			<?php }?>
           <!-- ordering-->
            <?php
				if($showbuttons==1) 
				{?>
			<th class="order" width="20%">				
				<?php  echo JHTML::_('grid.order',  $this->media ); ?>
			</th>
			<?php }?>
			<th width="5%" nowrap="nowrap">
				<?php
				if($showbuttons==1)
					echo JTEXT::_ ( 'PUBLISHED');
				else
					echo JHTML::_ ( 'grid.sort', 'PUBLISHED', 'published', $this->lists ['order_Dir'], $this->lists ['order'] );?></th>
			<th width="5%" nowrap="nowrap"><?php
				if($showbuttons==1)
					echo JTEXT::_ ('ID');
				else
					echo JHTML::_ ( 'grid.sort', 'ID', 'media_id', $this->lists ['order_Dir'], $this->lists ['order'] );	?></th>
		</tr>
	</thead>
<?php

	$k = 0;
	for($i = 0, $n = count($this->media); $i < $n; $i ++) 
	{
		$row = &$this->media[$i];
		$row->id = $row->media_id;
		$published = JHTML::_ ( 'grid.published', $row, $i );	?>
        
        <tr class="<?php echo "row$k";?>">
        	<td align="center"><?php echo $this->pagination->getRowOffset ( $i );?></td>
        	<td align="center"><?php echo JHTML::_ ( 'grid.id', $i, $row->id );?></td>
        	<td><?php $filetype = strtolower(JFile::getExt(trim($row->media_name)));
				if($filetype == 'png' || $filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'gif')
				{
					$media_img=$url.'components/'.$option.'/assets/'.$row->media_type.'/'.$row->media_section.'/'.trim($row->media_name);	?>
				<a class="modal" href="<?php echo $media_img;?>" title="<?php echo JText::_ ( 'VIEW_IMAGE' );?>"  rel="{handler: 'image', size: {}}">
				<img src="<?php echo $media_img?>" height="50" width="50"/></a>
		<?php	}
				else
				{
					echo $row->media_name;
				}	?></td>
			<td align="center" class="order"><?php echo $row->media_type;?></td>
		<?php	if($showbuttons==1){	?>	
				<td class="order"><?php
					if($row->media_type == 'download')
					{	
						$additionalfiles = $model->getAdditionalFiles($row->id);	?>
						<a href="index3.php?option=com_redshop&view=media&layout=additionalfile&media_id=<?php echo $row->id;?>&showbuttons=1" class="modal" rel="{handler: 'iframe', size: {x: 1000, y: 400}}" title="<?php echo JText::_('ADDITIONAL_DOWNLOAD_FILES').'&nbsp;('.count($additionalfiles).')';?>" >
						<?php echo JText::_('ADDITIONAL_DOWNLOAD_FILES').'&nbsp;('.count($additionalfiles).')';?></a>
			<?php	}	?></td>
		<?php 	}?>
			<td class="order"><?php	echo $row->media_alternate_text;?></td>
			<td align="center" class="order"><?php echo $row->media_section;?></td>
	<?php
	// Main Image
	 	if($showbuttons==1 && ($media_section=='product' || $media_section=='property'  || $media_section=='subproperty'))
			{
				$checked = (trim($sectiona_primary_image)== trim($row->media_name)) ? "checked" : "";	?>
			<td align="center"><input type="radio" name="primary" id="<?php echo trim($row->media_name);?>" value="<?php echo trim($row->media_name);?>" <?php echo $checked;?> /></td>
	<?php 	}
			if($showbuttons==1)
			{ ?>
    	    <!--ordering-->
        	<td align="center"><?php  echo $this->pagination->orderUpIcon( $i, true, 'orderup', 'Move Up', $row->ordering );  
				echo $this->pagination->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', $row->ordering );?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" class="text_area" style="text-align: center" /></td>
	<?php 	}	?>			
			<td align="center"><?php echo $published;?></td>
			<td align="center"><?php echo $row->media_id;?></td>
		</tr>
<?php	$k = 1 - $k;
	}	?>
	<input type="hidden" name="showbuttons" value="<?php echo $showbuttons;?>" />
	<input type="hidden" name="section_id" value="<?php echo $section_id;?>" />
	<input type="hidden" name="media_section" value="<?php echo $media_section;?>" />
	<input type="hidden" name="section_name" value="<?php echo $section_name;?>" />
<?php
if($showbuttons!=1)
{ ?>	
<tfoot><td colspan="9"><?php echo $this->pagination->getListFooter ();?></td></tfoot>
<?php }?>
</table>
</div>
<input type="hidden" name="view" value="media" /> 
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" /> 
<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order'];?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir'];?>" />
</form>