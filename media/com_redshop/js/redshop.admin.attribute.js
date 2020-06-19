jQuery(document).ready(function() {
    var attributeData = jQuery("#divAttribute").attr("data");

    jQuery('body').on('click', '.icon-expand', function(e) {
        jQuery(this).attr('class', 'icon-contract btn-collapse zoom');
    });

    jQuery('body').on('click', '.icon-contract', function(e) {
        jQuery(this).attr('class', 'icon-expand btn-collapse zoom');
    });

    jQuery('body').on('click', '.btn-collapse', function(e) {
        e.preventDefault();
        var target = jQuery(this).attr('target-id');
        jQuery('div[child-of=' + target + ']').toggle();
    });

    jQuery('#new_attribute').find('button.btn-add-dependency').click((e) => {
        e.preventDefault();
        addDependencyForElement('#new_attribute');
    });

    jQuery('#new_property').find('button.btn-add-dependency').click((e) => {
        e.preventDefault();
        addDependencyForElement('#new_property');
    });

    jQuery('#new_subproperty').find('button.btn-add-dependency').click((e) => {
        e.preventDefault();
        addDependencyForElement('#new_subproperty');
    });

    jQuery('body').on('click', '.icon-minus', function(e) {
        e.preventDefault();
        let dataAjax;
        let target;
        let confirmResult = confirm(Joomla.JText._('COM_REDSHOP_CONFIRM_DELETE'));``

        if ((confirmResult == true) && (jQuery(this).attr('class') == 'icon-minus btn-functionality zoom')) {
            target = jQuery(this).parent().parent().parent();
            let token = redSHOP.RSConfig._('AJAX_TOKEN');
            dataAjax = {
                type: target.attr('type'),
                id: target.attr('data-id')
            };

            dataAjax[redSHOP.RSConfig._('AJAX_TOKEN')] = 1;

            jQuery.ajax({
                method: 'POST',
                url: redSHOP.RSConfig._('SITE_URL') + 'administrator/index.php?option=com_ajax&plugin=DeleteElement&group=redshop_product&format=raw',
                data: dataAjax,
                beforeSend: function() {
                    jQuery('#loader').show();
                }
            }).success(function(res) {
                if (res == 1) {
                    calculateRemainingChild(target, '-');
                    target.remove();
                }

                jQuery('#loader').hide();
            });
        }
    });

    jQuery('body').on('click', '.btn-ajax', function(e) {
        let target = '#' + jQuery(this).attr('target');
        let targetType = jQuery(this).attr('type');
        let attributeData = getAllElements();
        let property_id;
        let attribute_id;
        let ele;

        jQuery(target).show();
        jQuery(target + " #modal").modal('show');

        jQuery('body').find('#new_property').find('.rs-media-cropper-btn').parent().remove();

        if (targetType == 'attribute') {
            jQuery(target).find('small').html('[ ' + Joomla.JText._('COM_REDSHOP_NEW') + ' ]');
            jQuery(target).find('input[name=attribute_id]').val(0);
            jQuery(target).find('input[name=attribute_name]').val('');
            jQuery(target).find('textarea[name=attribute_description]').val('');
            jQuery(target).find('label[id=allow_multiple_selection_0-lbl]').click();
            jQuery(target).find('label[id=attribute_required_0-lbl]').click();
            jQuery(target).find('label[id=attribute_published_1-lbl]').click();
            jQuery(target).find('label[id=hide_0-lbl]').click();
            jQuery(target).find('label[id=hide_attribute_price_0-lbl]').click();
            jQuery(target).find('label[id=display_type_dropdown-lbl]').click();
            jQuery(target).find('input[name=ordering]').val('0');

            ele = jQuery(target).find('select[data-name=dependency_attribute]');
            jQuery(target).find('.data-dependency').html('');
            setDependencySelector(ele, attributeData);

        } else if (targetType == 'property') {
            jQuery(target).find('input[name=attribute_id]').val(jQuery(this).attr('attribute-id'));
            jQuery(target).find('input[name=property_id]').val(0);
            jQuery(target).find('small').html('[ ' + Joomla.JText._('COM_REDSHOP_NEW') + ' ]');
            jQuery(target).find('input[name=property_name]').val('');
            jQuery(target).find('input[name=property_number]').val('');
            jQuery(target).find('textarea[name=extra_field]').val('');
            jQuery(target).find('input[name=property_price]').val('');

            let oprand = jQuery(target).find('select[name=oprand]').select2('destroy');
            oprand.parent().find('div[class^=chzn-container]').remove();
            oprand.val('S');
            oprand.select2();

            jQuery(target).find('label[id=setrequire_selected_0-lbl]').click();
            jQuery(target).find('label[id=setdefault_selected_0-lbl]').click();
            jQuery(target).find('label[id=setmulti_selected_0-lbl]').click();
            jQuery(target).find('label[id=setdisplay_type_dropdown-lbl]').click();
            jQuery(target).find('label[id=hide_0-lbl]').click();

            dz = jQuery(target).find('div[data-content=propduct-image-lbl]');
            dz.html(Joomla.JText._('COM_REDSHOP_IMAGE'));
            jQuery(target).find('.rs-media-cropper-btn').parent().remove();
            jQuery(target).find('.rs-media-remove-btn').click();

            ele = jQuery(target).find('select[data-name=dependency_attribute]');
            jQuery(target).find('.data-dependency').html('');
            setDependencySelector(ele, attributeData);

        } else if (targetType == 'subproperty') {
            jQuery(target).find('small').html('[ ' + Joomla.JText._('COM_REDSHOP_NEW') + ' ]');
            jQuery(target).find('input[name=subattribute_color_id]').val(0);

            property_id = jQuery(this).attr('property-id');
            attribute_id = jQuery(this).attr('attribute-id');
            jQuery(target).find('input[data-name=attribute_id]').val(attribute_id);
            setDependencySelector(jQuery(target).find('select[name=parent_id]'), attributeData[attribute_id][property_id]);


            jQuery(target).find('input[name=subattribute_id]').val(property_id);

            jQuery(target).find('input[name=subattribute_color_name]').val('');
            jQuery(target).find('input[name=subattribute_color_title]').val('');

            dz = jQuery(target).find('div[data-content=subproperty-image-lbl]');
            dz.html(Joomla.JText._('COM_REDSHOP_IMAGE'));
            jQuery(target).find('.rs-media-cropper-btn').parent().remove();
            jQuery(target).find('.rs-media-remove-btn').click();

            let oprand = jQuery(target).find('select[name=oprand]').select2('destroy');
            oprand.parent().find('div[class^=chzn-container]').remove();
            oprand.val('S');
            oprand.select2();

            jQuery(target).find('input[name=subattribute_color_price]').val('');
            jQuery(target).find('label[id=setdefault_selected_0-lbl]').click();
            jQuery(target).find('label[id=hide_0-lbl]').click();

            jQuery(target).find('textarea[name=extra_field]').val('');
            jQuery(target).find('textarea[name=ordering]').val(0);

            ele = jQuery(target).find('select[data-name=dependency_attribute]');
            jQuery(target).find('.data-dependency').html('');
            setDependencySelector(ele, attributeData);
        }
    });

    jQuery('body').on('click', '.btn-functionality', function(e) {
        let sourceData;
        let target;
        let parent;
        let t;
        let property_id;
        let attribute_id;
        let attributeData = getAllElements();

        e.preventDefault();

        if (jQuery(this).attr('class') == 'icon-edit btn-functionality zoom') {
            parent = jQuery(this).parent().parent().parent();
            t = parent.attr('type');
            sourceData = parent.attr('data');
            sourceData = JSON.parse(atob(sourceData));

            target = '#new_' + t;

            if (t == 'subproperty') {
                attribute_id = jQuery(this).attr('attribute-id');
                property_id = jQuery(this).attr('property-id');
                setDependencySelector(jQuery(target).find('select[name=parent_id]'), attributeData[attribute_id][property_id]);
            }

            changeModalForUpdate(target, t, sourceData);

            jQuery(target).show();
            jQuery(target + " #modal").modal('show');
        }
    });

    jQuery('body').keyup(function(e) {
        if ((e.keyCode === 13) || (e.keyCode === 27)) {
            jQuery('.modal-close').click();
        }
    });

    jQuery('.modal-close').click(function(e) {
        e.preventDefault();
        jQuery('#new_attribute').hide();
        jQuery('#attribute_result').hide();
        jQuery('#new_property').hide();
        jQuery('#property_result').hide();
        jQuery('#new_subproperty').hide();
        jQuery('#subproperty_result').hide();
    });

    jQuery('body').on('click', '#save_attribute_top', function(e) {
        e.preventDefault();
        ajaxSaveElement('attribute');
    });

    jQuery('body').on('click', '#save_attribute', function(e) {
        e.preventDefault();
        ajaxSaveElement('attribute');
    });

    jQuery('body').on('click', '#save_property_top', function(e) {
        e.preventDefault();
        ajaxSaveElement('property');
    });

    jQuery('body').on('click', '#save_property', function(e) {
        e.preventDefault();
        ajaxSaveElement('property');
    });

    jQuery('body').on('click', '#save_subproperty_top', function(e) {
        e.preventDefault();
        ajaxSaveElement('subproperty');
    });

    jQuery('body').on('click', '#save_subproperty', function(e) {
        e.preventDefault();
        ajaxSaveElement('subproperty');
    });

});

