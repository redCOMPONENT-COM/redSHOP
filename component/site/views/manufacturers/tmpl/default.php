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
JHTMLBehavior::modal();

require_once( JPATH_COMPONENT.DS.'helpers'.DS.'product.php' );
$producthelper = new producthelper();
$redTemplate = new Redtemplate();
$extraField = new extraField();
$config = new Redconfiguration();
$url= JURI::base();
$print = JRequest::getVar('print');
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$redhelper   = new redhelper();
//$document =& JFactory::getDocument();

// Page Title Start
$pagetitle = JText::_('MANUFACTURER');
if($this->pageheadingtag!='')
{
	$pagetitle = $this->pageheadingtag;
}?>
<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<?php
if ($this->params->get('show_page_title',1))
{
	if($this->params->get('page_title') != $pagetitle)
	{
		echo $this->escape($this->params->get('page_title'));
	} else {
		echo $pagetitle;
	}
}?>
</h1>
<?php
// Page title end
$manufacturers_template = $redTemplate->getTemplate("manufacturer");
if(count($manufacturers_template)>0 && $manufacturers_template[0]->template_desc!="")
{
	$template_desc = $manufacturers_template[0]->template_desc;
} else {
	$template_desc = "<div class=\"category_print\">{print}</div>\r\n<div style=\"clear: both;\"></div>\r\n<div id=\"category_header\">\r\n<div class=\"category_order_by\">{order_by} </div>\r\n</div>\r\n<div class=\"manufacturer_box_wrapper\">{manufacturer_loop_start}\r\n<div class=\"manufacturer_box_outside\">\r\n<div class=\"manufacturer_box_inside\">\r\n<div class=\"manufacturer_image\">{manufacturer_image}</div>\r\n<div class=\"manufacturer_title\">\r\n<h3>{manufacturer_name}</h3>\r\n</div>\r\n<div class=\"manufacturer_desc\">{manufacturer_description}</div>\r\n<div class=\"manufacturer_link\"><a href=\"{manufacturer_link}\">{manufacturer_link_lbl}</a></div>\r\n<div class=\"manufacturer_product_link\"><a href=\"{manufacturer_allproductslink}\">{manufacturer_allproductslink_lbl}</a></div>\r\n</div>\r\n</div>\r\n{manufacturer_loop_end}<div class=\"category_product_bottom\" style=\"clear: both;\"></div></div>";
}
// Replace Product Template
if ($print)
{
	$onclick = "onclick='window.print();'";
} else {
	$print_url = $url."index.php?option=com_redshop&view=manufacturers&print=1&tmpl=component&Itemid=".$Itemid;
	$onclick = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}
$print_tag = "<a ".$onclick." title='".JText::_('PRINT_LBL')."'>";
$print_tag .= "<img src='".$url."images/M_images/printButton.png' alt='".JText::_('PRINT_LBL')."' title='".JText::_('PRINT_LBL')."' />";
$print_tag .= "</a>";


