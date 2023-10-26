<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('bootstrap.tooltip', '.hasTooltip');

/**
 * $displayData extract
 *
 * @var   array   $displayData Layout data
 * @var   object  $form        A JForm object
 * @var   integer $productId   Id current product
 * @var   integer $modal       Flag use form in modal
 */

extract($displayData);

?>
<div class="form-group">
    <label>
        <?php echo Text::_($fieldHandle->title); ?>
    </label>
    <?php echo $inputField; ?>
    <?php if ($fieldHandle->required == 1): ?>
        <span class='required'>*</span>
    <?php endif; ?>
    <?php if (trim($fieldHandle->description) != ''): ?>
        <?php
        echo '&nbsp; ' . JHtml::_('redshop.tooltip', $fieldHandle->description);
        ?>
    <?php endif; ?>
</div>