function switchShowVat() {
    var check = jQuery('input[name="show_price_with_vat"]:checked').val();

    if (check == undefined) {
        check = 0;
    }
    // alert(check); // For debug
    jQuery.get("index.php?option=com_ajax&module=redshop_switch_vat&format=raw&show_vat=" + check, function(data, status){
        // alert("Data: " + data + "\nStatus: " + status); // For debug

        if (status == 'success') {
            location.reload();
        }
    });
}