const getProductId = () => {
    return jQuery('body').find('div#divAttribute').attr('product-id');
}

const updateCommonData = (t, res) => {
    if (res.queryType == 'insert') {
        let divAttribte = getAllElements();

        switch (t) {
            case 'attribute':
                divAttribte[res.attribute_id] = {
                    name: res.attribute_name
                };
                break;
            case 'property':
                divAttribte[res.attribute_id][res.property_id] = {
                    name: res.property_name
                };
                break;
            case 'subproperty':
                divAttribte[res.attribute_id][res.subattribute_id][res.subattribute_color_id] = {
                    name: res.subattribute_color_name
                };
                break;
            default:
        }

        divAttribte = btoa(JSON.stringify(divAttribte));

        jQuery('body').find('div[id=divAttribute]').attr('data', divAttribte);
    }
}

const calculateRemainingChild = (target, operand) => {

    parent = jQuery(target).parent().attr('child-of');
    parent = jQuery('#' + parent);
    btn = parent.find('button.btn-collapse');
    no = btn.html();

    switch (operand) {
        case '-':

            try {
                no = parseInt(no);
                no--;
                btn.html(no);
            } catch (error) {

            }

            break;
        case '+':

            try {
                no = parseInt(no);
                no++;
                btn.html(no);
            } catch (error) {

            }
            break;
        default:


    }
}

