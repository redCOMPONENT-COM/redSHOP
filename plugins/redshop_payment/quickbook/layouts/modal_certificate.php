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
<div id="getCertificateModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="getCertificateModalLabel" aria-hidden="true">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h1 id="getCertificateModalLabel"><?php echo $title ?></h1>
	</div>
<div class="modal-body">
	<ol>
		<li>
			<p>
				<button id="generatePrivateKey" class="btn btn-primary" type="button"><?php echo $privateKeyButtonTxt ?></button>
			</p>
			<p><textarea id="privateKeyTxt" rows="8" class="input-xxlarge"></textarea></p>
		</li>
		<li>
			<p>After successfully generating private key, time to create CSR certificate for signing request.</p>
			<p>To generate the CSR certificate, please copy private key from above text box and generate new file in your local machine as <b>private.key</b></p>
			<p>Now, you will need to execute following command in terminal(if you are Linux or Mac user, for windows use Cygwin - http://www.cygwin.com).</p>
			<pre>openssl req -new -nodes -key private.key -out host.csr</pre>
			<p>Pay attention now when you are creating CSR certificate. You will be asked for such information like country, state, company etc... This information are not necessory and just skipp them by giving <span class="badge badge-inverse">.</span>(dot)</p>
			<p>Common Name (CN) is the required field and in must be in <b>your-https-hostname.com:your-application-login</b> format. You may also copy it from your application sign certificate page.</p>
		</li>
		<li>
			<p>
				Paste signed certificate here which you got from intuit site after signing certificate there in following format.
				<pre>
-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCsUdEx9P9Cn1ghpPf5HSLKlw2I7MGAmUEKp2wuqeEURsAEm/WT
XNhrbywv5SqeYJqbiZnjjjek01a+gWoCyN/7hIB1/XELIYffGiJv7pvFLzY6yfv8
... more stuff here...
-----END RSA PRIVATE KEY-----
-----BEGIN CERTIFICATE-----
MIIEEzCCA3ygAwIBAgICB1MwDQYJKoZIhvcNAQEEBQAwgcExCzAJBgNVBAYTAlVT
MRYwFAYDVQQIEw1NYXNzYWNodXNldHRzMRAwDgYDVQQHEwdXYWx0aGFtMTswOQYD
... more stuff here...
-----END CERTIFICATE-----
				</pre>
			</p>
			<p><textarea id="signedCertificateTxt" rows="8" class="input-xxlarge"></textarea></p>
		</li>
	</ol>
	</div>
	<div class="modal-footer" style="text-align:left;">
		<span><?php echo $userAgreementText ?></span>
		<a href="#" class="btn btn-primary" id="generatePem"><?php echo $pemKeyButtonTxt?></a>
	</div>
</div>
