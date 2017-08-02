<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * =============================
 * @var  array   $displayData      Display data
 * @var  object  $orderStatusLogs  Order status log
 */
extract($displayData);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3><?php echo JText::_('COM_REDSHOP_ORDER_STATUS_LOG'); ?></h3>
            </div>
            <div class="box-body">
                <ul class="timeline">
                    <?php $orderStatusLogs = array_reverse($orderStatusLogs); ?>
                    <?php foreach ($orderStatusLogs as $index => $log): ?>
                        <?php $nextLog = (isset($orderStatusLogs[$index + 1])) ? $orderStatusLogs[$index + 1] : false; ?>
                        <li class="time-label">
                            <span class="bg-green"><?php echo RedshopHelperDatetime::convertDateFormat($log->date_changed) ?></span>
                        </li>
                        <?php if (!$nextLog): ?>
                            <li>
                                <i class="fa fa-check bg-green"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header"><?php echo JText::_('COM_REDSHOP_ORDER_PLACED') ?></h3>
                                    <div class="timeline-body">
                                        <p><?php echo JText::_('COM_REDSHOP_ORDER_STATUS') ?>: <span
                                                    class="label order_status_<?php echo strtolower($log->order_status) ?>"><?php echo $log->order_status_name ?></span>
                                        </p>
                                        <?php if (empty($log->order_payment_status)): ?>
                                            <p><?php echo JText::_('COM_REDSHOP_PAYMENT_STAUS_LBL') ?>: <span
                                                        class="label order_payment_status_unpaid"><?php echo JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID') ?></span>
                                            </p>
                                        <?php else: ?>
                                            <?php $paymentName = JText::_('COM_REDSHOP_PAYMENT_STA_' . strtoupper(str_replace(' ', '_', $log->order_payment_status))); ?>
                                            <p><?php echo JText::_('COM_REDSHOP_PAYMENT_STAUS_LBL') ?>: <span
                                                        class="label order_payment_status_<?php echo strtolower($log->order_payment_status) ?>"><?php echo $paymentName ?></span>
                                            </p>
                                        <?php endif; ?>
                                        <p><?php echo $log->customer_note ?></p>
                                    </div>
                                </div>
                            </li>
                        <?php else: ?>
                            <?php if ($log->order_status != $nextLog->order_status): ?>
                                <li>
                                    <i class="fa fa-book bg-blue"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-body">
                                            <?php echo JText::_('COM_REDSHOP_ORDER_STATUS_CHANGE_TO') ?>&nbsp;<span
                                                    class="label order_status_<?php echo strtolower($log->order_status) ?>"><?php echo $log->order_status_name ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <?php if ($log->order_payment_status != $nextLog->order_payment_status): ?>
                                <?php $paymentName = JText::_('COM_REDSHOP_PAYMENT_STA_' . strtoupper(str_replace(' ', '_', $log->order_payment_status))); ?>
                                <li>
                                    <i class="fa fa-dollar bg-red"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-body">
                                            <?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_STATUS_CHANGE_TO') ?>&nbsp;<span
                                                    class="label order_payment_status_<?php echo strtolower($log->order_payment_status) ?>"><?php echo $paymentName ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <?php if (!empty($log->customer_note) && $log->customer_note != $nextLog->customer_note): ?>
                                <li>
                                    <i class="fa fa-comment bg-yellow"></i>
                                    <div class="timeline-item">
                                        <div class="timeline-body">
                                            <i><?php echo $log->customer_note ?></i>
                                        </div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
