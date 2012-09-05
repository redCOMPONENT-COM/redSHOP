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
require_once(JPATH_ROOT.DS.'components/com_redshop'.DS.'helpers'.DS.'helper.php');

$templateid = $params->get('templateid');
$defaultSearchType		= trim( $params->get( 'defaultSearchType', 'product_name' ) );
$perpageproduct = $params->get('productperpage');
$search_type =  JRequest::getVar( 'search_type', $defaultSearchType );
$keyword = JRequest::getVar('keyword',$standardkeyword);

$manufac_data =  ( JRequest::getVar( 'manufacture_id', '' ) ); // Manufacture Select Id

$redhelper = new redhelper();
$Itemid = $redhelper->getItemid();
$modsearchitemid		= trim( $params->get( 'modsearchitemid', '' ) );
if($modsearchitemid!="")
{
	$Itemid = $modsearchitemid;
}

?>
<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=search&Itemid='.$Itemid); ?>" method="post" name="redSHOPSEARCH">
<div class="product_search">
	<?php
	if($showProductsearchtitle == 'yes'){ ?>
	<div class="product_search_title">
		<?php echo JTEXT::_('PRODUCT_SEARCH'); ?>
	</div>
	<?php } ?>
	<?php
	if ($showSearchTypeField == 'yes'){

	?>
		<div class="product_search_type"><?php echo $lists['searchtypedata'];?></div>
	<?php
	}
		if ($showCategory == 'yes'){
	?>
		<div class="product_search_catdata"><?php echo JText::_('SELECT_CATEGORIES');?><br>
			<div class="product_search_catdata_category"><?php echo $lists['catdata'];?></div>
			<?php
			if ($showManufacturer == 'yes'){
			?>
				<div class="product_search_catdata_product" id="product_search_catdata_product" style="display: none;"></div>
			<?php
			}
			?>
		</div>
	<?php
	}
	if ($showManufacturer == 'yes' && $showCategory == 'no'){
	?>

		<div class="product_search_manufacturedata"><?php echo JText::_('SELECT_MANUFACTURE');?><br>
			<div class="product_search_manufacturedata_manufacture"><?php echo $lists['manufacturedata'];?></div>

		</div>

	<?PHP

	}

	if ($showSearchField == 'yes')
	{

		if($showKeywordtitle == 'yes'){
		?>
		<div class="product_search_input"><?php echo JText::_('KEYWORD');?> <?php }?><br>
			<input type="text" name="keyword" id="keyword" value="<?php echo $keyword;?>" onclick="this.value=''"/>
		<?php if($showKeywordtitle == 'yes'){?>
		</div>
		<?php }?>
	<?php
	}
	?>
	<?php if(!$enableAjaxsearch) {  ?>
	<div class="product_search_button">
		<input type="submit" name="Search" value=<?php echo JTEXT::_('SEARCH');?> id="Search" />
	</div>
	<?php } ?>
</div>
<input type="hidden" name="option" value="com_redshop" />
<input type="hidden" name="view" value="search" />
<input type="hidden" name="layout" value="default" />
<input type="hidden" name="templateid" value="<?php echo $templateid;?>" />
<input type="hidden" name="perpageproduct" value="<?php echo $perpageproduct;?>" />
<?php if ($showSearchTypeField == 'yes'){?>
<input type="hidden" name="search_type1" id="search_type1" value="<?php echo $search_type;?>" />
<? }else{?>
<input type="hidden" name="search_type" id="search_type" value="<?php echo $search_type;?>" />
<?php }?>
<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>" />
</form>
<script type="text/javascript">
var selbox = document.getElementById('category_id') ? document.getElementById('category_id') : "";

if(selbox){
    var OnLoadfunc = 'loadProducts(selbox.options[selbox.selectedIndex].value,"<?php echo $manufac_data;?>")';
	window.onload = function() {
		eval(OnLoadfunc);
		};
}

<?php if($enableAjaxsearch) { ?>
makeUrl();
function makeUrl()
{
	var urlArg = new Array();
	var urlArgstring = '';
	var i = 0;
	var Itemid = 0;

	if(document.getElementById('Itemid'))
	{
		Itemid = (document.getElementById('Itemid').value);
	}
	if(document.getElementById('search_type'))
		urlArg[i++] = "search_type="+document.getElementById('search_type').value;
	if(document.getElementById('category_id'))
		urlArg[i++] = "category_id="+document.getElementById('category_id').value;
	if(document.getElementById('manufacture_id'))
		urlArg[i++] = "manufacture_id="+document.getElementById('manufacture_id').value;
	urlArgstring = urlArg.join("&");
	var varProducts = {
			script:"index2.php?option=com_redshop&view=search&json=1&task=ajaxsearch&"+urlArgstring+"&",
			varname:"input",
			json:true,
			shownoresults:true,
			callback: function (obj) {
				location.href = base_url+"index.php?option=com_redshop&view=product&pid="+obj.id+"&Itemid="+Itemid;
			}
		};
	var as_json = new bsn.AutoSuggest('keyword', varProducts);
}
<?php } ?>
</script>