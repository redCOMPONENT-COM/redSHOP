!function(a){a(document).ready(function(){a("#redSHOPAdminContainer .label-edit-inline").each(function(t,e){var n=a(e),o=a("#"+a(this).data("target")),i=a(this).data("id"),d=a(this).data("original-value");a(e).click(function(t){if("A"==t.target.nodeName)return!0;t.preventDefault(),a(this).hide("fast",function(){o.show("fast").trigger("show")})}),o.on("show",function(t){a(this).prop("disabled",!1).removeClass("disabled").focus().select()}).on("blur",function(t){a(this).hide("fast",function(){n.show("fast")})}).on("keypress",function(t){var e=t.keyCode||t.which;13==e?(t.preventDefault(),document.adminForm.task.value="ajaxInlineEdit",formData=a("#adminForm").serialize(),formData+="&id="+i,a.ajax({url:document.adminForm.action,type:"POST",data:formData,dataType:"JSON",complete:function(){o.prop("disabled",!0).addClass("disabled")}}).done(function(t){1==t?(n.find("a").length?n.find("a").text(o.val()):n.text(o.val()),a.redshopAlert(Joomla.JText._("COM_REDSHOP_SUCCESS"),Joomla.JText._("COM_REDSHOP_DATA_UPDATE_SUCCESS"))):a.redshopAlert(Joomla.JText._("COM_REDSHOP_FAIL"),Joomla.JText._("COM_REDSHOP_DATA_UPDATE_FAIL"),"danger"),o.hide("fast",function(){n.show("fast")}),document.adminForm.task.value=""})):27==e&&(o.val(d).hide("fast",function(){n.show("fast")}),document.adminForm.task.value="")})})})}(jQuery);