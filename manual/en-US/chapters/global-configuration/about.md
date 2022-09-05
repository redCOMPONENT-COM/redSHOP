## redSHOP Configuration - About
This last section offers the shop administrator an information panel covering basic system / server configuration details as well as any redSHOP extensions installed and whether they're enabled or not. The information is categorized into four sections: "System Information", "redSHOP Modules", "redSHOP Shipping Plugins" and "redSHOP Payment Plugins".

<hr>

### In this article you will fine:

<ul>
<li><a href="#system">System Information</a>
<li><a href="#modules">redSHOP Modules</a>
<li><a href="#shipping">redSHOP Shipping Plugins</a>
<li><a href="#payment">redSHOP Payment Plugins</a>
</ul>

<hr>

### Overview About Tab Screen

<img src="./manual/en-US/chapters/global-configuration/img/img93.png" class="example"/>

<hr>

<!-- System Information -->
<h2 id="system">System Information</h2>

This section of the "About" tab displays all the redSHOP-related modules, developed by the redSHOP Team, that have been installed on the site. Details regarding the modules installed are displayed in a table consisting of three columns with the following headers:

<img src="./manual/en-US/chapters/global-configuration/img/img94.png" class="example"/>

<ul>
<li><b>Check - </b>Displays the server software versions and related configuration file settings

<li><b>Result - </b>Displays the version numbers or configuration file setting values

<li><b>Web Server - </b>The software versions that are installed on the current web server. Displays version numbers for Apache, mod_ssl, OpenSSL, PHP,mod_perl, and Perl.

<li><b>PHP version - </b>The version of PHP installed and configured on the current web server.

<li><b>MYSQL Version - </b>The version of MySQL installed and configured on the current web server.

<li><b>GD Image Library - </b>The version of the GD Image Library installed and configured on the current web server. (The GD Image Library is used to generate redSHOP's thumbnails.)

<li><b>Multibyte string support - </b>Shows whether support for Multibyte strings is enabled or not. This setting is controlled by the "Use Multibyte Encoding" setting within the Global Configuration's "General" tab.

<li><b>Upload Limit - </b>The maximum size, measured in MB, that files uploaded to the site / server can be. Files larger than the value for the "Upload Limit" will not be accepted / uploaded to the server. This value can be configured using the upload_max_filesize setting from within the "php.ini" file.

<li><b>Memory Limit - </b>The maximum amount of memory, measured in MB, that a script is allowed to allocate for processing purposes. This helps prevent poorly written scripts for eating up all available memory on a server; the higher the amount, the more memory can be allocated and therefore scripts will be running faster and smoother. This value can be configured using the memory_limit setting from within the "php.ini" file.

<li><b>Open Remote Files (allow_url_fopen) - </b>Shows whether the "allow_url_fopen" setting has been enabled or not. This feature allows PHP's file functions to retrieve data from remote locations such as an FTP server or web site by allowing URLs (like http:// or ftp://) to be treated as files, however this could lead to code injection vulnerabilities and therefore it is generally recommended to ensure this setting is disabled. You can configure the allow_url_fopen' setting from within the "php.ini" file on the server.

<li><b>Execution Time - </b>The maximum amount of time, measured in seconds, that a script or function is allowed to take when running. A value of 200, for example, will mean any script or function run within redSHOP must have completed its job within the span of 200 seconds (3m 20s), otherwise the administrator will be shown a white screen with a "Timeout" error message.
</ul>

<hr>

<!-- redSHOP Modules -->
<h2 id="modules">redSHOP Modules</h2>

This section of the "About" tab displays all the redSHOP-related modules, developed by the redSHOP Team, that have been installed on the site. Details regarding the modules installed are displayed in a table consisting of three columns with the following headers:

<img src="./manual/en-US/chapters/global-configuration/img/img95.png" class="example"/>

<ul>
<li><b>Check - </b>Displays the name of the module

<li><b>Result - </b>Displays the results of the installation, usually indicates "installed"

<li><b>Published - </b>Shows whether the module is currently published

<br>More details regarding the available redSHOP-related modules can be found in the "Front-end Customization" section.
</ul>

<hr>

<!-- redSHOP Shipping Plugins -->
<h2 id="shipping">redSHOP Shipping Plugins</h2>

This section of the "About" tab displays all the redSHOP shipping plugins, developed by the redSHOP Team, that have been installed on the site. Details regarding the shipping plugins installed are displayed in a table consisting of three columns with the following headers:

<img src="./manual/en-US/chapters/global-configuration/img/img96.png" class="example"/>

<ul>
<li><b>Check - </b>Displays the name of the shipping plugin

<li><b>Result - </b>Displays the results of the installation, usually indicates "installed"

<li><b>Published - </b>Shows whether the module is currently published

<br>More details regarding the shipping plugins available for redSHOP can be found in the "Shipping Method Management" section.
</ul>

<hr>

<!-- redSHOP Payment Plugins -->
<h2 id="payment">redSHOP Payment Plugins</h2>

This section of the "About" tab displays all the redSHOP payment plugins, developed by the redSHOP Team, that have been installed on the site. Details regarding the payment plugins installed are displayed in a table consisting of three columns with the following headers:

<img src="./manual/en-US/chapters/global-configuration/img/img97.png" class="example"/>

<ul>
<li><b>Check - </b>Displays the name of the payment plugin

<li><b>Result - </b>Displays the results of the installation, usually indicates "installed"

<li><b>Published - </b>Shows whether the module is currently published

<br>More details regarding the payment method plugins available for redSHOP can be found in the "Payment Options Management" section.
</ul>

<hr>

<h6>Last updated on August 6, 2022</h6>