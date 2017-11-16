DELETE `#__redshop_fields` WHERE `name` IN ('rs_kerry_city', 'rs_kerry_district', 'rs_kerry_ward', 'rs_kerry_billing_city', 'rs_kerry_billing_district', 'rs_kerry_billing_ward');

DELETE FROM `#__redshop_order_status` WHERE `order_status_code` IN ('PUX','PUP','SIP','SIP-L','SOP-D','POD','PODEX','Cancel','PODR','SOPR');