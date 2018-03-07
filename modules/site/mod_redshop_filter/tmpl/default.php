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
                    <?php $categoriesValues = isset($getData['categories']) ? explode(',', $getData['categories']) : array(); ?>
					<div id="categories">
						<h3><?php echo JText::_('MOD_REDSHOP_FILTER_CATEGORY_LABEL');?></h3>
						<ul class='taglist'>
							<?php foreach ($categories as $key => $cat) :?>
								<li>
										<label>
											<span class='taginput' data-aliases='cat-<?php echo $cat->id;?>'>
												<input type="checkbox" name="redform[category][]" value="<?php echo $cat->id ?>" onclick="javascript: checkclick(this);"
												<?php if (in_array($cat->id, $categoriesValues)): ?>
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
			<?php if ($enableManufacturer == 1 && !empty($manufacturers)): ?>
				<div id='manu'>
					<label class="title"><?php echo JText::_("MOD_REDSHOP_FILTER_MANUFACTURER_LABEL"); ?></label>
					<div class="brand-input">
						<input type="text" name="keyword-manufacturer" id="keyword-manufacturer" placeholder="<?php echo JText::_('MOD_REDSHOP_FILTER_TYPE_A_KEYWORD')?>" />
						<i class="icon-search"></i>
					</div>
					<ul class='taglist' id="manufacture-list">
						<?php if (!empty($manufacturers)) : ?>
                            <?php $manufacturersValue = isset($getData['manufacturers']) ? explode(',', $getData['manufacturers']) : array(); ?>
						<?php foreach ($manufacturers as $m => $manu) : ?>
							<li style="list-style: none">
									<label>
										<span class='taginput' data-aliases='manu-<?php echo $manu->id;?>'>
										<input type="checkbox" name="redform[manufacturer][]" value="<?php echo $manu->id ?>"
										<?php if (in_array($manu->id, $manufacturersValue)) : ?>
											<?php echo "checked='checked'"; ?>
										<?php endif; ?>
										onchange="javascript:submitform(this)"
										>
										</span>
										<span class='tagname'><?php echo $manu->name; ?></span>
									</label>
								</li>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>
			<div class="row-fluid">
				<?php if ($enableCustomField == 1 && !empty($customFields)): ?>
					<div id="customFields">
						<h3><?php echo JText::_('MOD_REDSHOP_FILTER_CUSTOM_FIELDS_LABEL');?></h3>
						<ul class='taglist'>
								<?php foreach ($customFields as $key => $fields) :?>
									<h4><?php echo $fields['title']; ?></h4>
									<?php foreach ($fields['value'] as $value => $name) :?>
									<li>
										<label>
											<span class='taginput' data-aliases='cat-<?php echo $value;?>'>
												<input type="checkbox" name="redform[custom_field][<?php echo $key;?>][]" value="<?php echo urlencode($value); ?>"
												<?php foreach ($getData['custom_field'] as $fieldId => $data) :?>
													<?php if (in_array($value, explode(',', $data)) && $key == $fieldId) : ?>
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
			<span id="clear-btn" class="clear-btn" onclick="clearAll();"><?php echo JText::_("MOD_REDSHOP_FILTER_CLEAR_LABEL"); ?></span>
		</div>
		<input type="hidden" name="redform[cid]" value="<?php echo !empty($cid) ? $cid : 0; ?>" />
		<input type="hidden" name="redform[mid]" value="<?php echo !empty($mid) ? $mid : 0; ?>" />
		<input type="hidden" name="limitstart" value="<?php echo isset($getData['limitstart']) ? $getData['limitstart'] : 0; ?>" />
		<input type="hidden" name="limit" value="<?php echo isset($getData['limit']) ? $getData['limit'] : $limit; ?>" />
		<input type="hidden" name="redform[keyword]" value="<?php echo isset($getData['keyword']) ? $getData['keyword'] : $keyword; ?>" />
		<input type="hidden" name="check_list" value="" >
		<input type="hidden" name="order_by" value="" >
		<input type="hidden" name="redform[template_id]" value="<?php echo isset($getData['template_id']) ? $getData['template_id'] : $template; ?>" />
		<input type="hidden" name="redform[root_category]" value="<?php echo $rootCategory; ?>" />
		<!--<input type="hidden" name="redform[category_for_sale]" value="<?php /*echo $categoryForSale; */?>" />-->
		<input type="hidden" name="option" value="<?php echo $option; ?>" >
		<input type="hidden" name="view" value="<?php echo $view; ?>" >
		<input type="hidden" name="layout" value="<?php echo $layout; ?>" >
		<input type="hidden" name="Itemid" value="<?php echo $itemId; ?>" >
        <input type="hidden" name="pids" value="<?php echo implode(',', $pids) ?>" />
	</form>
