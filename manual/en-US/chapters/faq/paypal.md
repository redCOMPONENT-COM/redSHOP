## PayPal SSL Certificate Change
This document applies to those seeking information regarding an upcoming change in the PayPal SSL Certificate as outlined here:  https://devblog.paypal.com/paypal-ssl-certificate-changes/

The redCOMPONENT PayPal payment plugins do not require any updates to work with this new certificate. 

<hr>

<h4>Important Information</h4>

If you are using the standard PayPal payments plugin, you do not need to make any changes.

If you are using the PayPal Pro Payment Plugin (where the payments are processed within your site without redirecting to PayPal) you may need to make changes at your hosting level. If you find that there are issues please check the outlined steps provided by PayPal, pasted below:

<ul>
<li>Save the <a href="https://knowledge.verisign.com/support/mpki-for-ssl-support/index?page=content&actp=CROSSLINK&id=SO5624">VeriSign G5 Root Trust Anchor</a> in your keystore.

<li>Upgrade your environment to support the SHA-256 signing algorithm.

<li>Perform end-to-end testing of the integration against the Sandbox / Payflow Pilot environment (including Instant Payment Notifications (IPN), Payment Data Transfer (PDT), and Silent Posts).
</ul>

The first two steps relate to your hosting environment, and as such your hosting provider should be able to help you or point you in the right direction for support.

For performing tests, follow these steps as outlined by PayPal in the document:

<h4>Testing Your SSL Certificate Upgrade</h4>

Any tests that are currently run against PayPal Sandbox endpoints will require a VeriSign G5 root certificate, so you can test your upgrades by making requests against the Sandbox environment by using the following steps:

<ul>
<li>Swap out the live API credentials / API endpoints on the merchant application with the Sandbox credentials / API endpoints.

<li>If you receive a handshake error (e.g. “No trusted certificate found”), check the merchant keystone to see if the PayPal VeriSign G5 root certification is present.

<li>If not, download the <a href="https://knowledge.verisign.com/support/mpki-for-ssl-support/index?page=content&actp=CROSSLINK&id=SO5624">VeriSign Class 3 Public Primary Certification Authority – G5 root certificate</a>, or download the <a href="https://ppmts.custhelp.com/app/answers/detail/a_id/952">endpoint-specific SSL certificates</a>, and put these certificates in their keystore.
</ul>

<hr>

<h6>Last updated on January 4, 2016</h6>