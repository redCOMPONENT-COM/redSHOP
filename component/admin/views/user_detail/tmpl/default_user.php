<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_USER_DETAIL'); ?></h3>
    </div>
    <table class="admintable table">
        <?php
        if (!$this->silerntuser) {
        ?>
            <tr>
                <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_USERNAME'); ?>: 
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_USERNAME'),
                        Text::_('COM_REDSHOP_USERNAME')
                    ); ?>
                </td>
                <td><input class="text_area" type="text" name="username" id="username" value="<?php echo $this->detail->username; ?>" 
                        size="20" maxlength="250" />
                    <span class="star text-danger" id="user_valid"> *</span>
                </td>
            </tr>
            <tr>
                <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_NEW_PASSWORD_LBL'); ?>: 
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_PASSWORD'),
                        Text::_('COM_REDSHOP_NEW_PASSWORD_LBL')
                    ); ?>
                </td>
                <td><input class="inputbox" type="password" name="password" id="password" size="20" value="" />
                    <span class="star text-danger" id="user_valid"> *</span>
                </td>
            </tr>
            <tr>
                <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_VERIFIED_PASSWORD_LBL'); ?>: 
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_PASSWORD'),
                        Text::_('COM_REDSHOP_VERIFIED_PASSWORD_LBL')
                    ); ?>
                </td>
                <td><input class="inputbox" type="password" name="password2" id="password2" autocomplete="new-password" size="20" value="" />
                    <span class="star text-danger" id="user_valid"> *</span>
                </td>
            </tr>
        <?php
        } ?>
        <tr>
            <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_EMAIL'); ?>: 
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_EMAIL'),
                    Text::_('COM_REDSHOP_EMAIL')
                ); ?>
            </td>
            <td><input class="text_area" type="text" name="email" id="email" value="<?php echo $this->detail->email; ?>" size="20" 
                    maxlength="250" onblur="validate(2)" />
                <span class="star text-danger" id="email_valid">*</span>
            </td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_SHOPPER_GROUP_LBL'); ?>: 
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_GROUP'),
                    Text::_('COM_REDSHOP_GROUP')
                ); ?>
            </td>
            <td><?php echo $this->lists['shopper_group']; ?>
                <span class="star text-danger" id="user_valid"> *</span>
            </td>
        </tr>
        <?php if (!$this->silerntuser) {
        ?>
            <tr>
                <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_GROUP'); ?>: 
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_GROUP'),
                        Text::_('COM_REDSHOP_GROUP')
                    ); ?>
                </td>
                <td><?php echo HTMLHelper::_('access.usergroups', 'groups', $this->detail->user_groups, true); ?>
                    <span class="star text-danger" id="user_valid"> *</span>
                </td>
            </tr>
        <?php
        } ?>
        <tr>
            <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_BLOCK_USER'); ?></td>
            <td><?php echo $this->lists['block']; ?></td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_REGISTER_AS'); ?></td>
            <td><?php echo $this->lists['is_company']; ?></td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_RECEIVE_SYSTEM_EMAIL'); ?></td>
            <td><?php echo $this->lists['sendEmail']; ?></td>
        </tr>
    </table>
</div>