# Email Notification Setup Guide

This guide will help you configure email notifications for order confirmations in your K2 Computer e-commerce application.

## 📧 Email Features Implemented

- **Order Confirmation Emails** sent automatically after:
  - Cash on Delivery (COD) orders
  - Successful KHQR payment verification

- **Professional HTML Email Template** including:
  - Order details and order number
  - Product list with quantities and prices
  - Subtotal, tax, shipping, and total
  - Customer shipping address
  - Order status and payment method
  - Payment instructions for COD orders

---

## 🆓 Free Email Service Options

### Option 1: Mailtrap (For Testing/Development) ⭐ **RECOMMENDED FOR TESTING**

**Best for:** Development and testing before going live
**Free Plan:** Unlimited emails in test environment
**Website:** https://mailtrap.io

#### Setup Steps:
1. Go to https://mailtrap.io and create a free account
2. Click on "Email Testing" → "Inboxes"
3. Select your inbox or create a new one
4. Copy the SMTP credentials shown on the page
5. Update your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username_here
MAIL_PASSWORD=your_mailtrap_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@k2computer.com"
MAIL_FROM_NAME="K2 Computer"
```

6. Test your order and check Mailtrap inbox for the email

**Advantages:**
- ✅ Perfect for testing - emails don't go to real customers
- ✅ Unlimited emails in test environment
- ✅ Easy to debug email issues
- ✅ View HTML and plain text versions

---

### Option 2: Brevo (formerly Sendinblue) ⭐ **RECOMMENDED FOR PRODUCTION**

**Best for:** Production use with real customers
**Free Plan:** 300 emails per day (9,000 per month)
**Website:** https://www.brevo.com

#### Setup Steps:
1. Go to https://www.brevo.com and create a free account
2. Verify your email address
3. Go to "Settings" → "SMTP & API"
4. Click "Generate a new SMTP key" or use existing one
5. Copy your SMTP credentials
6. Update your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email@example.com
MAIL_PASSWORD=your_smtp_key_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@k2computer.com"
MAIL_FROM_NAME="K2 Computer"
```

**Important Notes:**
- The MAIL_USERNAME is your Brevo account email
- The MAIL_PASSWORD is your SMTP key (NOT your account password)
- You may need to verify your sender email/domain in Brevo

**Advantages:**
- ✅ 300 emails/day for free (enough for small businesses)
- ✅ Professional email service
- ✅ Delivery statistics and tracking
- ✅ No credit card required for free tier

---

### Option 3: SendGrid

**Best for:** Alternative production option
**Free Plan:** 100 emails per day (3,000 per month)
**Website:** https://sendgrid.com

