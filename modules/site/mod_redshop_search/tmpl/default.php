<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redshop_search
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperHelper');

$templateid = $params->get('templateid');
$defaultSearchType = trim($params->get('defaultSearchType', 'product_name'));
$perpageproduct = $params->get('productperpage');
$search_type = JRequest::getWord('search_type', $defaultSearchType);
$keyword = JRequest::getString('keyword', $standardkeyword);

// Manufacture Select Id
$manufac_data = JRequest::getInt('manufacture_id', '');

$redhelper       = new redhelper;
$Itemid          = $redhelper->getItemid();
$modsearchitemid = trim($params->get('modsearchitemid', ''));

if ($modsearchitemid != "")
{
	$Itemid = $modsearchitemid;
}

?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="get"
      name="redSHOPSEARCH">
    <input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="view" value="search"/>
	<input type="hidden" name="layout" value="default"/>
	<div class="product_search">
		<?php if ($showProductsearchtitle): ?>
			<div class="product_search_title">
				<?php echo JText::_('COM_REDSHOP_PRODUCT_SEARCH'); ?>
			</div>
		<?php endif; ?>

		<?php if ($showSearchTypeField): ?>
			<div class="product_search_type"><?php echo $lists['searchtypedata'];?></div>
		<?php endif; ?>

		<?php if ($showCategory):	?>
			<div class="product_search_catdata">
				<?php echo JText::_('COM_REDSHOP_SELECT_CATEGORIES');?><br>
				<div class="product_search_catdata_category"><?php echo $lists['catdata'];?></div>

				<?php if ($showManufacturer): ?>
					<div class="product_search_catdata_product" id="product_search_catdata_product"
					     style="display: none;"></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ($showManufacturer && !$showCategory): ?>
			<div class="product_search_manufacturedata">
				<?php echo JText::_('COM_REDSHOP_SELECT_MANUFACTURE');?><br>
				<div class="product_search_manufacturedata_manufacture">
					<?php echo $lists['manufacturedata'];?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($showSearchField): ?>
			<?php if ($showKeywordtitle): ?>
				<div class="product_search_input">
					<?php echo JText::_('COM_REDSHOP_KEYWORD'); ?>
				<?php endif; ?>
				<br>
					<input type="text" name="keyword" id="keyword" value="<?php echo $keyword; ?>" onclick="this.value=''"/>
			<?php if ($showKeywordtitle): ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if (!$enableAjaxsearch):?>
			<div class="product_search_button">
				<input type="submit" name="Search" value=<?php echo JText::_('COM_REDSHOP_SEARCH'); ?> id="Search" />
			</div>
		<?php endif; ?>
	</div>
	<input type="hidden" name="templateid" value="<?php echo $templateid; ?>"/>
	<input type="hidden" name="perpageproduct" value="<?php echo $perpageproduct; ?>"/>
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>"/>

	<?php if ($showSearchTypeField): ?>
		<input type="hidden" name="search_type1" id="search_type1" value="<?php echo $search_type; ?>"/>
	<?php else: ?>
		<input type="hidden" name="search_type" id="search_type" value="<?php echo $search_type; ?>"/>
	<?php endif;?>
</form>

<script type="text/javascript">
	var selbox = document.getElementById('category_id') ? document.getElementById('category_id') : "";

	if (selbox) {
		var OnLoadfunc = 'loadProducts(selbox.options[selbox.selectedIndex].value,"<?php echo $manufac_data;?>")';
		window.onload = function () {
			eval(OnLoadfunc);
		};
	}
</script>

<?php
$document = JFactory::getDocument();

if ($enableAjaxsearch)
{
	$document->addScriptDeclaration("
		window.onload = function(){
			var urlArg = new Array();
			var urlArgstring = '';
			var i = 0;
			var Itemid = 0;

			if (document.getElementById('Itemid'))
			{
				Itemid = (document.getElementById('Itemid').value);
			}

			if (document.getElementById('search_type'))
			{
				urlArg[i++] = 'search_type=' + document.getElementById('search_type').value;
			}

			if (document.getElementById('category_id'))
			{
				urlArg[i++] = 'category_id=' + document.getElementById('category_id').value;
			}

			if (document.getElementById('manufacture_id'))
			{
				urlArg[i++] = 'manufacture_id=' + document.getElementById('manufacture_id').value;
			}

			urlArgstring = urlArg.join('&');

			new bsn.AutoSuggest('keyword', {
				script: base_url + 'index.php?tmpl=component&option=com_redshop&view=search&json=1&task=ajaxsearch&' + urlArgstring + '&',
				varname: 'input',
				json: true,
				cache: false,
				shownoresults: true,
				callback: function (obj) {
					location.href = base_url + 'index.php?option=com_redshop&view=product&pid=' + obj.id + '&Itemid=' + Itemid;
				}
			});
		};
	");
}
