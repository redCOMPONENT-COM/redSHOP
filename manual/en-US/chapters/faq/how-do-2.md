## How do I eliminate VAT from my shop (non VAT countries)?
For non-VAT countries a shop owner will most likely want to eliminate references to VAT in redSHOP's front end.

To do this:

<h4>1. Go to administrator panel >> Components >> redSHOP >> Configuration</h4>
<ul>
<b>Select the tab 'Price' and set the following:</b>
<li>Use Tax Exempt: YES
<li>Show Tax Exempt in Front: NO
<li>Apply VAT for Tax Exempt: NO
</ul><br>

<h4>2. Go to administrator panel >> Components >> redSHOP >> VAT / Tax and Currency</h4>
<ul>
<b>In the VAT / Tax Group Management do the following:</b>
<li>Select the group: Default
<li>Click in the button: VAT/Tax
<li>Delete all the VAT Rates that you have in redSHOP
</ul><br>

<h4>3. Go to administrator panel >> Components >> redSHOP >> Customisation >> Templates</h4>
<ul>
<li>Open the template for the cart by clicking on the title.
<li>In the description you can remove the code that you not need to show, for example:

<pre>
&lt;tr&gt;
	&lt;td&gt;&lt;strong&gt;{product_subtotal_excl_vat_lbl}:&lt;/strong&gt;&lt;/td&gt;
	&lt;td width="100"&gt;{product_subtotal_excl_vat}&lt;/td&gt;
&lt;/tr&gt;
</pre>

<li>If you remove the above code from this template you will remove from frontend the text: Subtotal excl vat
</ul><br>

<h4>4. Click Save, or Save and Close</h4>

<hr>

<h4>Replacing a tag that was mistakenly deleted</h4>

If by mistake you delete a tag that you need you can copy and paste the original code for this template. You will find it in the same template, under: "Default template details".

<hr>

<h6>Last updated on January 4, 2016</h6>