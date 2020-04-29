var optionAccountBillto = Joomla.getOptions('account_billto');
if (optionAccountBillto.isEdit == 1) {
    window.parent.SqueezeBox.options.closeBtn = false;
    window.parent.SqueezeBox.options.closable = false;

    setTimeout(function () {
        window.parent.location.href = optionAccountBillto.link;
    }, 2000);
}

function cancelForm(frm) {
    frm.task.value = 'cancel';
    frm.submit();
}
