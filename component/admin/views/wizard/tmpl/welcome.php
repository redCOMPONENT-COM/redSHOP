<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
?>
<div class="welcome_wizard">
    <div class="wizard_intro_text"><?php echo JText::_('COM_REDSHOP_WELCOME_WIZARD_INTRO_TEXT'); ?></div>
    <div>&nbsp;</div>
	<?php
	$doc_link        = '<a href="http://wiki.redcomponent.com" target="_blank">website</a>';
	$forum_link      = '<a href="http://redcomponent.com/forum/54-redshop" target="_blank">redSHOP - redCOMPONENT Forum</a>';
	$contact_link    = '<a href="http://redcomponent.com" target="_blank">redCOMPONENT</a>';
	$learn_more_link = '<a href="http://www.redcomponent.com" target="_blank">redcomponent.com</a>';

	?>
    <div
            class="wizard_redshop_feature"><?php echo sprintf(JText::_('COM_REDSHOP_WELCOME_REDSHOP_FEATURE_TEXT'), $doc_link, $forum_link, $contact_link, $learn_more_link); ?></div>
    <div>&nbsp;</div>
    <div class="wizard_basic_info"><?php echo JText::_('COM_REDSHOP_WIZARD_BASIC_INFO_TEXT'); ?></div>
    <div>&nbsp;</div>
</div>
<div>
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
        <tr>
            <td valign="top" width="270px">
                <img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>261-x-88.png" width="261" height="88"
                     alt="redSHOP Logo" align="left">
            </td>
            <td valign="top">
                <strong><?php echo JText::_('COM_REDSHOP_COMPONENT_NAME'); ?></strong><br/>
                <font class="small"><?php echo JText::_('COM_REDSHOP_BY_LINK'); ?><br/></font>
                <font class="small"><?php echo JText::_('COM_REDSHOP_TERMS_AND_CONDITION'); ?></font>

                <p><?php echo JText::_('COM_REDSHOP_CHECK_UPDATES'); ?>:
                    <a href="http://redcomponent.com/" target="_new">
                        <img src="http://images.redcomponent.com/redcomponent.jpg" alt="">
                    </a>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <form action="?option=com_redshop" method="POST" name="installform" id="installform">
                    <input type="hidden" name="step" value="1"/>
                    <input type="hidden" name="go" value=""/>
                </form>
            </td>
        </tr>
    </table>
</div>