$template_start = $template_desc;
$template_middle = "";
$template_end = "";
if(strstr($template_desc, '{manufacturer_loop_start}') && strstr($template_desc, '{manufacturer_loop_end}'))
{
	$template_sdata = explode('{manufacturer_loop_start}',$template_desc);
	$template_start = $template_sdata[0];
	$template_edata = explode('{manufacturer_loop_end}',$template_sdata[1]);
	$template_end = $template_edata[1];
	$template_middle = $template_edata[0];
}
$extraFieldName = $extraField->getSectionFieldNameArray(10,1,1);
$replace_middledata = '';
if($template_middle!="")
{
	for($i=0;$i<$this->params->get('maxmanufacturer');$i++)
	{
		$row = &$this->detail[$i];
		if($row!='')
		{
			$mimg_tag = '{manufacturer_image}';
			$mh_thumb = MANUFACTURER_THUMB_HEIGHT;
			$mw_thumb = MANUFACTURER_THUMB_WIDTH;

			$link 	= JRoute::_( 'index.php?option='.$option.'&view=manufacturers&layout=detail&mid='.$row->manufacturer_id.'&Itemid='.$Itemid);

			$manproducts = JRoute::_( 'index.php?option='.$option.'&view=manufacturers&layout=products&mid='.$row->manufacturer_id.'&Itemid='.$Itemid);
			$manufacturer_name = "<a href='".$manproducts."'><b>".$row->manufacturer_name."</b></a>";

			$middledata = $template_middle;
			$manu_name = $config->maxchar ( $manufacturer_name, MANUFACTURER_TITLE_MAX_CHARS, MANUFACTURER_TITLE_END_SUFFIX );
			$middledata = str_replace("{manufacturer_name}",$manu_name, $middledata);

			// Extra field display
			$middledata = $producthelper->getExtraSectionTag($extraFieldName,$row->manufacturer_id,"10",$middledata);

			if(strstr($middledata,$mimg_tag))
			{
				$thum_image = "";
				$media_image = $producthelper->getAdditionMediaImage($row->manufacturer_id,"manufacturer");
				for($m=0; $m<count($media_image); $m++)
				{
					if ($media_image[$m]->media_name && file_exists(JPATH_BASE."/components/".$option."/assets/images/manufacturer/".$media_image[$m]->media_name))
					{
						$altText = $producthelper->getAltText('manufacturer', $row->manufacturer_id );
						if (! $altText) {
							$altText = $media_image[$m]->media_name;
						}
						$wimg = $redhelper->watermark('manufacturer',$media_image[$m]->media_name,$mw_thumb,$mh_thumb,WATERMARK_MANUFACTURER_THUMB_IMAGE,'0');
						$linkimage = $redhelper->watermark('manufacturer',$media_image[$m]->media_name,'','',WATERMARK_MANUFACTURER_IMAGE,'0');
						if (PRODUCT_IS_LIGHTBOX == 1){
							$thum_image ="<a title='".$altText."' class=\"modal\" href='".$linkimage."'   rel=\"{handler: 'image', size: {}}\"><img alt='".$altText."' title='".$altText."' src='".$wimg."'></a>";
						} else {
							$thum_image ="<a title='".$altText."' href='".$manproducts."'><img alt='".$altText."' title='".$altText."' src='".$wimg."'></a>";
						}
					}
				}
				$middledata = str_replace ( $mimg_tag, $thum_image, $middledata );
			}

			$middledata=str_replace("{manufacturer_description}",$row->manufacturer_desc,$middledata);
			$middledata=str_replace("{manufacturer_link}",$link,$middledata);
			$middledata=str_replace("{manufacturer_allproductslink}",$manproducts,$middledata);
			$middledata=str_replace("{manufacturer_allproductslink_lbl}",JText::_('MANUFACTURER_ALLPRODUCTSLINK_LBL'),$middledata);
			$middledata=str_replace("{manufacturer_link_lbl}",JText::_('MANUFACTURER_LINK_LBL'),$middledata);

			$replace_middledata .= $middledata;
		}
	}
}
$template_desc = $template_start.$replace_middledata.$template_end;

$template_desc = str_replace ( "{print}", $print_tag, $template_desc );

if(strstr($template_desc, '{order_by}'))
{
	$orderby_form = "<form name='orderby_form' action='' method='post'>".JText::_( 'SELECT_ORDER_BY' ).$this->lists['order_select']."</form>";
	$template_desc = str_replace("{order_by}",$orderby_form,$template_desc);
}
if(strstr($template_desc, '{pagination}'))
{
	if($print)
	{
		$template_desc = str_replace("{pagination}","",$template_desc);
	}
	else
	{
		$template_desc = str_replace("{pagination}",$this->pagination->getPagesLinks(),$template_desc);
	}
}

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>".$template_desc."<?php ");
?>