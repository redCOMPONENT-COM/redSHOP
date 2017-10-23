<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_search
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<form
	action="<?php echo JRoute::_('index.php?option=com_redshop&view=search&Itemid=' . $itemId); ?>"
	method="GET"
    name="redSHOPSEARCH">
	<div class="product_search">
		<?php if ($showProductSearchTitle == 'yes'): ?>
			<div class="product_search_title">
				<?php echo JText::_('COM_REDSHOP_PRODUCT_SEARCH'); ?>
			</div>
		<?php endif; ?>

		<?php if ($showSearchTypeField == 'yes'): ?>
			<div class="product_search_type"><?php echo $lists['searchtypedata'];?></div>
		<?php else: ?>
			<input type="hidden" name="search_type" id="search_type" value="<?php echo $type; ?>"/>
		<?php endif; ?>

		<?php if ($showCategory == 'yes'):	?>
			<div class="product_search_catdata">
				<?php echo JText::_('COM_REDSHOP_SELECT_CATEGORIES');?><br>
				<div class="product_search_catdata_category"><?php echo $lists['catdata'];?></div>
				<?php if ($showManufacturer == 'yes'): ?>
					<div class="product_search_catdata_product" id="product_search_catdata_product"
					     style="display: none;"></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ($showManufacturer == 'yes' && $showCategory == 'no'): ?>
			<div class="product_search_manufacturedata">
				<?php echo JText::_('COM_REDSHOP_SELECT_MANUFACTURE');?><br>
				<div class="product_search_manufacturedata_manufacture">
					<?php echo $lists['manufacturedata'];?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($showCustomfield == 'yes' && !empty($fieldData)):?>
			<div class="mod_redshop_search_custom_field">
				<?php foreach ($fieldData as $key => $field) : ?>
					<label><?php echo $field['title']; ?></label>
					<select name="custom_field[<?php echo $key; ?>]">
						<option value=""><?php echo JText::_('MOD_REDSHOP_SEARCH_SELECT_FIELD');?></option>
						<?php foreach ($field['value'] as $value => $name) :?>
							<option value="<?php echo urldecode($value); ?>"
							<?php if ($fields[$key] == urldecode($value)) : ?>
								selected="selected"
							<?php endif; ?>
							>
								<?php echo $name; ?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ($showSearchField == 'yes'): ?>
			<?php if ($showKeywordTitle == 'yes'): ?>
				<div class="product_search_input">
					<?php echo JText::_('COM_REDSHOP_KEYWORD'); ?>
				<?php endif; ?>
					<br>
					<input type="text" class="span12" name="keyword" id="keyword" value="<?php echo $keyword; ?>" onclick="this.value=''"/>
			<?php if ($showKeywordTitle == 'yes'): ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if (!$enableAjaxsearch):?>
			<div class="product_search_button">
				<input type="submit" name="Search" value=<?php echo JText::_('COM_REDSHOP_SEARCH'); ?> id="Search" />
			</div>
		<?php endif; ?>
	</div>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="view" value="search"/>
	<input type="hidden" name="layout" value="default"/>
	<input type="hidden" name="templateid" value="<?php echo $templateId; ?>"/>
	<input type="hidden" name="perpageproduct" value="<?php echo $productPerpage; ?>"/>
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $itemId; ?>"/>
</form>

<script type="text/javascript">
	var selbox = document.getElementById('category_id') ? document.getElementById('category_id') : "";

	if (selbox) {
		var OnLoadfunc = 'loadProducts(selbox.options[selbox.selectedIndex].value,"<?php echo $mid; ?>")';
		window.onload = function () {
			eval(OnLoadfunc);
		};
	}
</script>
<?php

if ($enableAjaxsearch)
{
	$document = JFactory::getDocument();
	$document->addScriptDeclaration(
		"function makeUrl()
		{
			var urlArg = new Array();
			var urlArgstring = '';
			var i = 0;

			if (document.getElementById('search_type'))
			{
				var searchType = document.getElementById('search_type');

				if ('hidden' == searchType.type)
				{
					urlArg[i++] = 'search_type=' + searchType.value;
				}
				else
				{
					urlArg[i++] = 'search_type=' + searchType.options[searchType.selectedIndex].value;
				}
			}

			if (document.getElementById('category_id'))
			{
				var categoryId = document.getElementById('category_id');
				urlArg[i++] = 'category_id=' + categoryId.options[categoryId.selectedIndex].value;
			}

			if (document.getElementById('manufacture_id'))
			{
				var manufactureId = document.getElementById('manufacture_id');
				urlArg[i++] = 'manufacture_id=' + manufactureId.options[manufactureId.selectedIndex].value;
			}

			urlArgstring = urlArg.join('&');
			new bsn.AutoSuggest('keyword', {
				script: 'index.php?tmpl=component&option=com_redshop&view=search&json=1&task=ajaxsearch&' + urlArgstring + '&',
				varname: 'keyword',
				json: true,
				cache: false,
				shownoresults: true,
				callback: function (obj) {
					location.href = base_url + 'index.php?option=com_redshop&view=product&pid=' + obj.id + '&Itemid=" . $itemId . "';
				}
			});
		}

		window.addEvent('domready', function(){
			makeUrl();
		});"
	);
}