const loadExistDependency = (target, dependency) => {
    if (dependency == undefined) {
        return;
    }

    let data = decodeDependency(atob(dependency));

    if ((data != null) && (data != undefined)) {
        for (let i = 0; i < data.length; i++) {
            saveDependencyForElement(target, data[i], i % 2);
        }
    }
};

const addDependencyForElement = (target) => {
    let data = jQuery(target).find('div[data-id=data-dependency]');
    let flag = true;
    let rowFlag;
    let input = {
        attribute: {
            value: jQuery(target).find('select[data-name=dependency_attribute]').val(),
            text: jQuery(target).find('select[data-name=dependency_attribute] option:selected').html()
        },
        property: {
            value: jQuery(target).find('select[data-name=dependency_property]').val(),
            text: jQuery(target).find('select[data-name=dependency_property] option:selected').html()
        },
        subproperty: {
            value: jQuery(target).find('select[data-name=dependency_subproperty]').val(),
            text: jQuery(target).find('select[data-name=dependency_subproperty] option:selected').html()
        }
    };

    if ((input.attribute.value == undefined) || (input.attribute.value == '') || (input.attribute.value == null)) {
        flag = false;
        input.attribute.text = '';
    }

    if ((input.property.value == undefined) || (input.property.value == '') || (input.property.value == null)) {
        input.property.text = '';
    }

    if ((input.subproperty.value == undefined) || (input.subproperty.value == '') || (input.subproperty.value == null)) {
        input.subproperty.text = '';
    }

    rowFlag = data.find('div.row-fluid').length % 2;

    if (flag == true) {
        saveDependencyForElement(target, input, rowFlag);
    }

};

const deleteDependencyForElement = (target) => {
    jQuery(target).parent().parent().remove();
}

const saveDependencyForElement = (target, input, index) => {
    let div = jQuery(target).find('div[data-id=data-dependency]');
    let inputHtml;
    let data = encodeDependency(input);

    inputHtml = '<div class="row-fluid row' + index + ' data-dependency" data=\'' + data + '\'>';
    inputHtml += '<div class="span3">' + input.attribute.text + '</div>';
    inputHtml += '<div class="span3">' + input.property.text + '</div>';
    inputHtml += '<div class="span3">' + input.subproperty.text + '</div>';
    inputHtml += '<div class="span1"><span class="btn btn-danger btn-delete-dependency" onclick="deleteDependencyForElement(this);">x</span></div>';
    inputHtml += '</div>';

    div.append(inputHtml);
};

const encodeDependency = (data) => {
    let ret;

    try {
        ret = JSON.stringify(data);
    } catch (err) {
        ret = "";
    }

    return ret;
}

const decodeDependency = (data) => {
    let ret;

    try {
        ret = JSON.parse(data);
    } catch (err) {
        ret = new Array();
    }

    return ret;
}

const setDependencySelector = (ele, data) => {
    if (data == undefined) {
        return;
    }

    let aKeys = Object.keys(data);
    let aValues = Object.values(data);
    let aHtml = '<option value="">' + Joomla.JText._('COM_REDSHOP_SELECT') + '</option>';
    let aSelectedId;
    let nextTarget = '';

    ele.select2('destroy');

    for (i = 0; i < aKeys.length; i++) {
        if (aValues[i].name != undefined) {
            aHtml += '<option value="' + aKeys[i] + '">' + aValues[i].name + '</option>';
        }
    };

    ele.html(aHtml);
    ele.parent().find('.chzn-container').remove();
    ele.select2();

    aSelectedId = ele.val();
    nextTarget = '';

    switch (jQuery(ele).attr('data-name')) {
        case 'dependency_attribute':
            nextTarget = jQuery('select[data-name=dependency_property]');
            setDependencySelector(nextTarget, data[aSelectedId]);
            break;
        case 'dependency_property':
            nextTarget = jQuery('select[data-name=dependency_subproperty]');
            setDependencySelector(nextTarget, data[aSelectedId]);
            break;
        case 'dependency_subproperty':
            break;
        default:
            break;

    };

    ele.on('change', (e) => {
        aSelectedId = e.added.id;
        nextTarget = '';

        switch (jQuery(ele).attr('data-name')) {
            case 'dependency_attribute':
                nextTarget = jQuery('select[data-name=dependency_property]');
                setDependencySelector(nextTarget, data[aSelectedId]);
                break;
            case 'dependency_property':
                nextTarget = jQuery('select[data-name=dependency_subproperty]');
                setDependencySelector(nextTarget, data[aSelectedId]);
                break;
            case 'dependency_subproperty':
                break;
            default:
                break;

        };
    });
}

