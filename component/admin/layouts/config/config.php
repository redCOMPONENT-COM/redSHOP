<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * =========================
 *
 * @var  array  $displayData List of data
 * @var  string $title       Title of this config field
 * @var  string $desc        Description of this config field.
 * @var  string $field       HTML content of field.
 * @var  string $showOn      JS condition for display this field.
 * @var  string $id          DOM ID of this field.
 */
extract($displayData);

$id = (isset($id)) ? $id : '';
?>
<?php if (!empty($showOn)): ?>
	<?php
	$showOn     = explode(':', $showOn);
	$fieldName  = $showOn[0];
	$fieldValue = $showOn[1];
	?>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                rsConfigShowOn("<?php echo $fieldName ?>", "<?php echo $fieldValue ?>", "<?php echo $id ?>-wrapper");
            });
        })(jQuery);
    </script>
<?php endif; ?>
<div class="form-group row-fluid" id="<?php echo !empty($id) ? $id . '-wrapper' : '' ?>">
    <label class="col-md-4 hasPopover" data-content="<?php echo $desc ?>" title="<?php echo $title ?>"><?php echo $title ?></label>
    <div class="col-md-8"><?php echo $field ?></div>
</div>
