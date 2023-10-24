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
<legend class="no-border text-danger">
    <?php echo Text::_('COM_REDSHOP_PERFORMANCE_SETTING'); ?>
</legend>
<?php
echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => Text::_('COM_REDSHOP_ENABLE_PERFORMANCE_MODE'),
        'desc'  => Text::_('COM_REDSHOP_TOOLTIP_PERFORMANCE_SETTING'),
        'field' => $this->lists['enable_performance_mode']
    )
);