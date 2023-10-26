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

HTMLHelper::_('bootstrap.tooltip', '.hasTooltip');

$now = JFactory::getDate();

?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
			Joomla.submitform(pressbutton);
            return;
        }

        if (form.tags_name.value == "") {
            alert("<?php echo Text::_('COM_REDSHOP_TAGS_NAME_MUST_FILLED', true); ?>");
        } else {
			Joomla.submitform(pressbutton);
        }
    }
</script>

<form action="<?php echo Redshop\IO\Route::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
    <div class="col50">
        <fieldset class="adminform">
            <legend><?php echo Text::_('COM_REDSHOP_DETAILS'); ?></legend>
            <table class="admintable table">
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="name">
                            <?php echo Text::_('COM_REDSHOP_TAGS_NAME'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="tags_name" id="tags_name" size="32" maxlength="250"
                               value="<?php echo $this->detail->tags_name; ?>"/>
                        <?php echo JHtml::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_TAGS_NAME'),
                            Text::_('COM_REDSHOP_TAGS_NAME')
                        ); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right" class="key">
                        <?php echo Text::_('COM_REDSHOP_PUBLISHED'); ?>:
                    </td>
                    <td>
                        <?php echo $this->lists['published']; ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="clr"></div>
    <input type="hidden" name="cid[]" value="<?php echo $this->detail->tags_id; ?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="tags_counter" value="<?php echo $this->detail->tags_counter; ?>"/>
    <input type="hidden" name="view" value="producttags_detail"/>
</form>