const getAllElements = () => {
    let attributeData = jQuery('body').find('div[id=divAttribute]').attr('data');

    return JSON.parse(atob(attributeData));
};

const loadElementList = (lv) => {
    let attributeData = getAllElements();

    switch (lv) {
        case '0':
        default:
            return attributeData;
            break;

    }
}

const changeModalForUpdate = (target, t, sourceData) => {
    let dz;
    let oprand;
    let imgSrc;
    let attributeData = getAllElements();
    let ele;

    switch (t) {
        case 'subproperty':
            jQuery(target).find('small').html('[ ' + sourceData.subattribute_color_id + '- ' + Joomla.JText._('COM_REDSHOP_EDIT') + ' ]');
            jQuery(target).find('input[name=subattribute_id]').val(sourceData.subattribute_id);
            jQuery(target).find('input[name=subattribute_color_id]').val(sourceData.subattribute_color_id);
            jQuery(target).find('input[name=subattribute_color_name]').val(sourceData.subattribute_color_name);
            jQuery(target).find('input[name=subattribute_color_title]').val(sourceData.subattribute_color_title);
            jQuery(target).find('input[name=subattribute_color_number]').val(sourceData.subattribute_color_number);
            jQuery(target).find('input[name=subattribute_color_price]').val(sourceData.subattribute_color_price);
            jQuery(target).find('textarea[name=extra_field]').val(sourceData.extra_field);
            jQuery(target).find('input[name=ordering]').val(sourceData.ordering);

            parent = jQuery(target).find('select[name=parent_id]').select2('destroy');
            parent.parent().find('div[class^=chzn-container]').remove();
            parent.val(sourceData.parent_id);
            parent.select2();

            oprand = jQuery(target).find('select[name=oprand]').select2('destroy');
            oprand.parent().find('div[class^=chzn-container]').remove();
            oprand.val(sourceData.oprand);
            oprand.select2();

            jQuery(target).find('label[id=setdefault_selected_' + sourceData.setdefault_selected + '-lbl]').click();
            jQuery(target).find('label[id=subattribute_published_' + sourceData.subattribute_published + '-lbl]').click();
            jQuery(target).find('label[id=subattribute_color_hide_' + sourceData.hide + '-lbl]').click();

            imgSrc = redSHOP.RSConfig._('REDSHOP_FRONT_IMAGES_ABSPATH') + 'subcolor/' + sourceData.subattribute_color_image;
            dz = jQuery(target).find('div[data-content=subproperty-image-lbl]');
            dz.html(Joomla.JText._('COM_REDSHOP_IMAGE') + '<div style="padding: 10px"><img src="' + imgSrc + '" style="width: 100px" /></div>');
            jQuery(target).find('.rs-media-cropper-btn').parent().remove();
            jQuery(target).find('.rs-media-remove-btn').click();

            ele = jQuery(target).find('select[data-name=dependency_attribute]');
            setDependencySelector(ele, attributeData);
            jQuery(target).find('div[data-id=data-dependency]').html('');
            loadExistDependency(target, sourceData.dependency);
            break;
        case 'property':
            jQuery(target).find('input[name=attribute_id]').val(sourceData.attribute_id);
            jQuery(target).find('input[name=property_id]').val(sourceData.property_id);
            jQuery(target).find('small').html('[ ' + sourceData.property_id + '- ' + Joomla.JText._('COM_REDSHOP_EDIT') + ' ]');
            jQuery(target).find('input[name=property_name]').val(sourceData.property_name);
            jQuery(target).find('input[name=property_number]').val(sourceData.property_number);
            jQuery(target).find('textarea[name=extra_field]').val(sourceData.extra_field);
            jQuery(target).find('input[name=property_price]').val(sourceData.property_price);

            oprand = jQuery(target).find('select[name=oprand]').select2('destroy');
            oprand.parent().find('div[class^=chzn-container]').remove();
            oprand.val(sourceData.oprand);
            oprand.select2();

            jQuery(target).find('label[id=property_setrequire_selected_' + sourceData.setrequire_selected + '-lbl]').click();
            jQuery(target).find('label[id=property_setdefault_selected_' + sourceData.setdefault_selected + '-lbl]').click();
            jQuery(target).find('label[id=setmulti_selected_' + sourceData.setmulti_selected + '-lbl]').click();
            jQuery(target).find('label[id=setdisplay_type_' + sourceData.setdisplay_type + '-lbl]').click();
            jQuery(target).find('label[id=property_published' + sourceData.property_published + '-lbl]').click();
            jQuery(target).find('label[id=property_hide_' + sourceData.hide + '-lbl]').click();

            imgSrc = redSHOP.RSConfig._('REDSHOP_FRONT_IMAGES_ABSPATH') + 'product_attributes/' + sourceData.property_image;
            dz = jQuery(target).find('div[data-content=propduct-image-lbl]');
            dz.html(Joomla.JText._('COM_REDSHOP_IMAGE') + '<div style="padding: 10px"><img src="' + imgSrc + '" style="width: 100px" /></div>');
            jQuery(target).find('.rs-media-cropper-btn').parent().remove();
            jQuery(target).find('.rs-media-remove-btn').click();

            ele = jQuery(target).find('select[data-name=dependency_attribute]');
            setDependencySelector(ele, attributeData);
            jQuery(target).find('div[data-id=data-dependency]').html('');
            loadExistDependency(target, sourceData.dependency);

            break;
        case 'attribute':
        default:
            jQuery(target).find('small').html('[ ' + sourceData.attribute_id + '- ' + Joomla.JText._('COM_REDSHOP_EDIT') + ' ]');
            jQuery(target).find('input[name=attribute_id]').val(sourceData.attribute_id);
            jQuery(target).find('input[name=attribute_name]').val(sourceData.attribute_name);
            jQuery(target).find('textarea[name=attribute_description]').val(sourceData.attribute_description);
            jQuery(target).find('label[id=allow_multiple_selection_' + sourceData.allow_multiple_selection + '-lbl]').click();
            jQuery(target).find('label[id=attribute_required_' + sourceData.attribute_required + '-lbl]').click();
            jQuery(target).find('label[id=attribute_published_' + sourceData.attribute_published + '-lbl]').click();
            jQuery(target).find('label[id=attribute_hide_' + sourceData.hide + '-lbl]').click();
            jQuery(target).find('label[id=hide_attribute_price_' + sourceData.hide_attribute_price + '-lbl]').click();
            jQuery(target).find('label[id=display_type_' + sourceData.display_type + '-lbl]').click();
            jQuery(target).find('input[name=ordering]').val(sourceData.ordering);
            jQuery(target).find('input[name=ordering]').val(sourceData.ordering);

            ele = jQuery(target).find('select[data-name=dependency_attribute]');
            setDependencySelector(ele, attributeData);
            jQuery(target).find('div[data-id=data-dependency]').html('');
            loadExistDependency(target, sourceData.dependency);
    }
}

