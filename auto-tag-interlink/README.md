# Auto Tag Interlink WordPress Plugin

**Version:** 1.0.0  
**Requires at least:** WordPress 5.0  
**Tested up to:** WordPress 6.4  
**Requires PHP:** 7.4+  
**License:** GPL v2 or later

## Description

Auto Tag Interlink is a powerful WordPress plugin that automatically creates internal links between posts based on shared tags and optional keywords. It improves SEO, enhances user navigation, and helps distribute link equity throughout your site—all without manual intervention.

### Key Features

✅ **Automatic Internal Linking** - Creates links between posts with matching tags and keywords  
✅ **Unlimited Links** - No restrictions on the number of links per post  
✅ **Smart Distribution** - Uses round-robin algorithm to distribute links among related posts  
✅ **HTML-Safe Parsing** - Uses DOMDocument to safely parse and modify content without breaking HTML  
✅ **Performance Optimized** - Implements WordPress transient caching for fast lookups  
✅ **Highly Configurable** - Extensive settings to customize behavior  
✅ **SEO Friendly** - Helps search engines discover related content on your site  
✅ **Clean Code** - Built with OOP, namespaces, and WordPress best practices

---

## Installation

### Method 1: Manual Installation

1. Download the plugin ZIP file
2. Extract the `auto-tag-interlink` folder
3. Upload the folder to `/wp-content/plugins/` directory
4. Activate the plugin through the 'Plugins' menu in WordPress
5. Go to **Settings → Auto Tag Interlink** to configure

### Method 2: WordPress Admin

1. Go to **Plugins → Add New** in your WordPress admin
2. Click **Upload Plugin**
3. Choose the plugin ZIP file
4. Click **Install Now**
5. Activate the plugin
6. Configure settings at **Settings → Auto Tag Interlink**

---

## How It Works

### Automatic Linking Process

When a post is **published or updated**, the plugin:

1. **Detects Tags & Keywords** - Extracts all tags assigned to the post and reads keywords from custom fields (if configured)
2. **Finds Related Posts** - Searches for other published posts that share the same tags/keywords
3. **Creates Internal Links** - Automatically inserts links into the post content when tag/keyword text is found
4. **Distributes Links** - Uses round-robin to ensure links are distributed among multiple related posts
5. **Saves Changes** - Updates the post content with the new internal links

### Smart Linking Rules

The plugin follows these intelligent rules:

- ✅ **Links unlimited occurrences** - All matching words are linked (no limit)
- ❌ **Skips certain HTML elements** - Never links inside `<a>`, `<code>`, `<pre>`, `<script>`, or `<style>` tags
- ⚙️ **Heading control** - Optional toggle to enable/disable linking inside H1-H6 headings
- 🚫 **No self-linking** - Never links to the current post itself
- 🔄 **Round-robin distribution** - Distributes links across multiple related posts
- 🔒 **HTML-safe** - Uses DOMDocument for safe parsing without breaking your HTML structure

---

## Settings Overview

Access settings at: **Settings → Auto Tag Interlink**

### General Settings

| Setting | Description | Default |
|---------|-------------|---------|
| **Enable Auto-Linking** | Master on/off switch for the plugin | ✅ Enabled |
| **Link in Headings** | Allow links inside H1-H6 heading tags | ❌ Disabled |
| **Post Types** | Select which post types to enable auto-linking | Post |

### Advanced Settings

| Setting | Description | Default |
|---------|-------------|---------|
| **Keyword Meta Fields** | Comma-separated custom field names for keywords | `keywords` |
| **Max Related Posts** | Maximum related posts to find per tag (1-100) | 10 |

### Cache Management

- **Clear Cache Button** - Clears all cached related post lookups
- Cache automatically clears on:
  - Post update
  - Tag changes
  - Settings changes

---

## Usage Examples

### Example 1: Basic Tag-Based Linking

**Post A** has tags: `WordPress`, `PHP`, `Development`  
**Post B** has tags: `WordPress`, `Themes`  
**Post C** has tags: `PHP`, `Security`

When Post A is saved, the plugin will:
- Find Posts B and C (they share tags)
- Insert links to Posts B and C when "WordPress" and "PHP" appear in Post A's content

### Example 2: Using Custom Keywords

Add a custom field named `keywords` to your post with value: `SEO, performance, optimization`

The plugin will:
- Search for posts with matching tags OR these keywords
- Create links when "SEO", "performance", or "optimization" appear in content

### Example 3: Multiple Post Types

Enable linking for `post` and `page`:
- Posts can link to pages
- Pages can link to posts
- Internal link network spans entire site

---

## Performance & Caching

### Caching Strategy

The plugin uses **WordPress Transients** to cache related post lookups for 24 hours. This means:

- First lookup per post: Database query executed
- Subsequent lookups: Served from cache (very fast)
- Cache automatically refreshes when needed

### Cache Auto-Clearing

Cache is automatically cleared when:
- Post is updated
- Post tags are modified
- Plugin settings are changed

### Manual Cache Clearing

Go to **Settings → Auto Tag Interlink** and click the **Clear Cache** button to force-clear all cached data.

---

## Technical Details

### File Structure

