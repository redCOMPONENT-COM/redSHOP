<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_filter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

?>
<div class="<?php echo $moduleClassSfx; ?>">
	<form action="<?php echo $action; ?>" method="post" name="adminForm-<?php echo $module->id;?>" id="redproductfinder-form-<?php echo $module->id;?>" class="form-validate">
	<div class="form-horizontal">
		<div class="row-fluid">
			<?php if ($enableCategory == 1 && !empty($categories)): ?>
				<div id="categories">
					<h3><?php echo JText::_('MOD_REDSHOP_FILTER_CATEGORY_LABEL');?></h3>
					<ul class='taglist'>
						<?php foreach ($categories as $key => $cat) :?>
							<li>
								<?php if (($view == 'search') || (!empty($cid) && in_array($cid, $childCat)) || !empty($mid)) : ?>
								<label>
									<span class='taginput' data-aliases='cat-<?php echo $cat->category_id;?>'>
										<input type="checkbox" name="redform[category][]" value="<?php echo $cat->category_id ?>" onclick="javascript: checkclick(this);" />
										<span class='tagname'><?php echo $cat->category_name; ?></span>
									</span>
								</label>
								<?php endif; ?>
								<?php if (!empty($cat->child)): ?>
									<ul class='taglist'>
										<?php foreach ($cat->child as $k => $child) :?>
											<li>
												<label>
													<span class='taginput' data-aliases='child-cat-<?php echo $child->category_id;?>'>
														<!-- <i class="icon icon-check-empty"></i> -->
														<input type="checkbox" name="redform[category][]" value="<?php echo $child->category_id ?>" onclick="javascript: checkclick(this);"" />
														<span class='tagname'><?php echo $child->category_name; ?></span>
													</span>
												</label>
												<?php if (!empty($child->sub)): ?>
													<ul class='taglist'>
														<?php foreach ($child->sub as $i => $sub) :?>
															<li>
																<label>
																	<span class='taginput' data-aliases='sub-cat-<?php echo $sub->category_id;?>'>
																		<input parent="<?php echo $child->category_id ?>" type="checkbox" name="redform[category][]" value="<?php echo $sub->category_id ?>" onclick="javascript: checkclick(this);" />
																		<span class='tagname'><?php echo $sub->category_name; ?></span>
																	</span>
																</label>
															</li>
														<?php endforeach; ?>
													</ul>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
		<?php if ($enableManufacturer == 1 && count($manufacturers) > 0): ?>
			<div id='manu'>
				<label class="title"><?php echo JText::_("MOD_REDSHOP_FILTER_MANUFACTURER_LABEL"); ?></label>
				<div class="brand-input">
					<input type="text" name="keyword-manufacturer" id="keyword-manufacturer" placeholder="<?php echo JText::_('TYPE_A_KEYWORD')?>" />
					<i class="icon-search"></i>
				</div>
				<ul class='taglist' id="manufacture-list">
					<?php if (!empty($manufacturers)) : ?>
					<?php foreach ($manufacturers as $m => $manu) : ?>
						<li style="list-style: none">
							<label>
								<span class='taginput' data-aliases='manu-<?php echo $manu->manufacturer_id;?>'>
								<input type="checkbox" name="redform[manufacturer][]" value="<?php echo $manu->manufacturer_id ?>">
								</span>
								<span class='tagname'><?php echo $manu->manufacturer_name; ?></span>
							</label>
						</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
		<?php if ($enablePrice == 1) : ?>
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
	</div>
	<input type="hidden" name="redform[cid]" value="<?php echo !empty($cid) ? $cid : 0; ?>" />
	<input type="hidden" name="redform[mid]" value="<?php echo !empty($mid) ? $mid : 0; ?>" />
	<input type="hidden" name="limitstart" value="0" />
	<input type="hidden" name="limit" value="27" />
	<input type="hidden" name="redform[keyword]" value="<?php echo $keyword;?>" />
	<input type="hidden" name="check_list" value="" >
	<input type="hidden" name="order_by" value="" >
	<input type="hidden" name="redform[product_on_sale]" value="<?php echo $productOnSale; ?>" >
	<input type="hidden" name="redform[template_id]" value="<?php echo $template; ?>" />
	<input type="hidden" name="redform[root_category]" value="<?php echo $rootCategory; ?>" />
	<input type="hidden" name="redform[category_for_sale]" value="<?php echo $categoryForSale; ?>" />
