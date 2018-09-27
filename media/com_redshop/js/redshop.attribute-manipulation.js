(function ($) {
	$(document).ready(function () {
		function InitSelect(select) {
			try {
				SqueezeBox.initialize();
				SqueezeBox.assign($$('a.modal-thumb'), {
					parse: 'rel'
				});
				if (select != '') {
					$(select).select2({
						width:"auto", 
						dropdownAutoWidth:"auto",
						minimumResultsForSearch: -1
					});
				}
			}
			catch (err) {
			}
		}

		function renderProperty(propPref, gh, sh, or) {
			return '<input type="hidden" value="0" name="' + propPref + '[count_subprop]" class="count_subprop" />'
			+ '<input type="hidden" value="' + sh + '" name="' + propPref + '[key_prop]" class="key_prop" />'

			+ '<a class="btn btn-success add_property btn-small">+ ' + Joomla.JText._('COM_REDSHOP_ADD_SUB_ATTRIBUTE') + '</a>'
			+ '<a href="#" class="showhidearrow">' + Joomla.JText._('COM_REDSHOP_SUB_ATTRIBUTE') + ': <span class="propertyName"></span><img class="arrowimg" src="media/com_redshop/images/arrow.png" alt=""/></a>'

			+ '<div class="attr_tbody form-inline divInspectFromHideShow">'

			+ '<div class="row">'
			+ '<div class="col-sm-4">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_SUB_ATTRIBUTE') + '</label><input type="text" class="form-control propertyInput" name="' + propPref + '[name]" value=""><input type="hidden"  name="attribute[' + gh + '][property][' + sh + '][mainImage]" value="" id="propmainImage' + gh + sh + '"><input type="hidden" name="attribute[' + gh + '][property][' + sh + '][property_image]" id="propertyImageName' + gh + sh + '" /></div>'
			+ '</div>'

			+ '<div class="col-sm-4">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_PRICE') + '</label>'
			+ '<div class="priceInput">'
			+ '<select id="attribute_' + gh + '_property_' + sh + '_oprand" name="' + propPref + '[oprand]" class="input-xmini"><option value="=">=</option><option value="+">+</option><option value="-">-</option><option value="*">*</option><option value="/">/</option></select>'
			+ '<input type="text" class="form-control" value="" name="' + propPref + '[price]"></div>'
			+ '</div>'
			+ '</div>'
			// row
			+ '</div>'


			+ '<div class="row">'
			+ '<div class="col-sm-4">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_PROPERTY_NUMBER') + '</label><input type="text" name="' + propPref + '[number]" value="" class="vpnrequired "></div>'
			+ '</div>'

			+ '<div class="col-sm-4">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD') + '</label><input type="text" class="" name="' + propPref + '[extra_field]" value="" /></div>'
			+ '</div>'

			+ '<div class="col-sm-4">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_ORDERING') + '</label><input type="number" class="form-control" name="' + propPref + '[order]" value="' + or + '"></div>'
			+ '</div>'
			// row
			+ '</div>'

			+ '<div class="row">'
			+ '<div class="col-sm-4">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_DEFAULT_SELECTED') + '</label><input type="checkbox" value="1" name="' + propPref + '[default_sel]" /></div>'
			+ '</div>'

			// row
			+ '</div>'

			+ '<div class="row">'
			+ '<div class="col-sm-4">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_PUBLISHED') + '</label><input type="checkbox" name="' + propPref + '[published]" checked="checked" value="1" /></div>'
			+ '</div>'
			+ '<div class="col-sm-8">'
			+ '<input value="' + Joomla.JText._('COM_REDSHOP_DELETE') + '" class="btn btn-danger delete_property btn-small" type="button" />'
			+ '<a class="btn btn-success add_subproperty btn-small" href="#">+ ' + Joomla.JText._('COM_REDSHOP_NEW_SUB_PROPERTY') + '</a>'
			+ '</div>'

			// row
			+ '</div>'

			+ '<div class="attribute_parameter_tr divFromHideShow">'

			+ '<div class="row showsubproperty">'

			+ '<div class="col-sm-6">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_TITLE') + '</label><input class="form-control" type="text" name="' + propPref + '[subproperty][title]" value=""></div>'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_SUBATTRIBUTE_REQUIRED') + '</label><input type="checkbox" value="1" name="' + propPref + '[req_sub_att]" /></div>'
			+ '</div>'

			+ '<div class="col-sm-6">'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE') + '</label><select id="attribute_' + gh + '_property_' + sh + '_setdisplay_type" name="' + propPref + '[setdisplay_type]" class="input-medium"><option value="dropdown" selected="selected" >' + Joomla.JText._('COM_REDSHOP_DROPDOWN_LIST') + '</option><option value="radio" >' + Joomla.JText._('COM_REDSHOP_RADIOBOX') + '</option></select></div>'
			+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED') + '</label><input type="checkbox" value="1" name="attribute[+gh+][property][' + sh + '][multi_sub_att]" /></div>'
			+ '</div>'

			// showsubproperty
			+ '</div>'

			+ '<div class="sub_attribute_table">'

			
			// sub_attribute_table
			+ '</div>'

			// attribute_parameter_tr
			+ '</div>'
	
			// divInspectFromHideShow
			+ '</div>';
		}

		$('.add_attribute').on('click', function (e) {
			e.preventDefault();
			var $countAttr = $('.count_attr');
			var gh = parseInt($countAttr.val());
			$countAttr.val(gh + 1);
			var attrPref = 'attribute[' + gh + ']';
			$('.mainTableAttributes').append(
				'<a href="#" class="showhidearrow">' + Joomla.JText._('COM_REDSHOP_ATTRIBUTE_NAME') + ': <span class="attributeName"></span><img class="arrowimg" src="media/com_redshop/images/arrow.png" alt=""/></a>'
				+ '<div class="attribute_table divInspectFromHideShow">'
				+ '<input type="hidden" name="' + attrPref + '[count_prop]" class="count_prop" value="1" />'
				+ '<input type="hidden" value="' + gh + '" name="' + attrPref + '[key_attr]" class="key_attr" />'
				+ '<div class="col-sm-12 oneAttribute">'
				+ '<div class="rowAttribute">'

				+ '<div class="col-sm-5">'
				+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_ATTRIBUTE_NAME') + '</label><input type="text" class="form-control attributeInput" name="' + attrPref + '[name]" value="" /></div>'
				+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE') + '</label><select id="attribute_' + gh + '_display_type" name="' + attrPref + '[display_type]" class="input-medium"> <option value="dropdown" selected="selected">' + Joomla.JText._('COM_REDSHOP_DROPDOWN_LIST') + '</option><option value="radio">' + Joomla.JText._('COM_REDSHOP_RADIOBOX') + '</option></select></div>'
				+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_DESCRIPTION') + '</label><input class="form-control" type="text" name="' + attrPref + '[attribute_description]" value="" /></div>'
				+ '</div>'

				+ '<div class="col-sm-3">'
				+ '<div class="form-group"><label>' + Joomla.JText._('COM_REDSHOP_ORDERING') + '</label><input class="text-center form-control" type="text" name="' + attrPref + '[ordering]" value="' + (gh + 1) + '" /></div>'
				+ '</div>'

				+ '<div class="col-sm-4">'
				+ '<div class="form-group"><input type="checkbox" name="' + attrPref + '[required]" value="1" /><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_ATTRIBUTE_REQUIRED') + '</label></div>'
				+ '<div class="form-group"><input type="checkbox" value="1" name="' + attrPref + '[allow_multiple_selection]" /><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION') + '</label></div>'
				+ '<div class="form-group"><input type="checkbox" value="1"  name="' + attrPref + '[hide_attribute_price]" /><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE') + '</label></div>'
				+ '<div class="form-group"><input type="checkbox" checked="checked" name="' + attrPref + '[published]" value="1" /><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_PUBLISHED') + '</label></div>'
				+ '</div>'

				// rowAttribute
				+ '</div>'

				// oneAttribute
				+ '</div>'

				+ '<div class="property_table">'
				+ renderProperty(attrPref + '[property][0]', gh, 0, 1)
				+ '</div>'

				// attribute_table
				+ '</div>'
			);
			InitSelect('#attribute_' + gh + '_display_type, #attribute_' + gh + '_property_0_setdisplay_type, #attribute_' + gh + '_property_0_oprand');
		});

		$('#mainTableAttributes').on('click', '.delete_subproperty', function () {
			if (confirm(Joomla.JText._('COM_REDSHOP_WARNING_TO_DELETE'))) {
				var $this = $(this);
				if ($this.attr('id')) {
					var entries = $this.attr('id').split('_');
					$.ajax({
						'url': 'index.php?option=com_redshop&view=product_detail&task=delete_subprop&tmpl=component&sp_id=' + entries[1] + '&subattribute_id=' + entries[2],
						'type': 'POST'
					});
				}
				if ($this.closest('.attribute_parameter_tr').find('.sub_attribute_table').size() == 1) {
					$this.closest('.attribute_parameter_tr').find('.showsubproperty').css({'display': 'none'});
				}
				$this.closest('.sub_attribute_table').remove();
			}
		}).on('click', '.deletePropertyMainImage', function () {
			if (confirm(Joomla.JText._('COM_REDSHOP_DO_WANT_TO_DELETE'))) {
				var $this = $(this);
				if ($this.attr('id')) {
					var entries = $this.attr('id').split('_');
					$.ajax({
						'url': 'index.php?tmpl=component&option=com_redshop&view=product_detail&task=removepropertyImage&pid=' + entries[1],
						'type': 'POST'
					});
					$('#propertyImageName' + entries[2]).val('');
					$('#propertyImage' + entries[2]).css({'display': 'none'});
					$this.remove();
				}
			}
		}).on('click', '.deleteSubPropertyMainImage', function () {
			if (confirm(Joomla.JText._('COM_REDSHOP_DO_WANT_TO_DELETE'))) {
				var $this = $(this);
				if ($this.attr('id')) {
					var entries = $this.attr('id').split('_');
					$.ajax({
						'url': 'index.php?tmpl=component&option=com_redshop&view=product_detail&task=removesubpropertyImage&pid=' + entries[1],
						'type': 'POST'
					});
					$('#subPropertyImageName' + entries[2]).val('');
					$('#subpropertyImage' + entries[2]).css({'display': 'none'});
					$this.remove();
				}
			}
		}).on('click', '.delete_property', function () {
			if (confirm(Joomla.JText._('COM_REDSHOP_WARNING_TO_DELETE'))) {
				var $this = $(this);
				if ($this.attr('id')) {
					var entries = $this.attr('id').split('_');
					$.ajax({
						'url': 'index.php?option=com_redshop&view=product_detail&task=delete_prop&tmpl=component&attribute_id=' + entries[2] + '&property_id=' + entries[1],
						'type': 'POST'
					});
				}

				$this.closest('.attr_tbody').prev('.showhidearrow').remove();
				$this.closest('.attr_tbody').remove();

			}
		}).on('click', '.delete_attribute', function () {
			if (confirm(Joomla.JText._('COM_REDSHOP_WARNING_TO_DELETE'))) {
				var $this = $(this);
				if ($this.attr('id')) {
					var entries = $this.attr('id').split('_');
					$.ajax({
						'url': 'index.php?option=com_redshop&view=product_detail&task=delete_attibute&tmpl=component&attribute_id=' + entries[1] + '&product_id=' + entries[2] + '&attribute_set_id=' + entries[3],
						'type': 'POST'
					});
				}
				$this.closest('.attribute_table').remove();
			}
		}).on('click', '.add_property', function (e) {
			e.preventDefault();
			var $this = $(this);
			var gh = parseInt($this.closest('.attribute_table').find('.key_attr').val());
			var $countProp = $this.closest('.attribute_table').find('.count_prop');
			var sh = parseInt($countProp.val());
			$countProp.val(sh + 1);
			var or = sh + 1;
			var propPref = 'attribute[' + gh + '][property][' + sh + ']';
			$this.closest('.attribute_table').find('.property_table').append(
				renderProperty(propPref, gh, sh, or)
			);
			InitSelect('#attribute_' + gh + '_property_' + sh + '_setdisplay_type, #attribute_' + gh + '_property_' + sh + '_oprand');
		}).on('click', '.add_subproperty', function (e) {
			e.preventDefault();
			var $this = $(this);
			$this.parents('.attr_tbody').find('.showsubproperty').css({'display': 'block'});
			$this.parents('.attr_tbody').find('.attribute_parameter_tr').css({'display': 'block'});
			var gh = parseInt($this.parents('.attribute_table').find('.key_attr').val());
			var sh = parseInt($this.parents('.attr_tbody').find('.key_prop').val());
			var countSubProp = $this.parents('.attr_tbody').find('.count_subprop');
			var sp = parseInt(countSubProp.val());
			countSubProp.val(sp + 1);
			var or = sp + 1;
			var subPref = 'attribute[' + gh + '][property][' + sh + '][subproperty][' + sp + ']';
	
			$this.parents('.attr_tbody').find('.sub_attribute_table').append(
				'<div class="sub_property_table"><div class="row">'

				+ '<div class="col-sm-2">'
				+ '<label>' + Joomla.JText._('COM_REDSHOP_PARAMETER') + '</label><input type="text" name="' + subPref + '[name]" >'
				+ '<input type="hidden" name="' + subPref + '[mainImage]" id="subpropmainImage' + gh + sp + '" />'
				+ '<input type="hidden" name="' + subPref + '[image]" id="subPropertyImageName' + gh + sp + '" />'
				+ '</div>'

				+ '<div class="col-sm-2">'
				+ '<label>' + Joomla.JText._('COM_REDSHOP_PRICE') + '</label>'
				+ '<select id="attribute_' + gh + '_subproperty_' + sp + '_oprand" name="' + subPref + '[oprand]" class="input-xmini"><option value="=">=</option><option value="+">+</option><option value="-">-</option><option value="*">*</option><option value="/">/</option></select>'
				+ '<input type="text" name="' + subPref + '[price]" class="input-mini">'
				+ '</div>'

				+ '<div class="col-sm-1">'
				+ '<label>' + Joomla.JText._('COM_REDSHOP_PROPERTY_NUMBER') + '</label><input type="text" name="' + subPref + '[number]" value="" class="vpnrequired">'
				+ '</div>'

				+ '<div class="col-sm-1">'
				+ '<label>' + Joomla.JText._('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD') + '</label><input type="text" name="' + subPref + '[extra_field]">'
				+ '</div>'

				+ '<div class="col-sm-3">'
				+ '<label>' + Joomla.JText._('COM_REDSHOP_ORDERING') + '</label><input type="number" value="' + or + '" name="' + subPref + '[order]">'
				+ '</div>'

				+ '<div class="col-sm-1">'
				+ '<label>' + Joomla.JText._('COM_REDSHOP_PUBLISHED') + '</label><input type="checkbox" value="1" checked="checked" name="' + subPref + '[published]">'
				+ '</div>'

				+ '<div class="col-sm-1">'
				+ '<label>' + Joomla.JText._('COM_REDSHOP_DEFAULT_SELECTED') + '</label><input type="checkbox" value="1" name="' + subPref + '[chk_propdselected]">'
				+ '</div>'

				+ '<div class="col-sm-1">'
				+ '<input value="' + Joomla.JText._('COM_REDSHOP_DELETE') + '" class="btn btn-danger delete_subproperty btn-small" type="button" />'
				+ '</div>'

				// row
				+ '</div></div>'
				);
			InitSelect('#attribute_' + gh + '_subproperty_' + sp + '_oprand');
		}).on('click', '.showhidearrow', function (e) {
			e.preventDefault();
			var $divFromHideShow = $(this).next('.divInspectFromHideShow');
			if ($divFromHideShow.css('display') == 'none') {
				$divFromHideShow.show();
				$(this).find('img').attr('src', 'media/com_redshop/images/arrow.png');
			} else {
				$divFromHideShow.hide();
				$(this).find('img').attr('src', 'media/com_redshop/images/arrow_d.png');
			}
		}).on('change', '.attributeInput', function(){
			console.log(11);
			$(this).parents('.attribute_table').prev('.showhidearrow').find('.attributeName').text($(this).val());
		}).on('change', '.propertyInput', function(){
			$(this).parents('.attr_tbody').prev('.showhidearrow').find('.propertyName').text($(this).val());
		});
		
		$('dd.tabs:visible').on('click', '.checkbox.inline[name*="[preselected]"] input', function () {
			if ($(this).parents('.attribute_table').find('.checkbox.inline input[name*="[allow_multiple_selection]"]').is(':checked') == true) {
				return true;
			}
			else {
				if ($(this).parents('.attribute_table').find('.checkbox.inline[name*="[preselected]"] input:checked').length > 1) {
					alert(Joomla.JText._('COM_REDSHOP_ALERT_PRESELECTED_CHECK'));
					return false;
				}
			}
		}).on('click', '.checkbox.inline input[name*="[allow_multiple_selection]"]', function () {
			if ($(this).is(':checked') == false) {
				$(this).parents('.attribute_table').find('.checkbox.inline[name*="[preselected]"] input').each(function(index, el) {
					$(el).prop('checked', false);
				});
			}
		});
	});
})(jQuery);
