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

?>
<div id="element-box">
    <div class="t">
        <div class="t">
            <div class="t"></div>
        </div>

    </div>
    <div class="m">
        <div>
            <div>
                <div>
                    <?php
                    if ($this->sync_user) {
                        echo '<font color=green>';
                        echo Text::_("COM_REDSHOP_ADDED");
                        echo ' ' . $this->sync_user . ' ';
                        echo Text::_("COM_REDSHOP_YES_SYNC");
                        echo '.</font>';
                    } else {
                        echo '<font color=green>';
                        echo Text::_('COM_REDSHOP_NO_SYNC');
                        echo '!</font>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="b">
        <div class="b">
            <div class="b"></div>

        </div>
    </div>
</div>