const ajaxSaveElement = (t) => {
    let encodeValues;
    let dataAjax;
    let inputValues;

    inputValues = getMapInputs('#new_' + t);
    encodeValues = JSON.stringify(inputValues, getCircularReplacer());
    let token = redSHOP.RSConfig._('AJAX_TOKEN');
    dataAjax = {
        encodeValues: encodeValues,
        productId: getProductId(),
        type: t
    };

    dataAjax[redSHOP.RSConfig._('AJAX_TOKEN')] = 1;

    jQuery.ajax({
        method: 'POST',
        url: redSHOP.RSConfig._('SITE_URL') + 'administrator/index.php?option=com_ajax&plugin=SaveElement&group=redshop_product&format=raw',
        data: dataAjax,
        beforeSend: function() {
            jQuery('#loader').show();
        }
    }).success(function(res) {
        let rpData;
        let element;
        let mess = jQuery('#new_' + t).find('#' + t + '_result');

        if (res != undefined) {
            res = JSON.parse(res);
        }

        if (res.result == true) {
            mess.show()
                .html('<i class="fa fa-check-circle"></i> ' + Joomla.JText._('COM_REDSHOP_SAVE_SUCCESSFUL'));

            element = prepareElementForUpdateList(t, res);

            rpData = btoa(JSON.stringify(res));
            assignRowAttributeData(element, t, rpData, res);
            updateCommonData(t, res);
        } else {
            mess.show()
                .html('<i class="fa fa-check-circle"></i> ' + Joomla.JText._('COM_REDSHOP_SAVE_FAIL') + '');
        }

        jQuery('#loader').hide();

    });
};

const getMapInputs = (target) => {
    let finalRet = jQuery(target + ' :input').map(function() {
        let type = jQuery(this).attr("type");
        let name = jQuery(this).attr("name");
        let value = jQuery(this).val();
        let ele = this.tagName;
        let ret = {
            name: name,
            value: value
        };

        if (value != '') {
            switch (type) {
                case 'hidden':
                case 'text':
                    return ret;
                    break;
                case 'checkbox':
                case 'radio':
                    if (this.checked) {
                        return ret;
                    }
                    break;
                case 'select':
                    return ret;
                    break;
                default:
                    switch (ele) {
                        case 'SELECT':
                        case 'TEXTAREA':
                            return ret;
                            break;
                    }
                    break;
            }
        }
    });

    let dependency = jQuery(target).find('div.data-dependency').map(function() {
        return {
            name: 'dependency',
            value: jQuery(this).attr('data')
        }
    });

    for (let k = 0; k < dependency.length; k++) {
        finalRet.push(dependency[k]);
    }

    return finalRet;
};

