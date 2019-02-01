<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$categories    = $this->model->countCategories();
$products      = $this->model->countProducts();
$shopperGroups = $this->model->countShopperGroups();
$users         = $this->model->countUsers();
$manufacturers = $this->model->countManufacturers();
$orderStatuses = $this->model->countOrderStatuses();
$orders        = $this->model->countOrders();
?>

<?php if (!$this->checkVirtuemart): ?>
    <div class="row">
        <div class="alert alert-danger">
            <p><?php echo JText::_("COM_REDSHOP_NO_VM") ?></p>
        </div>
    </div>
<?php else: ?>
<script type="text/javascript">
    var categories = <?php echo $categories; ?>;
    var products = <?php echo $products; ?>;
    var shopperGroups = <?php echo $shopperGroups; ?>;
    var users = <?php echo $users; ?>;
    var manufacturers = <?php echo $manufacturers; ?>;
    var orderStatuses = <?php echo $orderStatuses; ?>;
    var orders = <?php echo $orders; ?>;

    function syncCategories(index) {
        (function ($) {
            var next    = index + 1;
            var percent = index / categories * 100;
            var $slide = $("#category_sync");
            var $log    = $("#category_log");
            $slide.css("width", percent + "%");
            $slide.html(percent.toFixed(2) + "%");
            $slide.parent().removeClass("hidden");

            $.post(
                "index.php?option=com_redshop&task=import_vm.syncCategory&index=" + index,
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-success").text("Success").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(next + '. ' + response.name)
                            )
                    );
                }
            )
                .always(function () {
                    if (next < categories) {
                        syncCategories(next);
                    } else {
                        $slide.css("width", "100%").removeClass('progress-bar-striped active').html("<?php echo JText::_('COM_REDSHOP_IMPORT_VM_DONE') ?>")
                            .addClass("progress-bar-success");
                        syncManufacturer(0);
                    }
                })
                .fail(function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-danger").text("Fail").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(response.name + ':' + response.msg)
                            )
                    );
                });
        })(jQuery);
    }

    function syncManufacturer(index) {
        (function ($) {
            var next    = index + 1;
            var percent = index / manufacturers * 100;
            var $slide = $("#manufacturer_sync");
            var $log    = $("#manufacturer_log");
            $slide.css("width", percent + "%");
            $slide.html(percent.toFixed(2) + "%");
            $slide.parent().removeClass("hidden");

            $.post(
                "index.php?option=com_redshop&task=import_vm.syncManufacturer&index=" + index,
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-success").text("Success").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(next + '. ' + response.name)
                            )
                    );
                }
            )
                .always(function () {
                    if (next < manufacturers) {
                        syncManufacturer(next);
                    } else {
                        $slide.css("width", "100%").removeClass('progress-bar-striped active')
                            .html("<?php echo JText::_('COM_REDSHOP_IMPORT_VM_DONE') ?>")
                            .addClass("progress-bar-success");
                        syncShopperGroup(0);
                    }
                })
                .fail(function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-danger").text("Fail").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(response.name + ':' + response.msg)
                            )
                    );
                });
        })(jQuery);
    }

    function syncShopperGroup(index) {
        (function ($) {
            var next    = index + 1;
            var percent = index / shopperGroups * 100;
            var $slide = $("#shoppergroup_sync");
            var $log    = $("#shoppergroup_log");
            $slide.css("width", percent + "%");
            $slide.html(percent.toFixed(2) + "%");
            $slide.parent().removeClass("hidden");

            $.post(
                "index.php?option=com_redshop&task=import_vm.syncShopperGroup&index=" + index,
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-success").text("Success").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(next + '. ' + response.name)
                            )
                    );
                }
            )
                .always(function () {
                    if (next < shopperGroups) {
                        syncShopperGroup(next);
                    } else {
                        $slide.css("width", "100%").removeClass('progress-bar-striped active')
                            .html("<?php echo JText::_('COM_REDSHOP_IMPORT_VM_DONE') ?>")
                            .addClass("progress-bar-success");
                        syncUser(0);
                    }
                })
                .fail(function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-danger").text("Fail").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(response.name + ':' + response.msg)
                            )
                    );
                });
        })(jQuery);
    }

    function syncUser(index) {
        (function ($) {
            var next    = index + 1;
            var percent = index / users * 100;
            var $slide = $("#customer_sync");
            var $log    = $("#customer_log");
            $slide.css("width", percent + "%");
            $slide.html(percent.toFixed(2) + "%");
            $slide.parent().removeClass("hidden");

            $.post(
                "index.php?option=com_redshop&task=import_vm.syncUser&index=" + index,
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-success").text("Success").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(next + '. ' + response.name)
                            )
                    );
                }
            )
                .always(function () {
                    if (next < users) {
                        syncUser(next);
                    } else {
                        $slide.css("width", "100%").removeClass('progress-bar-striped active')
                            .html("<?php echo JText::_('COM_REDSHOP_IMPORT_VM_DONE') ?>")
                            .addClass("progress-bar-success");
                        syncOrderStatus(0);
                    }
                })
                .fail(function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-danger").text("Fail").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(response.name + ':' + response.msg)
                            )
                    );
                });
        })(jQuery);
    }

    function syncOrderStatus(index) {
        (function ($) {
            var next    = index + 1;
            var percent = index / orderStatuses * 100;
            var $slide  = $("#orderstatus_sync");
            var $log    = $("#orderstatus_log");
            $slide.css("width", percent + "%");
            $slide.html(percent.toFixed(2) + "%");
            $slide.parent().removeClass("hidden");

            $.post(
                "index.php?option=com_redshop&task=import_vm.syncOrderStatus&index=" + index,
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-success").text("Success").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(next + '. ' + response.name)
                            )
                    );
                }
            )
                .always(function () {
                    if (next < orderStatuses) {
                        syncOrderStatus(next);
                    } else {
                        $slide.css("width", "100%").removeClass('progress-bar-striped active')
                            .html("<?php echo JText::_('COM_REDSHOP_IMPORT_VM_DONE') ?>")
                            .addClass("progress-bar-success");
                        syncProduct(0);
                    }
                })
                .fail(function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-danger").text("Fail").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(response.name + ':' + response.msg)
                            )
                    );
                });
        })(jQuery);
    }

    function syncProduct(index) {
        (function ($) {
            var next    = index + 1;
            var percent = index / products * 100;
            var $slide  = $("#product_sync");
            var $log    = $("#product_log");
            $slide.css("width", percent + "%");
            $slide.html(percent.toFixed(2) + "%");
            $slide.parent().removeClass("hidden");

            $.post(
                "index.php?option=com_redshop&task=import_vm.syncProduct&index=" + index,
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-success").text("Success").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(next + '. ' + response.name)
                            )
                    );
                }
            )
                .always(function () {
                    if (next < products) {
                        syncProduct(next);
                    } else {
                        $slide.css("width", "100%").removeClass('progress-bar-striped active')
                            .html("<?php echo JText::_('COM_REDSHOP_IMPORT_VM_DONE') ?>")
                            .addClass("progress-bar-success");
                        syncOrder(0);
                    }
                })
                .fail(function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-danger").text("Fail").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(response.name + ':' + response.msg)
                            )
                    );
                });
        })(jQuery);
    }

    function syncOrder(index) {
        (function ($) {
            var next    = index + 1;
            var percent = index / orders * 100;
            var $slide  = $("#order_sync");
            var $log    = $("#order_log");
            $slide.css("width", percent + "%");
            $slide.html(percent.toFixed(2) + "%");
            $slide.parent().removeClass("hidden");

            $.post(
                "index.php?option=com_redshop&task=import_vm.syncOrder&index=" + index,
                {
                    "<?php echo JSession::getFormToken() ?>": 1
                },
                function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-success").text("Success").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(next + '. ' + response.name)
                            )
                    );
                }
            )
                .always(function () {
                    if (next < orders) {
                        syncOrder(next);
                    } else {
                        $slide.css("width", "100%").removeClass('progress-bar-striped active')
                            .html("<?php echo JText::_('COM_REDSHOP_IMPORT_VM_DONE') ?>")
                            .addClass("progress-bar-success");
                    }
                })
                .fail(function (response) {
                    response = $.parseJSON(response);
                    $log.append(
                        $('<p>')
                            .append(
                                $('<span>').addClass("label label-danger").text("Fail").css("margin-right", "10px")
                            )
                            .append(
                                $('<span>').text(response.name + ':' + response.msg)
                            )
                    );
                });
        })(jQuery);
    }