#### Setup Steps:
1. Go to https://sendgrid.com and create a free account
2. Complete email verification
3. Go to "Settings" → "API Keys"
4. Create a new API key with "Mail Send" permissions
5. Copy the API key (you won't see it again!)
6. Update your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@k2computer.com"
MAIL_FROM_NAME="K2 Computer"
```

**Important:** MAIL_USERNAME must be exactly "apikey" (not your email)

---

### Option 4: Gmail (For Testing Only)

**⚠️ Not Recommended for Production**

#### Setup Steps:
1. Enable 2-Step Verification in your Google Account
2. Generate an "App Password":
   - Go to https://myaccount.google.com/security
   - Click "2-Step Verification"
   - Scroll to "App passwords"
   - Generate a password for "Mail"
3. Update your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_16_digit_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_gmail@gmail.com"
MAIL_FROM_NAME="K2 Computer"
```

**Limitations:**
- ❌ Daily sending limit (around 500 emails/day)
- ❌ Not professional for business use
- ❌ May be marked as spam
- ❌ Requires 2FA and app password

---

## 🚀 Quick Start Guide

### Step 1: Choose Your Email Service

For testing: **Mailtrap**
For production: **Brevo** (300 emails/day free)

### Step 2: Update Your `.env` File

1. Open your `.env` file (NOT `.env.example`)
2. Find the MAIL configuration section
3. Copy the configuration from above for your chosen service
4. Replace the placeholder values with your actual credentials
5. Save the file

### Step 3: Clear Laravel Cache

Run these commands in your terminal:

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 4: Test Email Sending

1. Place a test order with COD payment method
2. Check your email inbox (or Mailtrap inbox)
3. You should receive a beautiful order confirmation email!

---

## 🧪 Testing Email Configuration

### Method 1: Tinker Command

```bash
php artisan tinker
```

Then run:

```php
Mail::raw('Test email from K2 Computer', function($message) {
    $message->to('your_test_email@example.com')
            ->subject('Test Email');
});
```

If no error appears, your email is configured correctly!

### Method 2: Place a Test Order

1. Add products to cart
2. Go to checkout
3. Fill in customer details (use your real email for testing)
4. Select "Cash on Delivery"
5. Complete the order
6. Check your email inbox

---

## 📁 Files Modified

1. **app/Mail/OrderConfirmation.php** - Email class
2. **resources/views/emails/order-confirmation.blade.php** - Email template
3. **app/Http/Controllers/CheckoutController.php** - Sends email for COD
4. **app/Http/Controllers/KhqrController.php** - Sends email after KHQR payment
5. **.env.example** - Email configuration examples

---

## 🔧 Troubleshooting

### Problem: Emails not sending

**Solution 1:** Check your `.env` file configuration
```bash
php artisan config:clear
```

**Solution 2:** Check Laravel logs
```bash
tail -f storage/logs/laravel.log
```

**Solution 3:** Verify SMTP credentials are correct

### Problem: Emails going to spam

**Solutions:**
- Use a professional email service (Brevo, SendGrid)
- Set up SPF/DKIM records for your domain
- Use a verified sender email address
- Don't use Gmail for production

### Problem: "Connection refused" error

**Solutions:**
- Check firewall settings
- Verify SMTP port is correct (587 or 2525)
- Check if MAIL_ENCRYPTION matches the port
- Some servers block port 25

---

## 🎯 Production Checklist

Before going live:

- [ ] Switch from Mailtrap to Brevo or SendGrid
- [ ] Update MAIL_FROM_ADDRESS to your domain
- [ ] Test email delivery to multiple providers (Gmail, Yahoo, etc.)
- [ ] Check spam score at https://mail-tester.com
- [ ] Set up SPF and DKIM records for your domain
- [ ] Enable queue for email sending (optional but recommended)

---

## 🚀 Advanced: Queue Email Sending (Optional)

To prevent slow page loads, send emails in background:

1. Set up queue in `.env`:
```env
QUEUE_CONNECTION=database
```

2. Run migrations:
```bash
php artisan queue:table
php artisan migrate
```

3. Start queue worker:
```bash
php artisan queue:work
```

The `OrderConfirmation` mail class already implements `ShouldQueue`, so emails will be automatically queued!

---

## 📊 Monitoring Email Delivery

### For Brevo:
- Login to dashboard
- View "Statistics" section
- See delivery rate, opens, clicks

### For SendGrid:
- Login to dashboard
- Check "Activity Feed"
- Monitor delivery status

### For Mailtrap:
- View all test emails in inbox
- Check HTML rendering
- Debug email content

---

## 💡 Tips

1. **Always test with Mailtrap first** before using production services
2. **Use queues in production** to avoid slowing down checkout
3. **Monitor your email quota** to avoid hitting limits
4. **Keep sender email consistent** (e.g., noreply@yourdomain.com)
5. **Include unsubscribe link** if sending marketing emails

---

## 📞 Support

If you encounter issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify email service status pages
3. Test SMTP credentials with a mail client (Thunderbird, etc.)
4. Contact your email service provider support

---

## ✅ What's Next?

After setting up email notifications, consider:

1. **Add more email templates:**
   - Order shipped notification
   - Order delivered notification
   - Payment receipt

2. **Customize email design:**
   - Add your logo
   - Match your brand colors
   - Update contact information

3. **Add email preferences:**
   - Let customers opt-in/opt-out
   - Email notification settings

---

**Happy Emailing! 🎉**