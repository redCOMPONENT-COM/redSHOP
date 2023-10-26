<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

?>
<div class="category-detail-wrapper">
    <?php echo sprintf(Text::_('COM_REDSHOP_CATEGORY_IS_NOT_PUBLISHED'), $this->maincat->name, $this->maincat->id) ?>
</div>