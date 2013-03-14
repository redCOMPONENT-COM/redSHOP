<table class="admintable" border="0" cellspacing="0" cellpadding="0">
	<tbody>
	<tr valign="top">
		<td>{private_billing_template:private_billing_template}{company_billing_template:company_billing_template}</td>
		<td>{account_creation_start}
			<table class="admintable" border="0">
				<tbody>
				<tr>
					<td width="100" align="right">{username_lbl}</td>
					<td>{username}</td>
					<td><span class="required">*</span></td>
				</tr>
				<tr>
					<td width="100" align="right">{password_lbl}</td>
					<td>{password}</td>
					<td><span class="required">*</span></td>
				</tr>
				<tr>
					<td width="100" align="right">{confirm_password_lbl}</td>
					<td>{confirm_password}</td>
					<td><span class="required">*</span></td>
				</tr>
				<tr>
					<td width="100" align="right">{newsletter_signup_chk}</td>
					<td colspan="2">{newsletter_signup_lbl}</td>
				</tr>
				</tbody>
			</table>
			{account_creation_end}
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right"><span class="required">*</span>{required_lbl}</td>
	</tr>
	<tr class="trshipping_add">
		<td class="tdshipping_add" colspan="2">{sipping_same_as_billing_lbl} {sipping_same_as_billing}</td>
	</tr>
	</tbody>
</table>