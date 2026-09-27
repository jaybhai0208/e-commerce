1. Import database/migration.sql into the same MySQL database.
2. Configure SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, MAIL_FROM and MAIL_FROM_NAME in your server environment/.env.
3. Gmail requires an App Password when 2-Step Verification is enabled.
4. Admin > Register Admin creates role_as=1 users after OTP verification.
5. Customer registration now stores OTP hash in email_otps, deletes it after successful verification, and only then reveals password fields.
