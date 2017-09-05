<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

/**
 * $displayData extract
 *
 * @var   array  $displayData  Extra field data
 * @var   string $image        Image file name
 * @var   string $key          Image file name
 */
extract($displayData);

$user = JFactory::getUser();
?>
<?php if (!empty($image)): ?>
    <img id="plg_user_redshop_avatar_img" src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $key . '/' . $user->id . '/thumb/' . $image ?>" />
<?php endif; ?>
<?php if (!$user->guest): ?>
    <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                $("#plg_redshop_avatar_upload").change(function(){
                    $(this).closest('form').submit();
                });

                $("#plg_redshop_avatar_form").submit(function(event){
                    event.preventDefault();

                    var formData = new FormData($(this)[0]);
                    formData.append("avatar", $("#plg_redshop_avatar_upload")[0].files[0]);

                    $.ajax({
                        url: $(this).attr("action"),
                        type: "POST",
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        enctype: "multipart/form-data",
                        processData: false,
                        success: function(response) {
                           if (response.length > 0) {
                               $("img#plg_user_redshop_avatar_img").attr("src", response);
                           }
                        }
                    });

                    return false;
                });
            });
        })(jQuery);
    </script>
    <form action="<?php echo JUri::root() ?>index.php?option=com_ajax&plugin=uploadAvatar&format=raw&method=post&group=user" method="post"
          id="plg_redshop_avatar_form" enctype="multipart/form-data">
        <input type="file" name="avatar" id="plg_redshop_avatar_upload"/>
        <?php echo JHtml::_('form.token') ?>
    </form>
<?php endif;
