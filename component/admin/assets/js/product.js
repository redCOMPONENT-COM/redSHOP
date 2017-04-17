function add_dependency(type_id, tag_id, product_id) {
    var request;
    request = getHTTPObject();
    var arry_sel = new Array();
    if (document.getElementById('sel_dep' + type_id + '_' + tag_id)) {
        var j = 0;
        var selVal = document.getElementById('sel_dep' + type_id + '_' + tag_id);
        for (var i = 0; i < selVal.options.length; i++)
            if (selVal.options[i].selected)
                arry_sel[j++] = selVal.options[i].value;
    }
    var dependent_tags = "";
    dependent_tags = arry_sel.join(",");
    if (document.getElementById('product_id'))
        product_id = document.getElementById('product_id').value;
    var args = "dependent_tags=" + dependent_tags + "&product_id=" + product_id + "&type_id=" + type_id + "&tag_id=" + tag_id;
    var url = "index.php?tmpl=component&option=com_redproductfinder&task=associations.savedependent&" + args;

    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            alert(request.responseText);
        }
    };
    request.open("GET", url, true);
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    request.send(null);
}

function hideDownloadLimit(val) {
    var downloadlimit = document.getElementById('download_limit');
    var downloaddays = document.getElementById('download_days');
    var downloadclock = document.getElementById('download_clock');

    if (val.value == 1) {

        downloadlimit.style.display = 'none';
        downloaddays.style.display = 'none';
        downloadclock.style.display = 'none';
    } else {

        downloadlimit.style.display = 'table-row';
        downloaddays.style.display = 'table-row';
        downloadclock.style.display = 'table-row';
    }

}

function set_dynamic_field(tid, pid, sid) {
    var form = document.adminForm;
    form.template_id.value = tid;
    form.section_id.value = sid;
    form.task.value = "getDynamicFields";
    form.submit();
}

function changeProductDiv(product_type) {
    document.getElementById("div_file").style.display = "none";
    document.getElementById("div_subscription").style.display = "none";
    var opendiv = document.getElementById("div_" + product_type);
    opendiv.style.display = 'block';

    if (product_type == 'file') {
        document.getElementById("product_download1").checked = true;
    }
    else {
        document.getElementById("product_download1").checked = false;
    }
}

function showBox(div) {
    var opendiv = document.getElementById(div);

    if (opendiv.style.display == 'block') opendiv.style.display = 'none';
    else opendiv.style.display = 'block';
    return false;
}

function jimage_insert(main_path, fid, fsec) {

    var path_url = "<?php echo JURI::getInstance()->root();?>";
    var propimg;

    if (!fid && !fsec) {

        if (main_path) {
            var elImageDisplay = redSHOP.RSConfig._('SITE_URL');

            // Make sure this el exists before apply
            if (elImageDisplay !== null) {
                elImageDisplay.style.display = "block";
                elImageDisplay.src = path_url + main_path;
            }
            else {
                // It's not exists than create and append it
                elImageDisplay = document.createElement('img');
                elImageDisplay.style.display = "block";
                elImageDisplay.src = path_url + main_path;
                jQuery('#product_image').parent().append(elImageDisplay);
            }
        }
        else {
            document.getElementById("product_image").value = "";
            document.getElementById("image_display").src = "";
        }
    } else {

        if (fsec == 'property') {
            if (main_path) {
                propimg = 'propertyImage' + fid;
                document.getElementById(propimg).style.display = "block";
                document.getElementById(propimg).width = "60";
                document.getElementById(propimg).heidth = "60";
                document.getElementById("propmainImage" + fid).value = main_path;
                document.getElementById(propimg).src = path_url + main_path;


            }
            else {
                document.getElementById("propmainImage" + fid).value = "";
                document.getElementById("propimg" + fid).src = "";
            }
        } else {
            if (main_path) {

                propimg = 'subpropertyImage' + fid;
                document.getElementById(propimg).style.display = "block";
                document.getElementById(propimg).width = "60";
                document.getElementById(propimg).heidth = "60";
                document.getElementById("subpropmainImage" + fid).value = main_path;
                document.getElementById(propimg).src = path_url + main_path;


            }
            else {
                document.getElementById("subpropmainImage" + fid).value = "";
                document.getElementById("propimg" + fid).src = "";
            }
        }
    }
}