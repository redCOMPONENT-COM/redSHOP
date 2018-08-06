<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_filter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * @var  array   $manufacturers     List of manufacturer Id
 * @var  boolean $enableKeyword     Enable keyword
 * @var  boolean $enableClearButton Enable show clear button
 * @var  boolean $restricted        Enable restricted mode
 * @var  float   $rangeMin          Range min
 * @var  float   $rangeMax          Range max
 * @var  float   $currentMin        Current min
 * @var  float   $currentMax        Current min
 */

?>
<div class="<?php echo $moduleClassSfx ?>">
    <form action="<?php echo $action; ?>" method="post" name="adminForm-<?php echo $module->id; ?>"
          id="redproductfinder-form-<?php echo $module->id; ?>" class="form-validate form-vertical">
        <div class="form-horizontal">
			<?php if ($enableKeyword): ?>
                <div class="container-fluid">
                    <div class="form-group">
                        <input type="text" name="redform[keyword]" id="<?php echo $module->id ?>-keyword"
                               value="<?php echo isset($getData['keyword']) ? $getData['keyword'] : $keyword; ?>"
                               placeholder="<?php echo JText::_('MOD_REDSHOP_FILTER_TYPE_A_KEYWORD') ?>"
                               class="form-control"/>
                        <i class="icon-search form-control-feedback"></i>
                    </div>
                </div>
			<?php endif; ?>
			<?php if ($enableCategory == 1 && !empty($categories)): ?>
                <div class="container-fluid">
					<?php $categoriesValues = isset($getData['categories']) ? explode(',', $getData['categories']) : array(); ?>
                    <div id="categories">
                        <h3><?php echo JText::_('MOD_REDSHOP_FILTER_CATEGORY_LABEL'); ?></h3>
                        <ul class='taglist'>
							<?php foreach ($categories as $key => $cat) : ?>
                                <li>
                                    <label>
											<span class='taginput' data-aliases='cat-<?php echo $cat->id; ?>'>
												<input type="checkbox" name="redform[category][]"
                                                       value="<?php echo $cat->id ?>"
                                                       onclick="javascript: redSHOP.Module.Filter.checkClick(this);"
													<?php if (in_array($cat->id, $categoriesValues)): ?>
														<?php echo "checked='checked'"; ?>
													<?php endif; ?>
                                                       onchange="javascript:redSHOP.Module.Filter.submitForm(this)"
                                                />
												<span class='tagname'><?php echo $cat->name; ?></span>
											</span>
                                    </label>
                                </li>
							<?php endforeach; ?>
                        </ul>
                    </div>
                </div>
			<?php endif; ?>
			<?php if ($enableManufacturer == 1 && !empty($manufacturers)): ?>
                <div id='manu' class="container-fluid">
                    <h4 class="title"><?php echo JText::_('MOD_REDSHOP_FILTER_MANUFACTURER_LABEL'); ?></h4>
                    <div class="form-group">
                        <input type="text" name="keyword-manufacturer" id="keyword-manufacturer" class="form-control"
                               placeholder="<?php echo JText::_('MOD_REDSHOP_FILTER_TYPE_A_KEYWORD_MANUFACTURER') ?>"/>
                        <i class="icon-search form-control-feedback"></i>
                    </div>
                    <ul class='taglist' id="manufacture-list">
						<?php if (!empty($manufacturers)) : ?>
							<?php $manufacturersValue = isset($getData['manufacturers']) ? explode(',', $getData['manufacturers']) : array(); ?>
							<?php foreach ($manufacturers as $m => $manu) : ?>
                                <li style="list-style: none">
                                    <label>
										<span class='taginput' data-aliases='manu-<?php echo $manu->id; ?>'>
										<input type="checkbox" name="redform[manufacturer][]"
                                               value="<?php echo $manu->id ?>"
											<?php if (in_array($manu->id, $manufacturersValue)) : ?>
												<?php echo "checked='checked'"; ?>
											<?php endif; ?>
                                            />
										</span>
                                        <span class='tagname'><?php echo $manu->name; ?></span>
                                    </label>
                                </li>
							<?php endforeach; ?>
						<?php endif; ?>
                    </ul>
                </div>
			<?php endif; ?>
			<?php if ($enableCustomField == 1 && !empty($customFields)): ?>
                <div class="container-fluid">
                    <div id="customFields">
                        <h3><?php echo JText::_('MOD_REDSHOP_FILTER_CUSTOM_FIELDS_LABEL'); ?></h3>
                        <ul class='taglist'>
							<?php foreach ($customFields as $key => $fields) : ?>
                                <h4><?php echo $fields['title']; ?></h4>
								<?php foreach ($fields['value'] as $value => $name) : ?>
                                    <li>
                                        <label>
											<span class='taginput' data-aliases='cat-<?php echo $value; ?>'>
												<input type="checkbox"
                                                       name="redform[custom_field][<?php echo $key; ?>][]"
                                                       value="<?php echo urlencode($value); ?>"
													<?php foreach ($getData['custom_field'] as $fieldId => $data) : ?>
														<?php if (in_array($value, explode(',', $data)) && $key == $fieldId) : ?>
															<?php echo "checked='checked'"; ?>
														<?php endif; ?>
													<?php endforeach; ?>
                                                       onchange="javascript:redSHOP.Module.Filter.submitForm(this)"
                                                />
												<span class='tagname'><?php echo $name; ?></span>
											</span>
                                        </label>
                                    </li>
								<?php endforeach; ?>
							<?php endforeach; ?>
                        </ul>
                    </div>
                </div>
			<?php endif; ?>
			<?php if ($enablePrice == 1) : ?>
                <div class="row-fluid">
                    <h4 class="price"><?php echo JText::_('MOD_REDSHOP_FILTER_PRICE_LABEL'); ?></h4>
                    <div id="slider-range"></div>
                    <div id="filter-price">
                        <div id="amount-min">
                            <div><?php echo Redshop::getConfig()->get('CURRENCY_CODE') ?></div>
                            <input type="text" pattern="^\d*(\.\d{2}$)?" class="span12" name="redform[filterprice][min]"
                                   value="<?php echo $rangeMin ?>" min="<?php echo $rangeMin ?>" max="<?php echo $rangeMax; ?>" required/>
                        </div>
                        <div id="amount-max">
                            <div><?php echo Redshop::getConfig()->get('CURRENCY_CODE') ?></div>
                            <input type="text" pattern="^\d*(\.\d{2}$)?" class="span12" name="redform[filterprice][max]"
                                   value="<?php echo $rangeMax ?>" min="<?php echo $rangeMin ?>" max="<?php echo $rangeMax; ?>" required/>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
			<?php if ($enableClearButton): ?>
                <div class="row-fluid">
                    <button id="clear-btn" class="clear-btn btn btn-default clearfix">
						<?php echo JText::_('MOD_REDSHOP_FILTER_CLEAR_LABEL') ?>
                    </button>
                </div>
			<?php endif; ?>
        </div>
        <input type="hidden" name="redform[cid]" value="<?php echo !empty($cid) ? $cid : 0; ?>"/>
        <input type="hidden" name="redform[mid]" value="<?php echo !empty($mid) ? $mid : 0; ?>"/>
        <input type="hidden" name="limitstart"
               value="<?php echo isset($getData['limitstart']) ? $getData['limitstart'] : 0; ?>"/>
        <input type="hidden" name="limit" value="<?php echo isset($getData['limit']) ? $getData['limit'] : $limit; ?>"/>
        <input type="hidden" name="check_list" value="">
        <input type="hidden" name="order_by" value="">
        <input type="hidden" name="redform[template_id]"
               value="<?php echo isset($getData['template_id']) ? $getData['template_id'] : $template; ?>"/>
        <input type="hidden" name="redform[root_category]" value="<?php echo $rootCategory; ?>"/>
        <!--<input type="hidden" name="redform[category_for_sale]" value="<?php /*echo $categoryForSale; */ ?>" />-->
        <input type="hidden" name="option" value="<?php echo $option; ?>">
        <input type="hidden" name="view" value="<?php echo $view; ?>">
        <input type="hidden" name="layout" value="<?php echo $layout; ?>">
        <input type="hidden" name="Itemid" value="<?php echo $itemId; ?>">
        <input type="hidden" name="pids" value="<?php echo implode(',', $pids) ?>"/>
    </form>
