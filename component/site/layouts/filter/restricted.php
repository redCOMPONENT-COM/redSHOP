<?php
/**
 * @package    RedPRODUCTFINDER.Backend
 *
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
extract($displayData);
?>
<div class="form-horizontal">
	<div class="row-fluid">
		<?php if ($params->category == 1 && !empty($categories)): ?>
			<div id="categories">
				<h3><?php echo JText::_('MOD_REDSHOP_FILTER_CATEGORY_LABEL');?></h3>
				<ul class='taglist'>
					<?php foreach ($categories as $key => $cat) :?>
						<li>
								<label>
									<span class='taginput' data-aliases='cat-<?php echo $cat->id;?>'>
										<input type="checkbox" name="redform[category][]" value="<?php echo $cat->id ?>"
										<?php if (in_array($cat->id, $formData['redform']['categories'])) : ?>
											<?php echo "checked='checked'"; ?>
										<?php endif; ?>
										onchange="javascript:submitform(this)"
										/>
										<span class='tagname'><?php echo $cat->name; ?></span>
									</span>
								</label>
							</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	<?php if ($params->manufacturer == 1 && !empty($manufacturers)): ?>
		<div id='manu'>
			<label class="title"><?php echo JText::_("MOD_REDSHOP_FILTER_MANUFACTURER_LABEL"); ?></label>
			<div class="brand-input">
				<input type="text" name="keyword-manufacturer" id="keyword-manufacturer" placeholder="<?php echo JText::_('MOD_REDSHOP_FILTER_TYPE_A_KEYWORD')?>" />
				<i class="icon-search"></i>
			</div>
			<ul class='taglist' id="manufacture-list">
				<?php if (!empty($manufacturers)) : ?>
				<?php foreach ($manufacturers as $m => $manu) : ?>
					<li style="list-style: none">
							<label>
								<span class='taginput' data-aliases='manu-<?php echo $manu->manufacturer_id;?>'>
								<input type="checkbox" name="redform[manufacturer][]" value="<?php echo $manu->manufacturer_id ?>"
								<?php if (in_array($manu->manufacturer_id, $formData['redform']['manufacturers'])) : ?>
									<?php echo "checked='checked'"; ?>
								<?php endif; ?>
								onchange="javascript:submitform(this)"
								>
								</span>
								<span class='tagname'><?php echo $manu->manufacturer_name; ?></span>
							</label>
						</li>
				<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
	<?php endif; ?>
	<div class="row-fluid">
		<?php if ($params->custom_field == 1 && !empty($customFields)): ?>
			<div id="customFields">
				<h3><?php echo JText::_('MOD_REDSHOP_FILTER_CUSTOM_FIELDS_LABEL');?></h3>
				<ul class='taglist'>
						<?php foreach ($customFields as $key => $fields) :?>
							<h4><?php echo $fields['title']; ?></h3>
							<?php foreach ($fields['value'] as $value => $name) :?>
							<li>
								<label>
									<span class='taginput' data-aliases='cat-<?php echo $value;?>'>
										<input type="checkbox" name="redform[custom_field][<?php echo $key;?>][]" value="<?php echo urlencode($value); ?>"
										<?php foreach ($formData['redform']['custom_field'] as $fieldId => $data) :?>
											<?php if (in_array($value, $data) && $key == $fieldId) : ?>
												<?php echo "checked='checked'"; ?>
											<?php endif; ?>
										<?php endforeach; ?>
										onchange="javascript:submitform(this)"
										/>
										<span class='tagname'><?php echo $name; ?></span>
									</span>
								</label>
							</li>
							<?php endforeach; ?>
						<?php endforeach; ?>
					</ul>
			</div>
		<?php endif; ?>
	</div>
	<?php if ($params->price == 1) : ?>
	<div class="row-fluid">
		<div class="price"><?php echo JText::_("MOD_REDSHOP_FILTER_PRICE_LABEL"); ?></div>
		<div id="slider-range"></div>
		<div id="filter-price">
			<div id="amount-min">
				<div><?php echo Redshop::getConfig()->get('CURRENCY_CODE')?></div>
				<input type="text" pattern="^\d*(\.\d{2}$)?" class="span12" name="redform[filterprice][min]" value="<?php echo $rangeMin; ?>" min="0" max="<?php echo $rangeMax; ?>" required/>
			</div>
			<div id="amount-max">
				<div><?php echo Redshop::getConfig()->get('CURRENCY_CODE')?></div>
				<input type="text" pattern="^\d*(\.\d{2}$)?" class="span12" name="redform[filterprice][max]" value="<?php echo $rangeMax; ?>" min="0" max="<?php echo $rangeMax; ?>" required/>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<span id="clear-btn" class="clear-btn" onclick="clearAll();"><?php echo JText::_("MOD_REDSHOP_FILTER_CLEAR_LABEL"); ?></span>
</div>
<input type="hidden" name="redform[cid]" value="<?php echo !empty($formData['redform']['cid']) ? $formData['redform']['cid'] : 0; ?>" />
<input type="hidden" name="redform[mid]" value="<?php echo !empty($formData['redform']['mid']) ? $formData['redform']['mid'] : 0; ?>" />
<input type="hidden" name="limitstart" value="<?php echo $formData['limitstart'] ? $formData['limitstart'] : 0; ?>" />
<input type="hidden" name="limit" value="<?php echo $formData['limit'] ? $formData['limit'] : $limit; ?>" />
<input type="hidden" name="redform[keyword]" value="<?php echo $formData['redform']['keyword'] ? $formData['redform']['keyword'] : $keyword; ?>" />
<input type="hidden" name="check_list" value="<?php echo $formData['check_list']; ?>" >
<input type="hidden" name="order_by" value="<?php echo $formData['order_by']; ?>" >
<input type="hidden" name="redform[template_id]" value="<?php echo $formData['redform']['template_id'] ? $formData['redform']['template_id'] : $template; ?>" />
<input type="hidden" name="redform[root_category]" value="<?php echo $params->root_category; ?>" />
<input type="hidden" name="redform[category_for_sale]" value="<?php echo $formData['redform']['category_for_sale']; ?>" />
<input type="hidden" name="redform[product_on_sale]" value="<?php echo $formData['redform']['product_on_sale']; ?>" >
<input type="hidden" name="option" value="<?php echo $formData['option']; ?>" >
<input type="hidden" name="view" value="<?php echo $formData['view']; ?>" >
<input type="hidden" name="layout" value="<?php echo $formData['layout']; ?>" >
<input type="hidden" name="Itemid" value="<?php echo $formData['Itemid']; ?>" >
