## redSHOP Configuration - Newsletter

<hr>

### In this article you will fine:

<ul>
<li><a href="#newsletter">Newsletter</a>
<li><a href="#test">Test Mail</a>
</ul>

<hr>

### Overview Newsletter Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img60.png" class="example"/>

<hr>

<!-- Newsletter -->
<h2 id="newsletter">Newsletter</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img61.png" class="example"/>

<ul>
<li><b>Enable Newsletter : </b>Available options: Yes, No

<li><b>Newsletter Confirmation - </b>Sets whether customers should receive an email containing a newsletter sign-up confirmation link that must be clicked on for verification before their email addresses will be added to the list of newsletter recipients. 
<br><b>Available options: </b>Yes, No

<li><b>Newsletter sender name - </b>The name that will appear in the "From" section of the newsletter email that those on the newsletter mailing list will receive. This would normally be the same name as the "Shop Name", however the shop administrator can modify this to their preference.

<li><b>Mail From - </b>The email address that will appear in the "From" section of the newsletter email that those on the newsletter mailing list will receive. This would normally be the online store's main email account address that customers would reply to, however the shop administrator can modify this to their preference.

<li><b>Default Newsletter - </b>Selects the newsletter template that should be used as the "default template" when constructing and sending out newsletters. The shop administrator can select from a list of all available "Newsletter" templates in redSHOP's "Newsletters" section. More information on newsletter templates is available in redSHOP's Mail and Communications section.

<li><b>No. of Mails in Batch - </b>The number of newsletter emails that should be sent out per batch when mass mailing newsletter subscribers. This setting is useful to shop administrators wanting to control the number of emails that can be sent at a time, as this number can vary based on server configurations and "outgoing mail" server settings. Setting a number too high can result the outgoing mail server getting overloaded and newsletter emails getting "blocked", so speaking with the site's hosting provider regarding the appropriate number to put in here would be advisable.

<li><b>Pause Between Batches (in seconds) - </b>The amount of time (in seconds) the outgoing mail server should wait between mass-mailing batches of newsletter subscribers. Together with the setting for "No. of Mails in Batch", this is designed to give the mail server ample time to send one batch successfully before sending the next one, reducing the load on the server and minimizing chances of newsletter mails being marked as "Spam", as some server configurations are set to mark certain mails as spam based on specific patterns of mail server behaviour. Once again, speaking with the site's hosting provider regarding the appropriate number to put in here would be advisable.
</ul>

<hr>

<!-- Test Mail -->
<h2 id="test">Test Mail</h2>

<img src="./manual/en-US/chapters/global-configuration/img/img62.png" class="example"/>

<ul>
<li><b>Test mail - </b>Designed to offer an idea of how the default newsletter will appear to recipients, this field allows the shop administrator to enter an email address that a sample newsletter can be sent to. This email address is not stored and has no effect on any other part of redSHOP; it is meant for the administrator to send a sample newsletter (using the "Test Newsletter" button below), based on the "Default Newsletter" template selected, to an email address of choice for visual inspection and verification purposes.

<li><b>Test Newsletter - </b>The button used to send a sample newsletter to the "Test mail" email address. This has no effect on any other part of redSHOP; it is meant only as a means for the shop administrator to visually inspect and verify the newsletter that newsletter subscribers will receive.
</ul>

<hr>

<h6>Last updated on July 22, 2019</h6>