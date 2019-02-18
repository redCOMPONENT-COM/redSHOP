<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            // Image clean thumb
            $("#images-clean-thumb").click(function(event){
                event.preventDefault();

                $("a").addClass("disabled").prop("disabled", true);
                $(".images-loading").show();

                $.post(
                    'index.php?option=com_redshop&task=tool_image.cleanThumbFolders',
                    {
                        "<?php echo JSession::getFormToken() ?>": 1
                    },
                    function(response){
                        $("a").removeClass("disabled").prop("disabled", false);
                        $(".images-loading").hide();
                        $(".images-status-log").html('');

                        for (i = 0; i < response.length; i++)
                        {
                            $(".images-status-log").append('<p>' + response[i] + '</p>');
                        }
                    },
                    'JSON'
                );
            });

            // Image size check
            $("#images-size-check").click(function(event){
                event.preventDefault();

                $("a").addClass("disabled").prop("disabled", true);
                $(".images-loading").show();
                $(".images-status-log").html('');

                $.post(
                    'index.php?option=com_redshop&task=tool_image.getImages',
                    {
                        "<?php echo JSession::getFormToken() ?>": 1
                    },
                    function(response){
                        $(".images-status-log").html('<p>' + response + '</p>');
                        processImageCheck();
                    }
                );
            });
        });
    })(jQuery);
</script>
<script type="text/javascript">
    function processImageCheck()
    {
        (function($){
            $.post(
                'index.php?option=com_redshop&task=tool_image.processImageCheck',
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function(response){
                    if (response.status == 2) {
                        processImageCheck();
                    } else {
                        $("a").removeClass("disabled").prop("disabled", false);
                        $(".images-loading").hide();
                    }

                    $(".images-status-log").append('<p>' + response.msg + '</p>');
                },
                'JSON'
            );
        })(jQuery);
    }
</script>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <img class="images-loading" src="<?php echo JUri::root() ?>/components/com_redshop/assets/images/loading.gif" width="20" height="20" style="display: none" />
                    <?php echo JText::_('COM_REDSHOP_TOOLS_IMAGE_WRAPPER') ?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <a class="btn btn-block btn-app" id="images-size-check" href="javascript:void(0);">
                            <i class="fa fa-refresh"></i><?php echo JText::_('COM_REDSHOP_TOOLS_IMAGE_SIZE_CHECK') ?>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a class="btn btn-block btn-app" id="images-clean-thumb" href="javascript:void(0);">
                            <i class="fa fa-remove"></i><?php echo JText::_('COM_REDSHOP_TOOLS_IMAGE_CLEAN_THUMBNAIL') ?>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr />
                        <div class="images-status-log well" style="height: 300px; overflow: auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
