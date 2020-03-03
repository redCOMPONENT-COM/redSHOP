Joomla.submitbutton = function (pressbutton) {
    var form = document.adminForm;

    if (pressbutton) {
        form.task.value = pressbutton;

    }

    form.submit();
}

function insertProduct(pid) {
    //	var alt = document.getElementById("alt").value;

    var tag = "{redshop:" + pid + "}";

    window.parent.jInsertEditorText(tag, '<?php echo $eName; ?>');
    window.parent.SqueezeBox.close();
    return false;
}