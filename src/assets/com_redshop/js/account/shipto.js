var optionShipTo = Joomla.getOptions('optionShipTo');

if (Joomla.getOptions('optionShipTo').isEdit == 1) {
	window.parent.SqueezeBox.options.closeBtn = false;
	window.parent.SqueezeBox.options.closable = false;

	setTimeout(function () {
		window.parent.location.href = optionShipTo.link;
	}, 2000);
}

function cancelForm(frm) {
	frm.task.value = 'cancel';
	frm.submit();
}