redSHOP=window.redSHOP||{},redSHOP.RSConfig={configStrings:{},_:function(e,t){return"undefined"!=typeof this.configStrings[e.toUpperCase()]?this.configStrings[e.toUpperCase()]:t},load:function(e){for(var t in e)this.configStrings[t.toUpperCase()]=e[t];return this}},redSHOP.AjaxOrderPaymentStatusExecuted=!1,redSHOP.AjaxOrderPaymentStatusCheck=function(){var e=jQuery.trim(jQuery("#order_payment_status").html())==Joomla.JText._("COM_REDSHOP_PAYMENT_STA_PAID");return!e&&void jQuery.ajax({url:redSHOP.RSConfig._("SITE_URL")+"index.php?option=com_redshop&view=order_detail&task=order_detail.AjaxOrderPaymentStatusCheck&tmpl=component",type:"POST",dataType:"HTML",data:{id:redSHOP.RSConfig._("orderId")}}).done(function(e){jQuery("#order_payment_status").html(e),redSHOP.AjaxOrderPaymentStatusExecuted&&setTimeout("redSHOP.AjaxOrderPaymentStatusCheck()",1e4),redSHOP.AjaxOrderPaymentStatusExecuted=!0}).fail(function(){console.log("error")})},redSHOP.prepareStateList=function(e,t){jQuery.ajax({url:redSHOP.RSConfig._("AJAX_BASE_URL"),type:"POST",dataType:"json",data:{view:"search",task:"getStatesAjax",country:e.val()}}).done(function(e){t.empty(),jQuery("#div_state_txt").hide(),t.parent().hide(),t.hide(),e.length&&(jQuery("#div_state_txt").show(),t.parent().show(),jQuery("#s2id_"+t.attr("id")).length||t.show()),jQuery.each(e,function(e,r){t.append(jQuery("<option></option>").attr("value",r.value).text(r.text))}),t.trigger("change.select2")}).fail(function(){console.log("Error getting state list.")})},jQuery(document).ready(function(e){jQuery(location).attr("search").match(/&layout=receipt/)&&(jQuery("#order_payment_status").length>0?(setTimeout("redSHOP.AjaxOrderPaymentStatusCheck()",1e3),setTimeout("redSHOP.AjaxOrderPaymentStatusCheck()",1e4)):console.warn("Make sure you add #order_payment_status ID in order receipt template")),jQuery(document).on("change",'select[id^="rs_country_"]',function(){redSHOP.prepareStateList(jQuery(this),jQuery("#"+jQuery(this).attr("stateid")))})});