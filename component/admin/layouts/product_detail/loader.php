<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('a.joom-box');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

extract($displayData);

?>

<div id='loader'>
    <img src='<?php echo \JUri::root() ?>/media/com_redshop/images/reloading.gif' width='32px' height='32px' />
</div>