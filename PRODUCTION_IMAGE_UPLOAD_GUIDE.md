# Production Deployment Checklist for Image Upload Fix

## Benzalee School - Image Upload & Retrieval Pipeline

This checklist documents the production deployment steps for the fixed image upload and retrieval system that addresses storage symlink dependencies and ensures reliable photo uploads.

---

## Pre-Deployment Verification

- [ ] Verify `config/filesystems.php` has public disk root set to `public_path('uploads')`
- [ ] Confirm `public/uploads/profile-photos/` directory exists and is writable
- [ ] Check that `.gitignore` and `.htaccess` exist in `public/uploads/`
- [ ] Verify no `asset('storage/')` references remain in Blade views (all use `Storage::url()`)
- [ ] Confirm both camera capture forms (create & edit) have enhanced error handling
- [ ] Test local upload/retrieval workflow before deployment

---

## Deployment Steps

### 1. Directory Setup
```bash
# Create upload directory structure
mkdir -p public/uploads/profile-photos

# Set proper permissions (adjust based on your server user)
chmod 755 public/uploads
chmod 755 public/uploads/profile-photos

# Verify ownership
chown -R www-data:www-data public/uploads  # or appropriate server user
```

### 2. No Symlink Required
⚠️ **IMPORTANT**: Unlike legacy storage symlink approach:
- **DO NOT** run `php artisan storage:link` for the uploads directory
- **DO NOT** create `public/storage` symlink for new uploads
- Images are stored directly in `public/uploads/` and accessed via `/uploads/` URL
- This eliminates production symlink issues entirely

### 3. Verify File Storage Configuration
```bash
# Check config is correct
grep -A5 "'public'" config/filesystems.php | head -15
```

Should show:
```php
'public' => [
    'driver' => 'local',
    'root' => public_path('uploads'),
    'url' => env('APP_URL').'/uploads',
    'visibility' => 'public',
],
```

### 4. Test Upload Endpoint
```bash
# Test API endpoint (adjust URL/ID as needed)
curl -X POST http://yoursite.com/api/students \
  -F "photo=@test_image.jpg" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

Expected response includes: `"profile_photo": "/uploads/profile-photos/filename.jpg"`

### 5. Verify Image Display
- Login to admin panel
- Create/edit a student with camera photo capture
- Verify image displays in:
  - Student edit form (preview)
  - Student detail view
  - Admin student list (if featured)
  - Student results view (if applicable)
  - PDF report generation

---

## Troubleshooting

### Images Upload but Don't Display

**Check 1: File Actually Exists**
```bash
ls -la public/uploads/profile-photos/
```

**Check 2: Web Server Permissions**
```bash
# Ensure web server can read files
chmod 644 public/uploads/profile-photos/*.jpg
```

**Check 3: Blade Template Using Correct URL**
```blade
{{-- Correct: --}}
{{ Storage::url($student->profile_photo) }}

{{-- Incorrect (will fail): --}}
{{ asset('storage/' . $student->profile_photo) }}
```

### Camera Capture Fails

**Check Browser Console**
- Press F12 in browser
- Look for console errors in "Console" tab
- Common errors:
  - "Canvas toBlob returned null" → Camera/canvas issue
  - "DataTransfer error" → Browser API compatibility
  - "Failed to capture image" → Permission or device issue

**Check User Permissions**
- User must grant camera access when prompted
- Check browser camera permissions for the site

### File Size Too Large

**Check upload limit in PHP**
```bash
grep -E "upload_max_filesize|post_max_size" /etc/php/*/apache2/php.ini
```

Camera typically captures 200KB-500KB JPEGs at 0.9 quality.

---

## Rollback Plan

If issues occur and you need to revert:

1. **Restore old storage path** (if keeping legacy support):
   ```php
   'public' => [
       'driver' => 'local',
       'root' => storage_path('app/public'),  // Back to old path
       'url' => env('APP_URL').'/storage',
   ],
   ```

2. **Recreate storage symlink**:
   ```bash
   php artisan storage:link
   ```

3. **Revert Blade views** to use `asset('storage/')` for legacy uploads

4. **Database**: No migration needed; file paths reference the original location

---

## Verification Checklist - Post Deployment

- [ ] Admin can create student with camera photo
- [ ] Photo appears in student edit view immediately
- [ ] Photo displays in student list/profile views
- [ ] Admin can edit student and replace photo
- [ ] Old photo deleted when replaced
- [ ] PDF reports include student photos correctly
- [ ] Photo URLs use `/uploads/profile-photos/` format
- [ ] No console errors in browser during upload
- [ ] File permissions allow web server read access
- [ ] Storage disk is accessible from all server instances (if load balanced)

---

## Code Changes Summary

**Files Modified:**
1. `config/filesystems.php` - Changed public disk root to `public_path('uploads')`
2. `routes/web.php` - Standardized upload folder to `profile-photos`, API response uses `Storage::url()`
3. `app/Http/Controllers/Admin/AdminStudentController.php` - Upload folder standardized to `profile-photos`
4. Blade views - All updated to use `Storage::url($path)` instead of `asset('storage/' . $path)`
5. `resources/views/admin/students/create.blade.php` - Camera capture with enhanced error handling
6. `resources/views/admin/students/edit.blade.php` - Camera capture with enhanced error handling

**Directories Created:**
- `public/uploads/` - Main upload directory (directly web-accessible)
- `public/uploads/profile-photos/` - Student photo storage
- `.gitignore` and `.htaccess` for security and accessibility

---

## Key Improvements

✅ **No Symlink Dependency** - Images directly in public folder  
✅ **Consistent Path Handling** - All uploads use `profile-photos` folder  
✅ **Unified Retrieval Method** - All views use `Storage::url()` facade  
✅ **Robust Camera Capture** - Comprehensive error handling and validation  
✅ **Production Ready** - Tested against WriTok-backend patterns  

---

## Support

For issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Web server logs: `/var/log/apache2/error.log` or `/var/log/nginx/error.log`
3. Browser console (F12) for client-side errors
4. File permissions: `ls -la public/uploads/profile-photos/`

---

*Last Updated: [Current Date]*
*Related Docs: COMPONENT_ORGANIZATION.md, USER_MANAGEMENT_IMPLEMENTATION.md*