</script>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("#start_import").click(function (e) {
                e.preventDefault();

                $("#category_log").html('');
                $("#category_sync").css("width", "0%").removeClass('progress-bar-striped active');

                $("#manufacturer_log").html('');
                $("#manufacturer_sync").css("width", "0%").removeClass('progress-bar-striped active');

                $("#shoppergroup_log").html('');
                $("#shoppergroup_sync").css("width", "0%").removeClass('progress-bar-striped active');

                $("#customer_log").html('');
                $("#customer_sync").css("width", "0%").removeClass('progress-bar-striped active');

                $("#orderstatus_log").html('');
                $("#orderstatus_sync").css("width", "0%").removeClass('progress-bar-striped active');

                $("#product_log").html('');
                $("#product_sync").css("width", "0%").removeClass('progress-bar-striped active');

                $("#order_log").html('');
                $("#order_sync").css("width", "0%").removeClass('progress-bar-striped active');

                syncCategories(0);
            });
        });
    })(jQuery);
</script>
<style type="text/css">
    .table-import-vm td { vertical-align: top !important; }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="btn btn-primary btn-large" id="start_import">
			<?php echo JText::_('COM_REDSHOP_IMPORT_VM_START') ?>
        </div>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-import-vm">
            <thead>
            <tr>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_IMPORT_VM_DATA') ?></th>
                <th width="10%"><?php echo JText::_('COM_REDSHOP_IMPORT_VM_COUNT') ?></th>
                <th><?php echo JText::_('COM_REDSHOP_IMPORT_VM_PROGRESS') ?></th>
                <th width="40%"><?php echo JText::_('COM_REDSHOP_IMPORT_VM_RESULT') ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo JText::_('COM_REDSHOP_CATEGORY') ?></td>
                <td><?php echo $this->model->countCategories() ?></td>
                <td>
                    <div class="progress hidden">
                        <div id="category_sync" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
                        </div>
                    </div>
                </td>
                <td>
                    <div id="category_log" class="well well-small"></div>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_REDSHOP_MANUFACTURER') ?></td>
                <td><?php echo $this->model->countManufacturers() ?></td>
                <td>
                    <div class="progress hidden">
                        <div id="manufacturer_sync" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
                        </div>
                    </div>
                </td>
                <td>
                    <div id="manufacturer_log" class="well well-small"></div>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP') ?></td>
                <td><?php echo $this->model->countShopperGroups() ?></td>
                <td>
                    <div class="progress hidden">
                        <div id="shoppergroup_sync" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
                        </div>
                    </div>
                </td>
                <td>
                    <div id="shoppergroup_log" class="well well-small"></div>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_REDSHOP_CUSTOMER') ?></td>
                <td><?php echo $this->model->countUsers() ?></td>
                <td>
                    <div class="progress hidden">
                        <div id="customer_sync" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
                        </div>
                    </div>
                </td>
                <td>
                    <div id="customer_log" class="well well-small"></div>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_REDSHOP_ORDER_STATUS') ?></td>
                <td><?php echo $this->model->countOrderStatuses() ?></td>
                <td>
                    <div class="progress hidden">
                        <div id="orderstatus_sync" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
                        </div>
                    </div>
                </td>
                <td>
                    <div id="orderstatus_log" class="well well-small"></div>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_REDSHOP_PRODUCT') ?></td>
                <td><?php echo $this->model->countProducts() ?></td>
                <td>
                    <div class="progress hidden">
                        <div id="product_sync" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
                        </div>
                    </div>
                </td>
                <td>
                    <div id="product_log" class="well well-small"></div>
                </td>
            </tr>
            <tr>
                <td><?php echo JText::_('COM_REDSHOP_ORDER') ?></td>
                <td><?php echo $this->model->countOrders() ?></td>
                <td>
                    <div class="progress hidden">
                        <div id="order_sync" class="progress-bar progress-bar-striped active" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
                        </div>
                    </div>
                </td>
                <td>
                    <div id="order_log" class="well well-small"></div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
	<?php endif; ?>
</div>
