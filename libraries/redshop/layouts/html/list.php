<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

use Joomla\Utilities\ArrayHelper;

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string  $type      Type of the list - radio or select
 * @var   array   $data      Options available for this field.
 * @var   string  $name      Name of the input field.
 * @var   array   $attribs   List input attributes
 * @var   string  $optKey    Option key
 * @var   string  $optText   Option title or label
 * @var   array   $selected  Selected options
 * @var   string  $idtag     List Id
 * @var   boolean $translate Translate title of list option or not.
 */

$cssClassSuffix = ' btn-group redRadioGroup';

if (is_array($attribs))
{
	$cssClassSuffix = (isset($attribs['cssClassSuffix'])) ? $attribs['cssClassSuffix'] : ' btn-group redRadioGroup';

	$attribs = ArrayHelper::toString($attribs);
}

$idText = $idtag ? $idtag : $name;

?>
<fieldset class="<?php echo $type . $cssClassSuffix; ?>">
	<?php if (!empty($data)) : ?>
		<?php foreach ($data as $i => $obj) : ?>
			<?php
			$key   = $obj->$optKey;
			$title = $translate ? JText::_($obj->$optText) : $obj->$optText;
			$id    = (isset($obj->id) ? $obj->id : null);

			$extra = '';
			$id    = $id ? $obj->id : $idText . $key;

			if (is_array($selected))
			{
				foreach ($selected as $val)
				{
					$key2 = is_object($val) ? $val->$optKey : $val;

					if ($key == $key2)
					{
						$extra .= ' selected="selected" ';
						break;
					}
				}
			}
			else
			{
				$extra .= ((string) $key == (string) $selected ? ' checked="checked" ' : '');
			}
			?>
            <input type="<?php echo $type; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $key; ?>"
				<?php echo $extra; ?> <?php echo $attribs; ?> rel="noicheck"/>
            <label class="<?php echo $type; ?>" for="<?php echo $id; ?>" id="<?php echo $id; ?>-lbl">
				<?php echo $title ?>
            </label>
		<?php endforeach; ?>
	<?php endif; ?>
</fieldset>