</form>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo JUri::root() . 'modules/mod_redshop_filter/lib/css/jqui.css'; ?>">
<script type="text/javascript" src="<?php echo JUri::root() . 'modules/mod_redshop_filter/lib/js/jquery-ui.min.js'; ?>"></script>
<script type="text/javascript">
	function range_slide (min_range, max_range , cur_min , cur_max, callback) {
		jQuery.ui.slider.prototype.widgetEventPrefix = 'slider';
		jQuery( "#slider-range" ).slider({
			range: true,
			min: min_range,
			max: max_range,
			step: 100000,
			values: [ cur_min , cur_max ],
			slide: function( event, ui ) {
			jQuery('[name="redform[filterprice][min]"]').attr('value', ui.values[ 0 ]);
			jQuery('[name="redform[filterprice][max]"]').attr('value', ui.values[ 1 ]);
			},change: function(event, ui){
				if (callback && typeof(callback) === "function") {
					jQuery('input[name="limitstart"]').val(0);
					callback();
				}
			}
		});
	}

	function modalCompare()
	{
		redSHOP = window.redSHOP || {};
		redSHOP.compareAction(jQuery('[id^="rsProductCompareChk"]'), "getItems");
		jQuery('[id^="rsProductCompareChk"]').click(function(event) {
		    redSHOP.compareAction(jQuery(this), "add");
		});
	}

	function checkclick(obj) {
		if (jQuery(obj).prop("checked") == true) {
			jQuery(obj).prev('.icon').addClass('active');
		}else{
			jQuery(obj).prev('.icon').removeClass('active');
		}
	}

	function submitform (argument) {
		jQuery('#redproductfinder-form-<?php echo $module->id;?> input[type="checkbox"], select').change(function(event) {
			jQuery('input[name="limitstart"]').val(0);
			submitpriceform();
		});
	}

	function submitpriceform (argument) {
		jQuery.ajax({
		 	type: "POST",
		 	url: "<?php echo JUri::root() ?>index.php?option=com_redshop&task=search.findProducts",
		 	data: jQuery('#redproductfinder-form-<?php echo $module->id;?>').serialize(),
		 	beforeSend: function() {
				jQuery('#wait').css('display', 'block');
				jQuery('.category_header').css('display', 'none');
			},
		 	success: function(data) {
		 		jQuery('.category_product_list #productlist').empty();
		 		jQuery('#main-content .category_main_toolbar').first().remove();
		 		jQuery('.category_product_list #productlist').html(data);
		 		jQuery('.category_wrapper.parent .category_main_toolbar, .category_wrapper.parent .category_product_list').css('display', 'block');
                jQuery('select#orderBy').select2();
                jQuery('.category-list').hide();
                modalCompare();

		 	},
		 	complete: function() {
			    jQuery('#wait').css('display', 'none');
			    jQuery('.cate_redshop_products_wrapper').responsiveEqualHeightGrid();
			    jQuery('.category_wrapper .category_main_toolbar').insertBefore('#sidebar1');
			}
		 });
	}

	function order(select){
		var value = jQuery(select).val();
		jQuery('input[name="order_by"]').val(value);
		submitpriceform();
	}

	function pagination(start){
		jQuery('input[name="limitstart"]').val(start);
		submitpriceform();
	}

	jQuery(document).ready(function(){
		var check = [];
		function checkList(){
			jQuery('#redproductfinder-form-<?php echo $module->id;?> #manu #manufacture-list input').on('change', function(){
				var id = jQuery(this).val();
				check.push(id);
				jQuery('input[name="check_list"]').val(JSON.stringify(check));
			});
		}

		checkList();

		jQuery('input[name="keyword-manufacturer"]').on('keyup', function(){
			var json    = '<?php echo json_encode($manufacturers); ?>';
			var arr     = jQuery.parseJSON(json);
			var keyword = jQuery(this).val();
			var new_arr = [];
			var check = jQuery('input[name="check_list"]').val();
			var check_list = jQuery.parseJSON(check);
			jQuery.each(arr, function(i, value){
				if (value.manufacturer_name.toLowerCase().indexOf(keyword.toLowerCase()) > -1){
					new_arr.push(value);
				}
			});

			var html = '';

			jQuery.each(new_arr, function(key, data){
				var check = Object.keys(data).length;
				if (check > 0){
					if(jQuery.inArray(data.manufacturer_id, check_list) != -1) {
					    var is_check = 'checked="checked"';
					} else {
					    var is_check = '';
					}
					html += '<li style="list-style: none"><label>';
					html += '<span class="taginput" data-aliases="'+data.manufacturer_id+'">';
					html += '<input type="checkbox" '+is_check+' value="'+data.manufacturer_id+'" name="redform[manufacturer][]" />';
					html += '</span>'
					html += '<span class="tagname">'+data.manufacturer_name+'</span>';
					html += '</label></li>';
				}
			});

			jQuery('#redproductfinder-form-<?php echo $module->id;?> #manu #manufacture-list').html('');
			jQuery('#redproductfinder-form-<?php echo $module->id;?> #manu #manufacture-list').append(html);
			checkList();
		});

		submitform();

		jQuery('#redproductfinder-form-<?php echo $module->id;?> [type="checkbox"]').each(function(){
			checkclick(jQuery(this))
		});

		jQuery('#redproductfinder-form-<?php echo $module->id;?>').html(function(){
			jQuery('span.label_alias').click(function(event) {
				if (jQuery(this).hasClass('active')) {
					jQuery(this).removeClass('active').next('ul.collapse').removeClass('in');
				}else{
					var ultab = redfinderform.find('ul.collapse.in');
					ultab.removeClass('in').prev('span').removeClass('active');

					jQuery(this).addClass('active').next('ul.collapse').addClass('in');
				}
			});
			range_slide(<?php echo $rangeMin;?>, <?php echo $rangeMax;?>, <?php echo $rangeMin;?>, <?php echo $rangeMax;?>, submitpriceform );
		});
	});
</script>