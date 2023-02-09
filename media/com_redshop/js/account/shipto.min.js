var optionShipTo = Joomla.getOptions('optionShipTo');

if (Joomla.getOptions('optionShipTo').isEdit == 1) {
// Tweak by Ronni START - Remove squeezebox options - conflict
//	window.parent.SqueezeBox.options.closeBtn = false;
//	window.parent.SqueezeBox.options.closable = false;

	setTimeout(function () {
		window.parent.location.href = optionShipTo.link;
	}, 1000);
}

function cancelForm(frm) {
	frm.task.value = 'cancel';
	frm.submit();
}