</div>
<script type="text/javascript">
    function modalCompare() {
        redSHOP = window.redSHOP || {};
        redSHOP.compareAction(jQuery('[id^="rsProductCompareChk"]'), "getItems");
        jQuery('[id^="rsProductCompareChk"]').click(function (event) {
            redSHOP.compareAction(jQuery(this), "add");
        });
    }

    function restricted(form, pids, params) {
        jQuery('body').on(function(event) {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo JUri::root() ?>index.php?option=com_redshop&task=search.restrictedData",
                data: {pids: pids, params: params, form: form},
                success: function (restrictedData) {
                    jQuery('#redproductfinder-form-<?php echo $module->id;?>').html(restrictedData);
                },
            });
        }); 
    }

    function order(select) {
        var value = jQuery(select).val();
        jQuery('input[name="order_by"]').val(value);
        redSHOP.Module.Filter.submitFormAjax();
    }

    function pagination(start) {
        jQuery('input[name="limitstart"]').val(start);
        redSHOP.Module.Filter.submitFormAjax();
    }

    function loadTemplate(el) {
        id = jQuery(el).val();
        jQuery('input[name="redform[template_id]"]').val(id);
        redSHOP.Module.Filter.submitFormAjax();
    }

    jQuery(document).ready(function () {
        redSHOP.Module.Filter.setup({
            "domId": "<?php echo $module->id ?>",
            "moduleParams": <?php echo $params->toString(); ?>,
            "manufacturers": <?php echo json_encode($manufacturers) ?>,
            "rangeMin": <?php echo $rangeMin; ?>,
            "rangeMax": <?php echo $rangeMax; ?>,
            "currentMin": <?php echo $currentMin ?>,
            "currentMax": <?php echo $currentMax ?>
        });
    });
</script>
