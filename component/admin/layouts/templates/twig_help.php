<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/*
 * Layout variables
 * =============================
 * @var  array   $displayData  Available data.
 * @var  array   $methods      Available methods.
 * @var  string  $tag          Object tag.
 */

extract($displayData);
?>
<table class="table table-striped">
    <tbody>
	<?php foreach ($methods as $method => $data): ?>
		<?php
		$doc    = $data['doc'];
		$params = $data['params'];
		?>
        <tr>
            <td>
                <strong class="text text-danger"><?php echo $tag ?></strong>.<span
                        class="text text-primary"><?php echo $method ?></span>
				<?php if (!empty($params)): ?>
					<?php $parameteres = array(); ?>
					<?php foreach ($params as $param): ?>
						<?php
						/** @var ReflectionParameter $param */
						/** @var ReflectionType $paramType */
						$paramType = $param->getType();
						$parameteres[] = $paramType === null ? $param->getName() . ' : NULL' : $param->getName() . ' : ' . $paramType;
						?>
					<?php endforeach; ?>
                    (<?php echo implode(', ', $parameteres) ?>)
				<?php endif; ?>
            </td>
            <td>
				<?php echo $doc->getSummary() ?>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>