const prepareElementForUpdateList = (type, res) => {
    let element;
    let sample;
    let e1;
    let divProperties;
    let divSubProperties;
    let cols;

    switch (type) {
        case 'subproperty':
            if (res.queryType == 'insert') {
                sample = jQuery('div[id=subproperty_id_new_' + res.subattribute_id + ']');
                element = sample.clone();
                element.insertAfter(sample);
                element.show();
                element.attr('id', 'subproperty_id_' + res.subattribute_color_id);
                element.find('.btn-collapse').attr('target-id', 'property_id_' + res.subattribute_id);
                element.attr('style', 'background-color: yellow');
            } else {
                element = jQuery('div[id=subproperty_id_' + res.subattribute_color_id + ']');
            }

            element.attr('dependency', res.dependency);
            break;
        case 'property':
            if (res.queryType == 'insert') {
                sample = jQuery('div[id=property_id_new_' + res.attribute_id + ']');
                element = sample.clone();
                element.insertAfter(sample);
                element.show();
                element.attr('id', 'property_id_' + res.property_id);
                element.find('.btn-collapse').attr('target-id', 'property_id_' + res.property_id);
                element.attr('style', 'background-color: yellow');

                e1 = jQuery('div[data-id=new-subproperty-bar]').first().clone();
                e1.find('div.btn-ajax').attr('attribute-id', res.attribute_id);
                e1.find('div.btn-ajax').attr('property-id', res.property_id);
                e1.show();
                divSubProperties = document.createElement('div');
                divSubProperties = jQuery(divSubProperties);
                divSubProperties.attr('class', 'div_subproperties');
                divSubProperties.attr('child-of', 'property_id_' + res.property_id);
                divSubProperties.append(e1);
                divSubProperties.insertAfter(element);
                divSubProperties.hide();

                let divSubSample = document.createElement('div');
                divSubSample = jQuery(divSubSample);
                divSubSample.attr('id', 'subproperty_id_new_' + res.property_id);
                divSubSample.attr('class', 'row-fluid row0');
                divSubSample.attr('style', 'display:none;');
                divSubSample.attr('type', 'subproperty');

                //TODO defind all fields here for subattribute
                cols = createSampleColumns(divSubSample, 'subproperty', res);

                divSubProperties.append(divSubSample);
            } else {
                element = jQuery('div[id=property_id_' + res.property_id + ']');
            }

            element.attr('dependency', res.dependency);

            break;
        case 'attribute':
        default:
            if (res.queryType == 'insert') {
                sample = jQuery('div[id=attribute_id_new]');
                element = sample.clone();
                element.insertAfter(sample);
                element.show();
                element.attr('id', 'attribute_id_' + res.attribute_id);
                element.find('.btn-collapse').attr('target-id', 'attribute_id_' + res.attribute_id);
                element.attr('style', 'background-color: yellow');

                e1 = jQuery('div[data-id=new-property-bar]').first().clone();
                e1.find('div.btn-ajax').attr('attribute-id', res.attribute_id);
                e1.show();
                let divProperties = document.createElement('div');
                divProperties = jQuery(divProperties);
                divProperties.attr('class', 'div_properties');
                divProperties.attr('child-of', 'attribute_id_' + res.attribute_id);
                divProperties.append(e1);
                divProperties.insertAfter(element);
                divProperties.hide();

                divSample = document.createElement('div');
                divSample = jQuery(divSample);
                divSample.attr('id', 'property_id_new_' + res.attribute_id);
                divSample.attr('class', 'row-fluid row0');
                divSample.attr('style', 'display:none;');
                divSample.attr('type', 'property');

                //TODO defind all fields here for subattribute
                cols = createSampleColumns(divSample, 'property', res);

                divProperties.append(divSample);

            } else {
                element = jQuery('div[id=attribute_id_' + res.attribute_id + ']');
            }

            element.attr('dependency', res.dependency);

            break;

    }

    return element;
}

