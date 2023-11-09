<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$user  = Factory::getApplication()->getIdentity();
$start = $this->pagination->limitstart;
$end   = $this->pagination->limit;

?>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table width="100%">
            <tr>
                <td><?php echo Text::_('COM_REDSHOP_FILTER') . ": " . $this->lists['filteroption']; ?></td>
            </tr>
        </table>
        <table class="adminlist table table-striped" width="100%">
            <thead>
            <tr>
                <th align="center"><?php echo Text::_('COM_REDSHOP_HASH'); ?></th>
                <?php if ($this->filteroption) { ?>
                        <th align="center"><?php echo Text::_('COM_REDSHOP_DATE'); ?></th>
                <?php } ?>
                <th align="center"><?php echo Text::_('COM_REDSHOP_TOTAL_TURNOVER'); ?></th>
            </tr>
            </thead>
            <?php
            for ($i = $start, $j = 0; $i < ($start + $end); $i++, $j++) {
                if (!isset($this->totalturnover[$i]) || !is_object($this->totalturnover[$i])) {
                    break;
                }

                $row = $this->totalturnover[$i];
                ?>
                    <tr>
                        <td align="center"><?php echo $i + 1; ?></td>
                        <?php if ($this->filteroption) { ?>
                                <td align="center"><?php echo $row->viewdate; ?></td>
                        <?php } ?>
                        <td align="center"><?php echo RedshopHelperProductPrice::formattedPrice($row->turnover); ?></td>
                    </tr>
            <?php } ?>
            <tfoot>
            <td colspan="3">
                <div class="redShopLimitBox">
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <?php echo $this->pagination->getListFooter(); ?></td>
            </tfoot>
        </table>
    </div>
    <input type="hidden" name="view" value="statistic"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="layout" value="<?php echo $this->layout; ?>"/>
</form>
