<?php
/**
 * @package     Joomla.Site
 * @subpackage  MOD_REDSHOP_LOGIN
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Tweak by Ronni - Move keepalive.js to template.js
// JHtml::_('behavior.keepalive');
?>
<form action="<?php echo Redshop\IO\Route::_('index.php', true, $params->get('usesecure', 0)); ?>" method="post" id="login-form" class="form-vertical">
<?php if ($params->get('greeting', 1)) : ?>
	<div class="login-greeting">
	<?php if (!$params->get('name', 0)) : ?>
		<?php echo JText::sprintf('MOD_REDSHOP_LOGIN_HINAME', htmlspecialchars($user->get('name'), ENT_COMPAT, 'UTF-8')); ?>
	<?php else : ?>
		<?php echo JText::sprintf('MOD_REDSHOP_LOGIN_HINAME', htmlspecialchars($user->get('username'), ENT_COMPAT, 'UTF-8')); ?>
	<?php endif; ?>
	</div>
<?php endif; ?>
<?php if ($params->get('profilelink', 0)) : ?>
	<ul class="unstyled">
		<li>
			<a href="<?php echo Redshop\IO\Route::_('index.php?option=com_users&view=profile'); ?>">
			<?php echo JText::_('MOD_REDSHOP_LOGIN_PROFILE'); ?></a>
		</li>
	</ul>
<?php endif; ?>
<?php /* // Tweak by Ronni START - Add account + old orders */ ?>
	<div align="center"> <?php
		$accountPage = JRoute::_('index.php?option=com_redshop&view=account'); ?>
        <input align="center" type="button" class="btn" 
                style="margin-bottom: 5px!important;margin-top: -13px; width:-webkit-fill-available" 
                onclick="location.href='<?php echo $accountPage; ?>';" 
                value="<?php echo JText::_('COM_REDSHOP_MY_ACCOUNT'); ?>" />
		<br> <?php
		$ordersPage = JRoute::_('index.php?option=com_redshop&view=orders'); ?>
		<input type="button" class="btn" style="margin-bottom:5px!important;width:-webkit-fill-available" 
                onclick="location.href='<?php echo $ordersPage; ?>';" 
                value="<?php echo JText::_('COM_REDSHOP_OLD_ORDERS'); ?>" />
		<br>
	</div>
    <?php /* // Tweak by Ronni END - Add account + old orders */ ?>
	<?php /* // Tweak by Ronni - Add align="left" + margin-top:3px + change button to class="btn button" */ ?>
    <div id="form-login-submit" class="control-group" style="margin-top:10px;margin-bottom:15px">
        <div class="controls">
		    <input type="submit" name="Submit" class="btn btn-primary login-button" style="width:auto" 
                    value="<?php echo JText::_('JLOGOUT'); ?>" />
    		<input type="hidden" name="option" value="com_users" />
	    	<input type="hidden" name="task" value="user.logout" />
		    <input type="hidden" name="return" value="<?php echo $return; ?>" />
		    <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
    <?php /* // Tweak by Ronni START - Add Lanuage button */ ?>
    <div class="lang-change-login" align="right"> <?php
        $languages = JLanguageHelper::getLanguages();
            
        if ($languages) {
            foreach($languages as $language) {
                $language->active = '';
                    
                if (isset($_SESSION['language_get']) && $_SESSION['language_get'] != '') {
                    if ($_SESSION['language_get'] == $language->lang_code) {
                        $language->active = "class='lang-flag-active'";
                    }
                }

                echo "<span class='hasPopover' style='margin-left:5px' title='" 
                            . JText::_('MOD_LOGIN_LANG_SWITCH') . "' data-content='" 
                            . JText::_('MOD_LOGIN_LANG_INFO') . "'>
                        <span " . $language->active . ">
                            <a href='javascript:;' onclick='javascript:setLanguage(\"" 
                                    . $language->lang_code . "\");'>
                                <img src='/media/mod_languages/images/" . $language->image 
                                        . ".png' alt='Language image' width='18' height='12'>&nbsp;&nbsp;
                            </a>
                        </span>
                      </span>";
            }
        } ?>
    </div>
    <div> <?php
        $BrowserLangTrim = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if ($BrowserLangTrim[0] != "da-DK") {
            echo "<div class='price_box price_box_orange display-kanvas' 
                        style='margin-top:20px;margin-bottom:-10px;display:block'>
                    <span style='font-size:14px;line-height:21px'>
                        <span class='fa fa-info-circle'></span>
                        You can switch most of the language to English here.
                    </span>
                  </div>";
        } ?>
    </div>
    <?php /* Tweak by Ronni END - Add Lanuage button */ ?>
</form>
<?php /* Tweak by Ronni START - Add script for Lanuage button */ ?>
<form name="language_get_form" action="" method="post">
    <input type="hidden" name="language_get" value="" />
</form>
<script>
    function setLanguage(lan) {
        document.language_get_form.language_get.value = lan;
        document.language_get_form.submit();
    }
</script>
<?php /* Tweak by Ronni END - Add script for Lanuage button */ ?>