# 🚀 Production Deployment Guide - Laravel Cloud

## ⚠️ IMPORTANT: Fix for CSRF Token Mismatch Error

If you're experiencing "CSRF token mismatch" or "419 Page Expired" errors on Laravel Cloud, follow these steps:

---

## 📋 Required Environment Variables for Laravel Cloud

Go to your **Laravel Cloud Dashboard** → **Environment Variables** and set these:

```env
# App Configuration
APP_ENV=production
APP_DEBUG=false
APP_URL=https://e-ecommerce-laravel-main-rxpjft.laravel.cloud

# Session Configuration (CRITICAL for fixing CSRF errors)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_DOMAIN=.laravel.cloud
SESSION_ENCRYPT=false
SESSION_PATH=/
```

---

## 🔧 Deployment Steps

### 1. **Push Your Code**
```bash
git push origin main
```

### 2. **Wait for Auto-Deployment**
Laravel Cloud will automatically deploy your code.

### 3. **Run Post-Deployment Commands**

After deployment completes, SSH into your Laravel Cloud instance or use the dashboard terminal:

```bash
# Clear all caches (REQUIRED!)
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations (if needed)
php artisan migrate --force
```

### 4. **Verify Sessions Table Exists**
```bash
php artisan db:table sessions
```

You should see a table with these columns:
- id
- user_id
- ip_address
- user_agent
- payload
- last_activity

---

## ✅ Testing After Deployment

### 1. **Check Session Cookie**
1. Visit your site: `https://e-ecommerce-laravel-main-rxpjft.laravel.cloud`
2. Open DevTools (F12) → **Application** tab
3. Check **Cookies** section
4. Look for `laravel_session` cookie
5. Verify it has:
   - ✅ `Secure` flag (checkmark)
   - ✅ `HttpOnly` flag (checkmark)
   - ✅ `SameSite: Lax`

### 2. **Test Cart Functionality**
1. Go to `/products` page
2. Open DevTools → **Network** tab
3. Click "Add to Cart" on any product
4. Check the request to `/api/cart/add/{id}`
5. Should return **200 OK** (not 419)

### 3. **Test Admin Product Creation**
1. Go to `/admin/products/create`
2. Fill out the form
3. Submit
4. Should create successfully (not 419 error)

---

## ❌ Common Issues & Solutions

### Issue 1: Still Getting 419 Error
**Cause:** Config is cached on production

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Issue 2: Session Cookie Not Being Set
**Cause:** Missing `SESSION_SECURE_COOKIE=true`

**Solution:**
1. Go to Laravel Cloud Dashboard
2. Add environment variable: `SESSION_SECURE_COOKIE=true`
3. Redeploy or run `php artisan config:clear`

### Issue 3: Sessions Table Doesn't Exist
**Cause:** Migration not run

**Solution:**
```bash
php artisan migrate --force
```

### Issue 4: CORS or Cookie Domain Issues
**Cause:** Wrong `SESSION_DOMAIN`

**Solution:**
Set `SESSION_DOMAIN=.laravel.cloud` in Laravel Cloud environment variables

---

## 🔍 Debugging

### Check Current Session Config
```bash
php artisan config:show session
```

Look for:
- `driver` should be `database`
- `secure` should be `true`
- `http_only` should be `true`
- `same_site` should be `lax`

### Check Sessions in Database
```bash
php artisan tinker --execute="echo 'Sessions: ' . DB::table('sessions')->count();"
```

### View Recent Errors
```bash
tail -f storage/logs/laravel.log
```

---

## 📝 Environment Variable Checklist

Before deployment, ensure these are set on Laravel Cloud:

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` matches your Laravel Cloud URL
- [ ] `SESSION_DRIVER=database`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] `SESSION_HTTP_ONLY=true`
- [ ] `SESSION_SAME_SITE=lax`
- [ ] `SESSION_DOMAIN=.laravel.cloud`
- [ ] Database credentials configured
- [ ] S3/R2 storage credentials configured

---

## 🎯 Quick Fix Summary

The CSRF error happens because:
1. Production uses HTTPS (local uses HTTP)
2. HTTPS requires secure cookies
3. Without `SESSION_SECURE_COOKIE=true`, cookies aren't sent
4. Without cookies, sessions don't work
5. Without sessions, CSRF tokens fail → 419 error

**The fix:** Set `SESSION_SECURE_COOKIE=true` on production and clear all caches.

---

## 📞 Still Having Issues?

1. Check the browser console for errors
2. Check Network tab for failed requests
3. Check Laravel Cloud logs
4. Verify all environment variables are set correctly
5. Make sure you ran `config:clear` after changing environment variables

---

## ✨ What Changed in This Fix

1. **Moved cart API routes** from `routes/api.php` to `routes/web.php` for CSRF protection
2. **Updated session config** to auto-detect production and use secure cookies
3. **Added production environment examples** with correct settings
4. **Created this deployment guide** to prevent future issues

---

Last updated: 2025-01-16