</div>
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
		jQuery('input[name="limitstart"]').val(0);
		submitpriceform();
	}

	function submitpriceform (argument) {
		jQuery.ajax({
		 	type: "POST",
		 	url: "<?php echo JUri::root() ?>index.php?option=com_redshop&task=search.findProducts",
		 	data: jQuery('#redproductfinder-form-<?php echo $module->id;?>').serialize(),
		 	beforeSend: function() {
				jQuery('#wait').css('display', 'block');
			},
		 	success: function(data) {
		 		jQuery('#main #redshopcomponent').html(data);
				jQuery('select#orderBy').select2();

				url = jQuery(jQuery.parseHTML(data)).find("#new-url").text();
				window.history.pushState("", "", url);
		 	},
		 	complete: function() {
			    jQuery('#wait').css('display', 'none');
			    <?php if ($restricted) : ?>
                    var pids = jQuery('input[name="pids"]').val();
			    	restricted(jQuery('#redproductfinder-form-<?php echo $module->id;?>').serialize(), pids, '<?php echo json_encode($params); ?>');
			    <?php endif; ?>
			}
		 });
	}

	function restricted(form, pids, params) {
		jQuery.ajax({
		 	type: "POST",
		 	url: "<?php echo JUri::root() ?>index.php?option=com_redshop&task=search.restrictedData",
		 	data: {pids: pids, params: params, form: form},
		 	success: function(restrictedData) {
		 		jQuery('#redproductfinder-form-<?php echo $module->id;?>').html(restrictedData);
		 	},
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

	function clearAll(){
		jQuery('#redproductfinder-form-<?php echo $module->id;?> input[type="checkbox"]').prop('checked' , false);
		jQuery('#redproductfinder-form-<?php echo $module->id;?> input[type="checkbox"]').each(function(){
			checkclick(jQuery(this))
		});
		jQuery('input[name="redform[filterprice][min]"]').val('<?php echo $rangeMin;?>');
		jQuery('input[name="redform[filterprice][max]"]').val('<?php echo $rangeMax;?>');
		range_slide(<?php echo $rangeMin;?>, <?php echo $rangeMax;?>, <?php echo $rangeMin;?>, <?php echo $rangeMax;?>, submitpriceform );
		submitpriceform(null);
	}

	function loadTemplate(el){
		id = jQuery(el).val();
		jQuery('input[name="redform[template_id]"]').val(id);
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
				if (value.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1){
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
					html += '<span class="taginput" data-aliases="'+data.id+'">';
					html += '<input type="checkbox" '+is_check+' value="'+data.id+'" name="redform[manufacturer][]" />';
					html += '</span>'
					html += '<span class="tagname">'+data.name+'</span>';
					html += '</label></li>';
				}
			});

			jQuery('#redproductfinder-form-<?php echo $module->id;?> #manu #manufacture-list').html('');
			jQuery('#redproductfinder-form-<?php echo $module->id;?> #manu #manufacture-list').append(html);
			checkList();
		});

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
