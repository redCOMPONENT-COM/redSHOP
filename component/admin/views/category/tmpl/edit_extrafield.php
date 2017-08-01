<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JPluginHelper::importPlugin('redshop_category');
JDispatcher::getInstance()->trigger('onRenderCategoryExtraFields', array($this->item->id));
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_FIELDS'); ?></h3>
            </div>
            <div class="box-body">
                <table class="admintable table">
                    <tr>
                        <td colspan="2">
							<?php if ($this->extraFields) : ?>
								<?php echo $this->extraFields; ?>
							<?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
