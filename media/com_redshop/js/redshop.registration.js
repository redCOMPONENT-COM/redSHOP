if (typeof(window['jQuery']) != "undefined") {
    var rss = jQuery.noConflict();
    var rs = jQuery.noConflict();

    rs().ready(function () {
        var hash = getUrlVars();

        function getUrlVars() {
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        rs.validator.addMethod("zipcode", function (zipcode, element) {
            return this.optional(element) || /^\d{4} ?[a-z]{2}$/i.test(zipcode) || zipcode.match(/(^\d{6}?$)|(^\d{5}?$)|(^\d{7}?$)|(^\d{4}?$)|(^\d{3}?$)|(^\d{8}?$)|(^\d{9}?$)|[A-Z]{1,2}\d[\dA-Z]?\s?\d[A-Z]{2}$/i) || zipcode.match(/^[A-Z][0-9][A-Z].[0-9][A-Z][0-9]$/) || zipcode.match(/^[A-Z][0-9][A-Z][0-9][A-Z][0-9]$/i) || zipcode.match(/^[0-9]{5}$/) || zipcode.match(/^[0-9]{2,2}\s[0-9]{3,3}$/) || zipcode.match(/^[0-9]{3,3}\s[0-9]{2,2}$/) || zipcode.match(/^[0-9]{4,4}-[0-9]{3,3}$/) || zipcode.match(/^[0-9]{3,3}-[0-9]{2,2}$/) || zipcode.match(/^[0-9]{2,2}-[0-9]{3,3}$/) || zipcode.match(/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/);
        }, Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP'));

        rs.validator.addMethod("phone", function (phone, element) {
            phone = phone.replace(/\s+/g, "");

            return this.optional(element) || phone.match(/^[-+.() 0-9]+$/);
        }, Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE'));

        rs.validator.messages.required = Joomla.JText._('COM_REDSHOP_THIS_FIELD_IS_REQUIRED');

        rs("#adminForm").validate({
            onkeyup: false, //turn off auto validate whilst typing
            rules: {
                firstname: "required",
                lastname: "required",
                username: {
                    required: function () {
                        return rs("#createaccount") && rs("#createaccount").is(":checked")
                            || (!rs("#createaccount") && rs("#username"));
                    },
                    minlength: 2,
                    remote :
                        {
                            url: "index.php?tmpl=component&option=com_redshop&view=registration&task=ajaxValidateNewJoomlaUser",
                            type: "post",
                            data: {
                                username: function() {
                                    return rs("#adminForm input[name='username']").val();
                                }
                            },
                            async: false
                        }
                },
                company_name: {
                    required: function () {
                        return rs("#toggler2").is(":checked");
                    }
                },
                vat_number: {
                    required: function () {
                        return rs("#toggler2").is(":checked") && redSHOP.RSConfig._('REQUIRED_VAT_NUMBER') == 1;
                    }
                },
                country_code: {
                    required: function () {
                        return rs("#div_country_txt") && rs("#div_country_txt").is(":visible");
                    }
                },
                state_code: {
                    required: function () {
                        return rs("#div_state_txt")
                            && rs("#div_state_txt").is(":visible");
                    }
                },
                ean_number: {
                    required: function () {
                        return rs("#toggler2").is(":checked") && rs("#ean_number").length > 0;
                    },
                    minlength: 13,
                    maxlength: 13,
                    decimal: false,
                    negative: false,
                    number: true
                },
                email1: {
                    email: true
                },
                email2: {
                    required: true,
                    equalTo: "[name=email1]:visible"
                },
                password1: {
                    required: function () {
                        return rs("#createaccount") && rs("#createaccount").is(":checked") || (rs("#user_id") && rs("#user_id").val() == 0 && rs("#password1"));
                    },
                    minlength: 5
                },
                password2: {
                    required: function () {
                        return rs("#createaccount") && rs("#createaccount").is(":checked")
                            || (rs("#user_id") && rs("#user_id").val() == 0 && rs("#password2"));
                    },
                    minlength: 5,
                    equalTo: "#password1"
                },
                topic: {
                    required: "#newsletter:checked",
                    minlength: 2
                },
                zipcode: {
                    zipcode: true
                },
                phone: {
                    required: function () {
                        return rs("input[name='phone']") && rs("input[name='phone']").is(":visible");
                    }
                },
                phone_ST: {
                    required: function () {
                        return rs("input[name='phone_ST']") && rs("input[name='phone_ST']").is(":visible");
                    }
                },
                termscondition: {
                    required: function () {
                        return rs("#termscondition") && rs("#termscondition").is(":visible");
                    }
                },
                agree: "required"
            },
            ignore: "#adminForm input:not(:visible)",
            messages: {
                company_name: Joomla.JText._('COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME'),
                firstname: Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME'),
                lastname: Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME'),
                address: Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ADDRESS'),
                zipcode: Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP'),
                city: Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_CITY'),
                phone: Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE'),
                username: {
                    required: Joomla.JText._('COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME'),
                    minlength: Joomla.JText._('COM_REDSHOP_USERNAME_MIN_CHARACTER_LIMIT'),
                    remote: Joomla.JText._('COM_REDSHOP_USERNAME_ALREADY_EXISTS')
                },
                email1: {
                    required: Joomla.JText._('COM_REDSHOP_PROVIDE_EMAIL_ADDRESS')
                },
                email2: {
                    required: Joomla.JText._('COM_REDSHOP_PROVIDE_EMAIL_ADDRESS'),
                    equalTo: Joomla.JText._('COM_REDSHOP_EMAIL_NOT_MATCH')
                },
                password1: {
                    required: Joomla.JText._('COM_REDSHOP_THIS_FIELD_IS_REQUIRED'),
                    minlength: Joomla.JText._('COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT')
                },
                password2: {
                    required: Joomla.JText._('COM_REDSHOP_THIS_FIELD_IS_REQUIRED'),
                    minlength: Joomla.JText._('COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT'),
                    equalTo: Joomla.JText._('COM_REDSHOP_PASSWORD_NOT_MATCH')
                },
                termscondition: Joomla.JText._('COM_REDSHOP_PLEASE_SELECT_TEMS_CONDITIONS'),
                agree: "Please accept our policy",
                ean_number: {
                    minlength: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT'),
                    maxlength: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT'),
                    decimal: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT'),
                    negative: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT'),
                    number: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT')
                }
            },
           /* invalidHandler: function(e,validator) {
                //validator.errorList contains an array of objects, where each object has properties "element" and "message".  element is the actual HTML Input.
                for (var i=0;i<validator.errorList.length;i++){
                    console.log(validator.errorList[i]);
                }

                //validator.errorMap is an object mapping input names -> error messages
                for (var i in validator.errorMap) {
                    console.log(i, ":", validator.errorMap[i]);
                }
            },*/
        });

        // propose username by combining first- and lastname
        rs("#username").focus(function () {
            var firstname = rs("#firstname").val();
            var lastname = rs("#lastname").val();

            if (firstname && lastname && !this.value) {
                this.value = firstname + "." + lastname;
            }
        });

        rs.validator.addMethod("billingRequired", function (value, element) {
            if (rs("#billisship").is(":checked")) {
                return rs(element).parents(".subTable").length;
            }

            return !this.optional(element);
        }, "");
    });
}