const createSampleColumns = (papa, t, res) => {
    let c = [];
    let i = 0;
    let inner;
    let subInner;
    switch (t) {
        case 'property':
            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'property_id');
            c[i].attr('class', 'td span1');
            c[i].attr('style', 'color: darkred');
            c[i].html('<i></i>');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'property_image');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('img'));
            inner.attr('src', '');
            inner.attr('style', 'display:none');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('a'));
            inner.attr('class', 'joom-box btn btn-small');
            inner.attr('href', '');
            inner.attr('rel', "{handler: 'iframe', size: {x: 950, y: 500}}");
            inner.html('<img src="' + redSHOP.RSConfig._('SITE_URL') + 'media/com_redshop/images/media16.png" alt="" />');
            c[i].append(inner.clone());
            inner.html('<img src="' + redSHOP.RSConfig._('SITE_URL') + 'media/com_redshop/images/discountmanagmenet16.png" alt="" />');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'property_name');
            c[i].attr('class', 'td span2');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'setdefault_selected');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('span'));
            inner.attr('class', 'icon-checkbox-unchecked');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'oprand');
            c[i].attr('class', 'td span1');
            c[i].html('n/a');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'property_price');
            c[i].attr('class', 'td span1');
            c[i].html('0');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'setrequire_selected');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('span'));
            inner.attr('class', 'icon-checkbox-unchecked');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'property_published');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('span'));
            inner.attr('class', 'icon-checkbox-unchecked');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'setdisplay_type');
            c[i].attr('class', 'td span1');
            c[i].html('dropdown');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'hide');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('span'));
            inner.attr('class', 'icon-checkbox-unchecked');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            inner = jQuery(document.createElement('button'));
            inner.attr('class', 'btn btn-collapse');
            inner.attr('target-id', 'propery_id_' + res.property_id);
            inner.attr('style', 'background-color: darkgreen; color: white;');
            inner.html('0');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('div'));
            inner.attr('class', 'btn-edit-inrow');
            subInner = jQuery(document.createElement('span'));
            subInner.attr('class', 'icon-minus btn-functionality zoom');
            inner.append(subInner.clone());
            subInner.attr('class', 'icon-edit btn-functionality zoom');
            subInner.attr('attribute-id', res.attribute_id);
            subInner.attr('property-id', res.property_id);
            inner.prepend(subInner.clone());
            subInner.attr('class', 'icon-expand btn-collapse zoom');
            subInner.attr('target-id', 'propery_id_' + res.property_id);
            inner.prepend(subInner.clone());
            c[i].append(inner);
            papa.append(c[i]);
            i++;

            break;
        case 'subproperty':
            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'subattribute_color_id');
            c[i].attr('class', 'td span1');
            c[i].attr('style', 'color: darkgreen');
            c[i].html('<i></i>');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'subattribute_color_image');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('img'));
            inner.attr('src', '');
            inner.attr('style', 'display:none');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('a'));
            inner.attr('class', 'joom-box btn btn-small');
            inner.attr('href', '');
            inner.attr('rel', "{handler: 'iframe', size: {x: 950, y: 500}}");
            inner.html('<img src="' + redSHOP.RSConfig._('SITE_URL') + 'media/com_redshop/images/media16.png" alt="" />');
            c[i].append(inner.clone());
            inner.html('<img src="' + redSHOP.RSConfig._('SITE_URL') + 'media/com_redshop/images/discountmanagmenet16.png" alt="" />');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'subattribute_color_name');
            c[i].attr('class', 'td span2');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'oprand');
            c[i].attr('class', 'td span1');
            c[i].html('n/a');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'subattribute_color_price');
            c[i].attr('class', 'td span1');
            c[i].html('0');
            papa.append(c[i]);
            i++;
            //

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'setdisplay_type');
            c[i].attr('class', 'td span1');
            c[i].html('dropdown');
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'setdefault_selected');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('span'));
            inner.attr('class', 'icon-checkbox-unchecked');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'subattribute_published');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('span'));
            inner.attr('class', 'icon-checkbox-unchecked');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('data-content', 'hide');
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('span'));
            inner.attr('class', 'icon-checkbox-unchecked');
            c[i].append(inner.clone());
            papa.append(c[i]);
            i++;

            c[i] = jQuery(document.createElement('div'));
            c[i].attr('class', 'td span1');
            inner = jQuery(document.createElement('div'));
            inner.attr('class', 'btn-edit-inrow');
            subInner = jQuery(document.createElement('span'));
            subInner.attr('class', 'icon-minus btn-functionality zoom');
            inner.append(subInner.clone());
            subInner.attr('class', 'icon-edit btn-functionality zoom');
            subInner.attr('attribute-id', res.attribute_id);
            subInner.attr('property-id', res.property_id);
            inner.prepend(subInner.clone());
            c[i].append(inner);
            papa.append(c[i]);
            i++;

        default:
            break;
    }
}

/**
 * Helper for dynamic change data in row of attribute
 */
