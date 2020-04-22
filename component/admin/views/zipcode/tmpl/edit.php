<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$uri = JURI::getInstance();
$url = $uri->root();

echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this));
?>

<script>
    function getState2Code()
    {
        var filterData = {};
        filterData['<?php echo JSession::getFormToken(); ?>'] = 1;
        filterData['country_code'] = jQuery('#jform_country_code').val();
        jQuery.ajax({
            url: 'index.php?option=com_redshop&task=zipcode.ajaxGetState2Code',
            data: filterData,
            type: 'POST',
            dataType: 'text',
            beforeSend: function (xhr) {
                jQuery('#stateCodeBox').addClass('opacity-40');
                jQuery('#stateCodeBox .spinner').show();
            }
        }).done(function (data) {
            jQuery('#jform_state_code').html(data);
            jQuery('#jform_state_code').select2({ width: 'resolve' });
            jQuery('#jform_state_code').val("<?php echo $this->item->state_code ?>").trigger('change');
            jQuery('#jform_state_code').select2
        });
    }

    jQuery(window).load(function() {
        filterData = jQuery('#jform_country_code').val();

        if (filterData != '') {
            getState2Code();
        }
    })
</script>
