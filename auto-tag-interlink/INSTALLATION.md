# Installation Guide - Auto Tag Interlink Plugin

## 🚀 Quick Installation (3 Steps)

### Step 1: Get the Plugin Files

You have the complete plugin in the `auto-tag-interlink` folder. This folder contains all necessary files.

### Step 2: Upload to WordPress

**Option A: Via FTP/SFTP**
1. Connect to your server via FTP
2. Navigate to `/wp-content/plugins/`
3. Upload the entire `auto-tag-interlink` folder
4. The path should be: `/wp-content/plugins/auto-tag-interlink/`

**Option B: Via cPanel File Manager**
1. Login to cPanel
2. Open File Manager
3. Navigate to `public_html/wp-content/plugins/`
4. Upload the `auto-tag-interlink` folder

**Option C: Via WordPress Admin (ZIP)**
1. Create a ZIP file of the `auto-tag-interlink` folder
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin"
4. Choose the ZIP file
5. Click "Install Now"

### Step 3: Activate

1. Go to **Plugins** in WordPress admin
2. Find "Auto Tag Interlink"
3. Click **Activate**
4. You'll see a success message

---

## ⚙️ Initial Configuration

After activation, configure the plugin:

1. Go to **Settings → Auto Tag Interlink**
2. Review the settings:
   - ✅ **Enable Auto-Linking**: ON (default)
   - ✅ **Link in Headings**: OFF (recommended for beginners)
   - ✅ **Post Types**: Select "Post" (or add more)
   - ✅ **Keyword Meta Fields**: `keywords` (default)
   - ✅ **Max Related Posts**: 10 (default)
3. Click **Save Changes**

---

## 🧪 Testing the Plugin

### Test 1: Basic Linking

**Create Test Post #1:**
- Title: "WordPress SEO Guide"
- Tags: `WordPress`, `SEO`
- Content: "WordPress is a powerful platform. SEO is important for visibility."

**Create Test Post #2:**
- Title: "SEO Best Practices"
- Tags: `SEO`, `Marketing`
- Content: "Follow these SEO best practices for better rankings."

**Create Test Post #3:**
- Title: "WordPress Tips"
- Tags: `WordPress`, `Tips`
- Content: "Here are WordPress tips for beginners."

**Now Re-save Test Post #1:**
- Edit Test Post #1
- Click "Update"
- View the post

**Expected Result:**
The words "WordPress" and "SEO" in Test Post #1 should now be linked to Test Posts #2 and #3.

### Test 2: Verify Links

1. Open Test Post #1 in the WordPress editor
2. Switch to "Code Editor" mode (if in Gutenberg)
3. Look for `<a href="...">` tags around "WordPress" and "SEO"
4. ✅ Success if links are present!

---

## 📋 Verification Checklist

Before using in production, verify:

- [ ] Plugin activated without errors
- [ ] Settings page accessible at Settings → Auto Tag Interlink
- [ ] Created test posts with shared tags
- [ ] Links automatically appear in content
- [ ] Cache clear button works
- [ ] No PHP errors in debug.log

---

## 🔒 Security Check

The plugin is production-ready and includes:
- ✅ Nonce verification for admin actions
- ✅ Capability checks (`manage_options`)
- ✅ Input sanitization for all user data
- ✅ Output escaping for all displayed data
- ✅ No SQL injection vulnerabilities
- ✅ No XSS vulnerabilities

---

## 🐛 Troubleshooting Installation

### "The plugin does not have a valid header"

**Cause:** Main plugin file is missing or damaged

**Fix:**
1. Ensure `auto-tag-interlink.php` exists in the plugin folder
2. Check that the file starts with plugin header comments
3. Re-upload the files

### "Plugin could not be activated"

**Cause:** PHP version incompatibility or missing files

**Fix:**
1. Check PHP version (need 7.4+)
2. Ensure all files are uploaded
3. Check server error logs

### "Settings page is blank"

**Cause:** Theme or plugin conflict

**Fix:**
1. Disable other plugins temporarily
2. Switch to a default WordPress theme
3. Check browser console for JavaScript errors

### "Links not appearing"

**Cause:** Plugin not enabled or no matching tags

**Fix:**
1. Check Settings → ensure plugin is enabled
2. Verify posts have matching tags
3. Clear cache and re-save post
4. Check that posts are published (not drafts)

---

## 🔄 Updating the Plugin

If you receive an updated version:

1. **Backup** your database first
2. **Deactivate** the plugin (don't delete)
3. **Delete** the old `auto-tag-interlink` folder
4. **Upload** the new version
5. **Activate** the plugin again
6. **Clear cache** via settings page

Settings are preserved in the database.

---

## ❌ Uninstalling

To completely remove the plugin:

1. **Deactivate** the plugin
2. **Delete** via WordPress admin (Plugins → Delete)
3. This removes the plugin files
4. To remove settings: Run this in phpMyAdmin:
   ```sql
   DELETE FROM wp_options WHERE option_name = 'auto_tag_interlink_settings';
   ```

**Note:** Existing links in posts will remain. They are permanent HTML changes.

---

## 📞 Support

If you encounter issues:

1. Check the [README.md](README.md) for detailed documentation
2. Review [QUICK-START.md](QUICK-START.md) for usage tips
3. Check WordPress debug log for PHP errors
4. Verify PHP version is 7.4 or higher

---

## ✅ Installation Complete!

Your plugin is now ready to automatically create internal links between your posts. Enjoy improved SEO and better user navigation! 🎉
