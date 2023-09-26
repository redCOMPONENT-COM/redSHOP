<div>
    {private_billing_template:private_billing_template}

    {company_billing_template:company_billing_template}
</div>

{account_creation_start}
<div>
    <div class="form-group">
        {username_lbl}{username}
        <span class="required">*</span>
    </div>

    <div class="form-group">
        {password_lbl}{password}
        <span class="required">*</span>
    </div>

    <div class="form-group">
        {confirm_password_lbl}{confirm_password}
        <span class="required">*</span>
    </div>

    <div class="form-group">
        {newsletter_signup_chk}
        {newsletter_signup_lbl}
    </div>
</div>
{account_creation_end}

<div class="form-check">
    {shipping_same_as_billing}
    {shipping_same_as_billing_lbl}
</div>