```
auto-tag-interlink/
├── auto-tag-interlink.php     # Main plugin file
├── includes/
│   ├── class-plugin.php        # Bootstrap class
│   ├── class-linker.php        # Core linking logic
│   ├── class-admin.php         # Admin settings page
│   ├── class-cache.php         # Cache management
│   └── class-settings.php      # Settings handler
├── assets/
│   ├── css/
│   │   └── admin.css           # Admin styles
│   └── js/
│       └── admin.js            # Admin JavaScript
└── README.md                   # Documentation
```

### Code Standards

- **PHP 7.4+** with type hints where appropriate
- **PSR-4 autoloading** with namespaces
- **WordPress Coding Standards** compliant
- **OOP architecture** with single responsibility principle
- **Security hardened** with proper sanitization and capability checks

### WordPress Hooks Used

- `plugins_loaded` - Initialize plugin
- `transition_post_status` - Detect post publish/update
- `set_object_terms` - Detect tag changes
- `admin_menu` - Add settings page
- `admin_init` - Register settings
- `admin_enqueue_scripts` - Load admin assets

---

## Known Limitations

1. **Post Content Only** - Only processes `post_content`, not custom fields or meta
2. **Published Posts Only** - Only links to published posts (drafts excluded)
3. **No Excerpt Linking** - Post excerpts are not processed
4. **English-Optimized** - Word boundary matching works best with English text
5. **No Link Removal** - Plugin adds links but doesn't remove existing ones
6. **Performance** - Very large posts (50,000+ words) may take longer to process

---

## Testing Instructions

### Manual Testing Steps

#### Test 1: Basic Functionality

1. Create **Post A** with tags: `WordPress`, `Plugin`
2. Add content containing the words "WordPress" and "Plugin"
3. Create **Post B** with tag: `WordPress`
4. Add content containing "WordPress"
5. Create **Post C** with tag: `Plugin`
6. Save Post A
7. **Expected**: Post A should now contain links to Posts B and C

#### Test 2: Keyword Linking

1. Create a post with custom field `keywords` = `SEO, performance`
2. Create another post with tag `SEO`
3. In first post's content, write "SEO is important"
4. Save the first post
5. **Expected**: "SEO" should link to the second post

#### Test 3: Settings

1. Go to **Settings → Auto Tag Interlink**
2. Disable "Enable Auto-Linking"
3. Save settings
4. Create/update a post
5. **Expected**: No new links should be created

#### Test 4: Heading Links

1. Ensure "Link in Headings" is disabled
2. Create a post with `<h2>WordPress Tutorial</h2>`
3. Create another post with tag `WordPress`
4. Save first post
5. **Expected**: "WordPress" in heading should NOT be linked
6. Enable "Link in Headings" and save again
7. **Expected**: Now "WordPress" in heading SHOULD be linked

#### Test 5: Cache Clearing

1. Create several linked posts
2. Go to settings page
3. Click "Clear Cache"
4. **Expected**: Success message appears

### Automated Testing (Optional)

For developers who want to add automated tests:

```bash
# Install PHPUnit and WP test suite
# Then run:
phpunit
```

---

## Troubleshooting

### Links Not Being Created

**Possible causes:**
- Plugin is disabled in settings
- Posts don't share any tags/keywords
- Content doesn't contain matching tag/keyword text
- Posts are not published

**Solutions:**
1. Check **Settings → Auto Tag Interlink** - ensure enabled
2. Verify posts have matching tags
3. Clear cache and re-save post

### Performance Issues

**Possible causes:**
- Too many related posts
- Large post content
- Outdated cache

**Solutions:**
1. Reduce "Max Related Posts" setting
2. Clear cache regularly
3. Consider server caching (Redis, Memcached)

### Links Breaking HTML

**Possible causes:**
- Rare edge cases with malformed HTML

**Solutions:**
1. Check post content for HTML errors
2. Use WordPress HTML editor to clean content
3. Report issue with example content

---

## Frequently Asked Questions

### Does this work with custom post types?

Yes! Just enable them in **Settings → Post Types**.

### Can I use multiple keyword fields?

Yes! Enter comma-separated field names: `keywords, seo_keywords, focus_words`

### Will it link to draft posts?

No, only published posts are linked.

### Does it remove old links?

No, it only adds new links. Existing links remain unchanged.

### How often does cache refresh?

Cache expires after 24 hours or when manually cleared.

### Can I limit links per post?

Not currently, but this feature may be added in future versions.

---

## Changelog

### Version 1.0.0 - 2024
- Initial release
- Automatic tag-based linking
- Custom keyword support
- Admin settings page
- Performance caching
- Round-robin link distribution
- HTML-safe parsing with DOMDocument

---

## Support & Contributions

### Support

For issues, feature requests, or questions:
- Open an issue on GitHub
- Contact: [your-email@example.com]

### Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Submit a pull request

---

## License

This plugin is licensed under the **GPL v2 or later**.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

---

## Credits

Developed with ❤️ for the WordPress community.

**Built using:**
- PHP 7.4+
- WordPress 5.0+
- DOMDocument for HTML parsing
- WordPress Transients API for caching

---

## Version History

- **1.0.0** - Initial public release

---

**Thank you for using Auto Tag Interlink!** 🚀
