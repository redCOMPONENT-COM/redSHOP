(function ($) {
	$(document).ready(function () {
		function InitSelect(select) {
			try {
				SqueezeBox.initialize();
				SqueezeBox.assign($$('a.modal-thumb'), {
					parse: 'rel'
				});
				if (select != '') {
					$(select).chosen({
						disable_search_threshold: 10,
						allow_single_deselect: true
					});
				}
			}
			catch (err) {
			}
		}

		function renderProperty(propPref, gh, sh, or) {
			return '<div class="row-fluid attr_tbody divInspectFromHideShow">'
			+ '<input type="hidden" value="0" name="' + propPref + '[count_subprop]" class="count_subprop" />'
			+ '<input type="hidden" value="' + sh + '" name="' + propPref + '[key_prop]" class="key_prop" />'
			+ '<div class="row-fluid"><div class="row-fluid">'
			+ '<div class="span1"><a href="#" class="showhidearrow">'
			+ '<img class="arrowimg" src="components/com_redshop/assets/images/arrow.png" alt="" />'
			+ Joomla.JText._('COM_REDSHOP_SUB_ATTRIBUTE') + '</a></div>'
			+ '<div class="span2"><input type="text" class="input-small" name="' + propPref + '[name]" value=""><input type="hidden"  name="attribute[' + gh + '][property][' + sh + '][mainImage]" value="" id="propmainImage' + gh + sh + '"><input type="hidden" name="attribute[' + gh + '][property][' + sh + '][property_image]" id="propertyImageName' + gh + sh + '" /></div>'
			+ '<div class="span2">' + Joomla.JText._('COM_REDSHOP_ORDERING') + ' <input type="text" class="text-center input-xmini" name="' + propPref + '[order]" value="' + or + '"></div>'
			+ '<div class="span2"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_DEFAULT_SELECTED') + ' <input type="checkbox" value="1" name="' + propPref + '[default_sel]" /></label></div>'
			+ '<div class="span2">' + Joomla.JText._('COM_REDSHOP_PRICE') + ' <input type="text" class="text-center input-xmini" value="+" name="' + propPref + '[oprand]" onchange="javascript:oprand_check(this);"> <input type="text" class="input-mini" value="" name="' + propPref + '[price]"></div>'
			+ '</div><div class="row-fluid"><div class="span2"></div>'
			+ '<div class="span3"><div class="button2-left"><div class="image"><a class="modal-thumb" href="index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid=' + gh + sh + '&layout=thumbs" rel="{handler: \'iframe\', size: {x: 900, y: 500}}"></a></div></div><input type="file" name="attribute_' + gh + '_property_' + sh + '_image" /></div>'
			+ '<div class="span1"><img id="propertyImage' + gh + sh + '" src="" style="display: none;" /></div>'
			+ '<div class="span2"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_PUBLISHED') + ' <input type="checkbox" name="' + propPref + '[published]" checked="checked" value="1"></label></div>'
			+ '<div class="span2">' + Joomla.JText._('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD') + ' <input type="text" class="input-mini" name="' + propPref + '[extra_field]" value="" /></div>'
			+ '<div class="span2"><input value="' + Joomla.JText._('COM_REDSHOP_DELETE') + '" class="btn btn-danger delete_property btn-small" type="button" /></div></div><br />'
			+ '<div class="span12 attribute_parameter_tr divFromHideShow">'
			+ '<div class="row-fluid"><div class="row-fluid showsubproperty" style="display: none;">'
			+ '<div class="span1">' + Joomla.JText._('COM_REDSHOP_TITLE') + '</div>'
			+ '<div class="span2"><input class="input-small" type="text" name="' + propPref + '[subproperty][title]" value=""></div>'
			+ '<div class="span3"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_SUBATTRIBUTE_REQUIRED') + ' <input type="checkbox" value="1" name="' + propPref + '[req_sub_att]" /></label></div>'
			+ '<div class="span3"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_SUBATTRIBUTE_MULTISELECTED') + ' <input type="checkbox" value="1" name="attribute[+gh+][property][' + sh + '][multi_sub_att]" /></label></div>'
			+ '<div class="span3">' + Joomla.JText._('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE') + ' <select id="attribute_' + gh + '_property_' + sh + '_setdisplay_type" name="' + propPref + '[setdisplay_type]" class="input-medium"><option value="dropdown" selected="selected" >' + Joomla.JText._('COM_REDSHOP_DROPDOWN_LIST') + '</option><option value="radio" >' + Joomla.JText._('COM_REDSHOP_RADIOBOX') + '</option></select></div></div>'
			+ '<div class="row-fluid"><div class="span12 sub_property_table"><div class="row-fluid">'
			+ '<div class="span1"><a class="btn btn-success add_subproperty btn-small" href="#">+ ' + Joomla.JText._('COM_REDSHOP_NEW_SUB_PROPERTY') + '</a></div>'
			+ '<div class="span11"><div class="row-fluid sub_attributes_table">'
			+ '</div></div></div></div></div></div></div></div></div>';
		}

		$('.add_attribute').on('click', function (e) {
			e.preventDefault();
			var $countAttr = $('.count_attr');
			var gh = parseInt($countAttr.val());
			$countAttr.val(gh + 1);
			var attrPref = 'attribute[' + gh + ']';
			$('.mainTableAttributes').append(
				'<div class="span12 attribute_table divInspectFromHideShow">'
				+ '<input type="hidden" name="' + attrPref + '[count_prop]" class="count_prop" value="1" />'
				+ '<input type="hidden" value="' + gh + '" name="' + attrPref + '[key_attr]" class="key_attr" />'
				+ '<div class="span12 oneAttribute">'
				+ '<div class="span2"><a href="#" class="showhidearrow" style="display: block;"><img class="arrowimg" src="components/com_redshop/assets/images/arrow.png" alt="" />' + Joomla.JText._('COM_REDSHOP_TITLE') + '</a></div>'
				+ '<div class="span2"><input type="text" class="input-small" name="' + attrPref + '[name]" value="" /></div>'
				+ '<div class="span2">' + Joomla.JText._('COM_REDSHOP_DESCRIPTION') + ' <input class="text-center input-small" type="text" name="' + attrPref + '[attribute_description]" value="" /></div>'
				+ '<div class="span2">' + Joomla.JText._('COM_REDSHOP_ORDERING') + ' <input class="text-center input-xmini" type="text" name="' + attrPref + '[ordering]" value="' + (gh + 1) + '" /></div>'
				+ '<div class="span2"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_ATTRIBUTE_REQUIRED') + ' <input type="checkbox" name="' + attrPref + '[required]" value="1" /></label></div>'
				+ '<div class="span2"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_PUBLISHED') + ' <input type="checkbox" checked="checked" name="' + attrPref + '[published]" value="1" /></label></div>'
				+ '<div class="span1"><input class="btn btn-danger delete_attribute btn-small" value="' + Joomla.JText._('COM_REDSHOP_DELETE_ATTRIBUTE') + '" type="button" /></div>'
				+ '</div>'
				+ '<div class="span12 attribute_table_pro divFromHideShow"><div class="row-fluid attrSecondRow">'
				+ '<div class="span2"><a class="btn btn-success add_property btn-small">+ ' + Joomla.JText._('COM_REDSHOP_ADD_SUB_ATTRIBUTE') + '</a></div>'
				+ '<div class="span3"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION') + ' <input type="checkbox" value="1" name="' + attrPref + '[allow_multiple_selection]" /></label></div>'
				+ '<div class="span3"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE') + ' <input type="checkbox" value="1"  name="' + attrPref + '[hide_attribute_price]" /></label></div>'
				+ '<div class="span3">' + Joomla.JText._('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE') + ' <select id="attribute_' + gh + '_display_type" name="' + attrPref + '[display_type]" class="input-medium"> <option value="dropdown" selected="selected">' + Joomla.JText._('COM_REDSHOP_DROPDOWN_LIST') + '</option><option value="radio">' + Joomla.JText._('COM_REDSHOP_RADIOBOX') + '</option></select></div>'
				+ '</div>'
				+ '<div class="row-fluid property_table">'
				+ renderProperty(attrPref + '[property][0]', gh, 0, 1)
				+ '</div></div></div>'
			);
			InitSelect('#attribute_' + gh + '_display_type, #attribute_' + gh + '_property_0_setdisplay_type');
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
			InitSelect('#attribute_' + gh + '_property_' + sh + '_setdisplay_type');
		}).on('click', '.add_subproperty', function (e) {
			e.preventDefault();
			var $this = $(this);
			$this.closest('.attribute_parameter_tr').find('.showsubproperty').css({'display': 'block'});
			$this.closest('.attribute_parameter_tr').css({'display': 'block'});
			var gh = parseInt($this.closest('.attribute_table').find('.key_attr').val());
			var sh = parseInt($this.closest('.attr_tbody').find('.key_prop').val());
			var $countSubProp = $this.closest('.attr_tbody').find('.count_subprop');
			var sp = parseInt($countSubProp.val());
			$countSubProp.val(sp + 1);
			var or = sp + 1;
			var subPref = 'attribute[' + gh + '][property][' + sh + '][subproperty][' + sp + ']';
			$this.closest('.sub_property_table').find('.sub_attributes_table').append(
				'<div class="row-fluid sub_attribute_table"> <div class="span2">'
				+ Joomla.JText._('COM_REDSHOP_PARAMETER') + ' <input type="text" name="' + subPref + '[name]"  class="input-small">'
				+ '<input type="hidden" name="' + subPref + '[mainImage]" id="subpropmainImage' + gh + sp + '" />'
				+ '<input type="hidden" name="' + subPref + '[image]" id="subPropertyImageName' + gh + sp + '" />'
				+ '</div><div class="span2">' + Joomla.JText._('COM_REDSHOP_ORDERING') + ' '
				+ '<input type="text" value="' + or + '" name="' + subPref + '[order]" class="text-center input-xmini">'
				+ '</div><div class="span2"><label class="inline checkbox">' + Joomla.JText._('COM_REDSHOP_DEFAULT_SELECTED') + ' '
				+ ' <input type="checkbox" value="1" name="' + subPref + '[chk_propdselected]">'
				+ '</label></div><div class="span3">' + Joomla.JText._('COM_REDSHOP_PRICE') + ' '
				+ '<input type="text" onchange="javascript:oprand_check(this);" name="' + subPref + '[oprand]" value="+" class="input-xmini text-center"> '
				+ '<input type="text" name="' + subPref + '[price]" class="input-mini"></div>'
				+ '<div class="span3">' + Joomla.JText._('COM_REDSHOP_PROPERTY_NUMBER') + ' <input type="text" name="' + subPref + '[number]" value="" class="vpnrequired input-mini" size="14"></div>'
				+ '<div class="span12 subAttrMedia">'
				+ '<div class="row-fluid"><div class="span3 offset2">'
				+ '<div class="button2-left"><div class="image"><a class="modal-thumb" href="index.php?tmpl=component&option=com_redshop&view=media&fsec=subproperty&fid=' + gh + sp + '&layout=thumbs" rel="{handler: \'iframe\', size: {x: 900, y: 500}}" ></a></div></div>'
				+ '<input type="file" name="attribute_' + gh + '_property_' + sh + '_subproperty_' + sp + '_image" /></div>'
				+ '<div class="span1"><img id="subpropertyImage' + gh + sp + '" src="" style="display: none;"/></div>'
				+ '<div class="span2"><label class="checkbox inline">' + Joomla.JText._('COM_REDSHOP_PUBLISHED') + ' <input type="checkbox" value="1" checked="checked" name="' + subPref + '[published]"></label></div>'
				+ '<div class="span2">'
				+ Joomla.JText._('COM_REDSHOP_ATTRIBUTE_EXTRAFIELD') + ' <input type="text" name="' + subPref + '[extra_field]" class="input-mini">'
				+ '</div><div class="span2">'
				+ '<input value="' + Joomla.JText._('COM_REDSHOP_DELETE') + '" class="btn btn-danger delete_subproperty btn-small" type="button" />'
				+ '</div></div></div></div>');
			InitSelect('');
		}).on('click', '.showhidearrow', function (e) {
			e.preventDefault();
			var $divFromHideShow = $(this).closest('.divInspectFromHideShow').find('.divFromHideShow');
			if ($divFromHideShow.css('display') == 'none') {
				$divFromHideShow.show(200);
				$(this).find('img').attr('src', 'components/com_redshop/assets/images/arrow.png');
			} else {
				$divFromHideShow.hide(200);
				$(this).find('img').attr('src', 'components/com_redshop/assets/images/arrow_d.png');
			}
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
