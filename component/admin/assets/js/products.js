Joomla.submitform = submitform = Joomla.submitbutton = submitbutton = function (pressbutton) {
    var form = document.adminForm;

    if (pressbutton) {
        form.task.value = pressbutton;
    }

    if ((pressbutton == 'publish') || (pressbutton == 'unpublish')
        || (pressbutton == 'remove') || (pressbutton == 'copy') || (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
        form.view.value = "product_detail";
    }
    if ((pressbutton == 'assignCategory') || (pressbutton == 'removeCategory')) {
        form.view.value = "product_category";
    }

    if (pressbutton == 'remove') {
        if (confirm(Joomla.JText._('COM_REDSHOP_PRODUCT_DELETE_CONFIRM')) != true) {
            return false;
        }
    }

    try {
        form.onsubmit();
    }
    catch (e) {
    }

    form.submit();
}

function AssignTemplate() {
    var form = document.adminForm;
    if (form.boxchecked.value == 0) {
        jQuery('#product_template').val(0).trigger("liszt:updated");
        alert(Joomla.JText._('COM_REDSHOP_PLEASE_SELECT_PRODUCT'));
    } else {
        form.task.value = 'assignTemplate';
        if (confirm(Joomla.JText._('COM_REDSHOP_SURE_WANT_TO_ASSIGN_TEMPLATE'))) {
            form.submit();
        } else {
            jQuery('#product_template').val(0).trigger("liszt:updated");
        }
    }

}

function resetFilter() {
    document.getElementById('keyword').value = '';
    document.getElementById('search_field').value = 'p.title';
    document.getElementById('category_id').value = 0;
    document.getElementById('product_sort').value = 0;
}