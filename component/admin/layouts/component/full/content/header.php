<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$toolbar = JToolbar::getInstance('toolbar');
?>
<div class="row">
    <div class="col-xs-12 col-sm-5">
	    <?php echo JFactory::getApplication()->JComponentTitle; ?>
    </div>
    <div class="col-xs-12 col-sm-7">
        <div class="header-toolbar pull-right">
			<?php echo $toolbar->render() ?>
        </div>
    </div>
</div>
<div class="row-fluid message-sys" id="message-sys"></div>