const assignRowAttributeData = (element, t, rpData, res) => {
    let target;
    let imgSrc;

    element.attr('data', rpData);
    element.attr('dependency', res.dependency);

    if (res.queryType == 'insert') {
        calculateRemainingChild(element, '+');
    }

    switch (t) {
        case 'subproperty':
            if (res.subattribute_color_image != undefined) {
                element.find('div[data-content=subattribute_color_image]')
                    .find('img').show()
                    .attr('src', redSHOP.RSConfig._('REDSHOP_FRONT_IMAGES_ABSPATH') + 'subcolor/' + res.subattribute_color_image);
            }

            let attribute_id = element.find('span.icon-edit').attr('attribute-id');

            if (attribute_id == undefined) {
                attribute_id = jQuery('div.div_subproperties').find('div[target=new_subproperty]').attr('attribute-id');
                element.find('span.icon-edit').attr('attribute-id', attribute_id);
            }

            element.attr('data-id', res.subattribute_color_id);
            element.find('span.icon-edit').attr('property-id', res.subattribute_id);

            element.find('div[data-content=subattribute_color_id]').html('<b>' + res.subattribute_color_id + '</b>');
            element.find('div[data-content=subattribute_color_name]').html(res.subattribute_color_name);
            element.find('div[data-content=oprand]').html(res.oprand);
            element.find('div[data-content=subattribute_color_price]').html(res.subattribute_color_price);
            element.find('div[data-content=ordering]').html(res.ordering);
            changeStickState(element, 'setdefault_selected', res.setdefault_selected);
            changeStickState(element, 'subattribute_published', res.subattribute_published);
            changeStickState(element, 'hide', res.hide);

            element.css('background-color', '#ffcd9e');

            target = jQuery('#new_' + t);
            imgSrc = redSHOP.RSConfig._('REDSHOP_FRONT_IMAGES_ABSPATH') + 'subcolor/' + res.subattribute_color_image;
            jQuery(target).find('.rs-media-cropper-btn').parent().remove();
            jQuery(target).find('.rs-media-remove-btn').click();
            jQuery(target).find('div[data-content=propduct-image-lbl]').find('img').attr('src', imgSrc);

            mediaBox = element.find('a.joom-box').first();
            priceBox = element.find('a.joom-box').last();
            mediaBox.attr('href', redSHOP.RSConfig._('SITE_URL') + 'administrator/index.php?tmpl=component&option=com_redshop&view=media&section_id=' + res.subattribute_color_id + '&showbuttons=1&media_section=subproperty');
            priceBox.attr('href', redSHOP.RSConfig._('SITE_URL') + 'administrator/index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' + res.subattribute_color_id + '&cid=' + getProductId() + '&section=subproperty');
            mediaBox.click(function(e) {
                e.preventDefault();
                SqueezeBox.assign(this, {
                    handler: 'iframe',
                    size: {
                        x: 950,
                        y: 500
                    }
                });
            });
            priceBox.click(function(e) {
                e.preventDefault();
                SqueezeBox.assign(this, {
                    handler: 'iframe',
                    size: {
                        x: 950,
                        y: 500
                    }
                });
            });

            break;
        case 'property':
            if (res.property_image != undefined) {
                element.find('div[data-content=property_image]')
                    .find('img').show()
                    .attr('src', redSHOP.RSConfig._('REDSHOP_FRONT_IMAGES_ABSPATH') + 'product_attributes/' + res.property_image);
            }

            element.attr('data-id', res.property_id);
            element.find('div[data-content=property_id]').html('<b>' + res.property_id + '</b>');
            element.find('div[data-content=property_name]').html(res.property_name);
            element.find('div[data-content=property_number]').html(Joomla.JText._('COM_REDSHOP_PROPERTY_NUMBER') + ':' + res.property_number);

            element.find('div[data-content=setdisplay_type]').html(res.setdisplay_type);
            element.find('div[data-content=oprand]').html(res.oprand);
            element.find('div[data-content=property_price]').html(res.property_price);
            element.find('div[data-content=ordering]').html(res.ordering);
            changeStickState(element, 'setdefault_selected', res.setdefault_selected);
            changeStickState(element, 'setrequire_selected', res.setrequire_selected);
            changeStickState(element, 'property_published', res.property_published);
            changeStickState(element, 'setmulti_selected', res.setmulti_selected);
            changeStickState(element, 'hide', res.hide);

            element.css('background-color', '#ffcd9e');

            target = jQuery('#new_' + t);
            imgSrc = redSHOP.RSConfig._('REDSHOP_FRONT_IMAGES_ABSPATH') + 'product_attributes/' + res.property_image;
            jQuery(target).find('.rs-media-cropper-btn').parent().remove();
            jQuery(target).find('.rs-media-remove-btn').click();
            jQuery(target).find('div[data-content=propduct-image-lbl]').find('img').attr('src', imgSrc);

            mediaBox = element.find('a.joom-box').first();
            priceBox = element.find('a.joom-box').last();
            mediaBox.attr('href', redSHOP.RSConfig._('SITE_URL') + 'administrator/index.php?tmpl=component&option=com_redshop&view=media&section_id=' + res.property_id + '&showbuttons=1&media_section=property');
            priceBox.attr('href', redSHOP.RSConfig._('SITE_URL') + 'administrator/index.php?tmpl=component&option=com_redshop&view=attributeprices&section_id=' + res.property_id + '&cid=' + getProductId() + '&section=property');
            mediaBox.click(function(e) {
                e.preventDefault();
                SqueezeBox.assign(this, {
                    handler: 'iframe',
                    size: {
                        x: 950,
                        y: 500
                    }
                });
            });
            priceBox.click(function(e) {
                e.preventDefault();
                SqueezeBox.assign(this, {
                    handler: 'iframe',
                    size: {
                        x: 950,
                        y: 500
                    }
                });
            });
            // <?php JHtml::_('behavior.modal', 'a.joom-box'); ?>
            break;
        case 'attribute':
        default:
            element.attr('data-id', res.attribute_id);
            element.attr('dependency', res.dependency);
            element.find('div[data-content=attribute_id]').html('<b>' + res.attribute_id + '</b>');
            element.find('div[data-content=attribute_name]').html(res.attribute_name);
            element.find('div[data-content=display_type]').html(res.display_type);
            element.find('div[data-content=ordering]').html(res.ordering);
            changeStickState(element, 'attribute_required', res.attribute_required);
            changeStickState(element, 'allow_multiple_selection', res.allow_multiple_selection);
            changeStickState(element, 'attribute_published', res.attribute_published);
            changeStickState(element, 'hide', res.hide);

            element.css('background-color', '#ffcd9e');

            break;
    }
}

/**
 * Helper for change stick icon
 */
const changeStickState = (o, e, v) => {
    if (v == "1") {
        o.find('div[data-content=' + e + ']').first().find('span').attr('class', 'icon-checkbox-checked');
    } else {
        o.find('div[data-content=' + e + ']').first().find('span').attr('class', 'icon-checkbox-unchecked');
    }
};

/**
 * Help for JSON.stringify return map objects
 * @returns {function(*, *=): *}
 */
const getCircularReplacer = () => {
    const seen = new WeakSet();
    return (key, value) => {
        if (typeof value === "object" && value !== null) {
            if (seen.has(value)) {
                return;
            }
            seen.add(value);
        }
        return value;
    };
};