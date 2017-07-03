<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.QuickBook
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

?>
<div id="getTicketModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="getTicketModalLabel" aria-hidden="true">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h1 id="getTicketModalLabel"><?php echo $title ?></h1>
	</div>
	<div class="modal-body">
		<ol>
			<li>Copy following URL which you need to add as a <b>Subscription URL</b> while you will create QBMS Application in next step.
				<pre><?php echo $subscriptionUrl ?></pre>
			</li>
			<li>
				<p>Follow the steps on the application registration page here: https://developer.intuit.com/Application/Create/QBMS</p>
			</li>
			<li>
				<p>After you have developed your QBMS application, you need to attach your QBMS account to your application registration. You can do that by visiting the links below in a web browser.</p>
				<p>
					For <span class="label label-success">Production</span> applications visit this page in a web browser: <a target="_blank" id="app_id_link_production" href="">Click me to get connection ticket for Production</a>
				</p>
				<p>
					For <span class="label label-important">Development</span> applications, visit this page in a web browser: <a target="_blank" id="app_id_link_develop" href="">Click me to get connection ticket for Development</a>
				</p>
			</li>
		</ol>
	</div>
	<div class="modal-footer" style="text-align:left;">
		<span><?php echo $userAgreementText ?></span>
		<a href="#" class="btn btn-primary" id="generate_conn_ticket"><?php echo $connectionTicketButtonTxt ?></a>
	</div>
</div>
