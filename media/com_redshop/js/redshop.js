redSHOP=window.redSHOP||{},redSHOP.RSConfig={configStrings:{},_:function(e,t){return typeof this.configStrings[e.toUpperCase()]!="undefined"?this.configStrings[e.toUpperCase()]:t},load:function(e){for(var t in e)this.configStrings[t.toUpperCase()]=e[t];return this}},redSHOP.AjaxOrderPaymentStatusExecure=!1,redSHOP.AjaxOrderPaymentStatusCheck=function(){var e=jQuery.trim(jQuery("#order_payment_status").html())==Joomla.JText._("COM_REDSHOP_PAYMENT_STA_PAID");if(e)return!1;jQuery.ajax({url:redSHOP.RSConfig._("SITE_URL")+"index.php?option=com_redshop&view=order_detail&task=order_detail.AjaxOrderPaymentStatusCheck&tmpl=component",type:"POST",dataType:"HTML",data:{id:redSHOP.RSConfig._("orderId")}}).done(function(e){jQuery("#order_payment_status").html(e),redSHOP.AjaxOrderPaymentStatusExecure&&setTimeout("redSHOP.AjaxOrderPaymentStatusCheck()",1e4),redSHOP.AjaxOrderPaymentStatusExecure=!0}).fail(function(){console.log("error")})},jQuery(document).ready(function(e){jQuery("#order_payment_status").length>0?(setTimeout("redSHOP.AjaxOrderPaymentStatusCheck()",1e3),setTimeout("redSHOP.AjaxOrderPaymentStatusCheck()",1e4)):console.warn("Make sure you add #order_payment_status ID in order receipt template")});