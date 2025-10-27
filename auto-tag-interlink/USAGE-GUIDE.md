# Auto Tag Interlink - Complete Usage Guide

## 📖 Table of Contents

1. [How Auto-Linking Works](#how-auto-linking-works)
2. [Settings Explained](#settings-explained)
3. [Real-World Examples](#real-world-examples)
4. [Advanced Usage](#advanced-usage)
5. [Best Practices](#best-practices)
6. [Performance Tips](#performance-tips)

---

## 🔄 How Auto-Linking Works

### The Process (Step-by-Step)

When you publish or update a post, the plugin automatically:

1. **Extracts Tags** from the post (e.g., `WordPress`, `SEO`, `Blogging`)
2. **Reads Keywords** from custom fields (if configured, e.g., `keywords` meta field)
3. **Searches Database** for other published posts that share these tags/keywords
4. **Finds Matches** in the post content (whole word matching, case-insensitive)
5. **Creates Links** by wrapping matching words with `<a href="...">` tags
6. **Distributes Links** using round-robin to link to different related posts
7. **Saves Content** with the newly inserted links

### What Gets Linked?

✅ **Plain text** containing tag/keyword words  
✅ **Inside paragraphs** (`<p>` tags)  
✅ **Inside list items** (`<li>` tags)  
✅ **Inside divs and other containers**  
⚙️ **Inside headings** (H1-H6) - Only if enabled in settings

❌ **Never links inside:**
- `<a>` tags (existing links)
- `<code>` and `<pre>` tags (code blocks)
- `<script>` and `<style>` tags

---

## ⚙️ Settings Explained

### General Settings

#### Enable Auto-Linking
- **Default:** ON
- **Purpose:** Master switch to enable/disable the plugin
- **Use Case:** Temporarily disable without deactivating the plugin

#### Link in Headings
- **Default:** OFF
- **Purpose:** Control whether to create links inside H1-H6 headings
- **Recommended:** Keep OFF for cleaner heading structure
- **Use Case:** Enable if you want maximum link coverage

#### Post Types
- **Default:** Post
- **Options:** Any public post type (Posts, Pages, Custom Post Types)
- **Purpose:** Select which content types to process
- **Example:** Enable for `post` and `page` to link across both

### Advanced Settings

#### Keyword Meta Fields
- **Default:** `keywords`
- **Format:** Comma-separated field names
- **Purpose:** Specify custom field names to use as linking keywords
- **Examples:**
  - Single field: `keywords`
  - Multiple fields: `keywords, seo_keywords, focus_words`
  - Works with: Any custom field containing comma-separated or array values

#### Max Related Posts
- **Default:** 10
- **Range:** 1-100
- **Purpose:** Limit how many related posts to find per tag
- **Performance Impact:**
  - Lower value (5-10): Faster, fewer links
  - Higher value (20-50): Slower, more links
  - Very high (50-100): Use only on powerful servers

---

## 🌍 Real-World Examples

### Example 1: Tech Blog

**Scenario:** You run a WordPress development blog

**Posts:**
- Post A: "WordPress Performance Tips" (Tags: `WordPress`, `Performance`)
- Post B: "PHP 8 Features" (Tags: `PHP`, `Performance`)
- Post C: "WordPress Security Guide" (Tags: `WordPress`, `Security`)

**Content of Post A:**
```
WordPress is the most popular CMS. Performance is crucial for user experience.
PHP powers WordPress behind the scenes.
```

**After Auto-Linking:**
```html
<a href="[Post C URL]">WordPress</a> is the most popular CMS. 
<a href="[Post B URL]">Performance</a> is crucial for user experience.
<a href="[Post B URL]">PHP</a> powers <a href="[Post C URL]">WordPress</a> behind the scenes.
```

**Result:** 4 internal links created automatically!

---

### Example 2: Recipe Blog with Keywords

**Scenario:** Food blog using custom keywords

**Post A: "Chocolate Cake Recipe"**
- Tags: `Desserts`, `Chocolate`
- Custom Field `keywords`: `baking, easy recipes, sweet`
- Content: "This easy chocolate baking recipe is perfect for beginners."

**Post B: "Baking Tips for Beginners"**
- Tags: `Baking`, `Tips`
- Content: "Master these baking techniques..."

**Post C: "Easy Desserts"**
- Tags: `Desserts`, `Easy`
- Content: "Try these easy recipes..."

**Result:** Post A links to Post B (via "baking") and Post C (via "easy" + "Desserts")

---

### Example 3: Multi-Language Blog

**Scenario:** Blog with posts in multiple languages

**English Post:**
- Tags: `Tutorial`, `WordPress`
- Content: "Learn WordPress development..."

**French Post:**
- Tags: `Tutoriel`, `WordPress`
- Content: "Apprenez le développement WordPress..."

**Result:** Both posts link each other on the shared tag "WordPress"

---

## 🚀 Advanced Usage

### Using Multiple Keyword Fields

Configure multiple sources for keywords:

```
Settings → Keyword Meta Fields: keywords, seo_keywords, related_terms
```

**Post Meta Examples:**
```php
// As comma-separated string
add_post_meta($post_id, 'keywords', 'SEO, marketing, analytics');

// As array (from plugin like ACF)
add_post_meta($post_id, 'seo_keywords', array('WordPress', 'PHP', 'MySQL'));
```

### Programmatically Adding Keywords

```php
// Add keywords when creating a post
$post_id = wp_insert_post($post_data);
add_post_meta($post_id, 'keywords', 'custom, keywords, here');

// Update keywords for existing post
update_post_meta($post_id, 'keywords', 'new, keywords');
```

### Custom Post Type Integration

```php
// Register custom post type
register_post_type('portfolio', [
    'public' => true,
    'label' => 'Portfolio',
    'supports' => ['title', 'editor', 'custom-fields'],
]);

// Enable in plugin settings:
// Settings → Post Types → Check "Portfolio"
```

### Hooking into Plugin Actions

```php
// Clear cache programmatically
AutoTagInterlink\Cache::clear_all();

// Check if plugin is enabled
$plugin = AutoTagInterlink\Plugin::get_instance();
if ($plugin->settings->is_enabled()) {
    // Plugin is active
}
```

---

## 💡 Best Practices

### 1. Tag Strategy

**✅ DO:**
- Use 3-7 tags per post
- Use consistent tag names
- Create a tag taxonomy before writing
- Use specific tags (not just generic ones)

**❌ DON'T:**
- Use 20+ tags per post (dilutes linking)
- Use single-use tags (won't find matches)
- Use very generic tags only (like "blog", "post")

### 2. Keyword Usage

**✅ DO:**
- Add 3-5 relevant keywords per post
- Use synonyms of your tags
- Include long-tail keywords
- Keep keywords natural and readable

**❌ DON'T:**
- Keyword stuff (looks spammy)
- Use irrelevant keywords
- Duplicate tags as keywords (redundant)

### 3. Content Writing

**✅ DO:**
- Write naturally - let the plugin find matches
- Use your tags/keywords naturally in content
- Write comprehensive posts (more linking opportunities)
- Review auto-links after publishing

**❌ DON'T:**
- Force keywords unnaturally
- Over-optimize for linking
- Rely solely on auto-linking (manual links still valuable)

### 4. Performance Optimization

**✅ DO:**
- Start with max 10-15 related posts
- Clear cache monthly
- Monitor post save times
- Use caching plugin (W3 Total Cache, WP Rocket)

**❌ DON'T:**
- Set max related posts to 100 on shared hosting
- Never clear cache
- Process thousands of posts at once

---

## ⚡ Performance Tips

### For Small Sites (< 100 posts)

- Max Related Posts: 10-20
- No special configuration needed
- Cache rarely needs clearing

### For Medium Sites (100-1,000 posts)

- Max Related Posts: 10-15
- Clear cache monthly
- Consider object caching (Redis/Memcached)
- Monitor database performance

### For Large Sites (1,000+ posts)

- Max Related Posts: 5-10
- Use object caching (mandatory)
- Clear cache weekly
- Consider batch processing (update posts in groups)
- Monitor server resources

### Cache Management Strategy

**Clear cache when:**
- After bulk tag changes
- After importing posts
- If links seem outdated
- Once per month (maintenance)

**Don't clear cache when:**
- Publishing single posts (auto-clears that post)
- Making content edits
- Changing theme/design

### Database Optimization

```sql
-- Run occasionally to optimize
OPTIMIZE TABLE wp_options;
OPTIMIZE TABLE wp_posts;
OPTIMIZE TABLE wp_term_relationships;
```

---

## 🎯 Common Use Cases

### Use Case 1: Blog with Series
Create post series that auto-link:
- Tag all series posts with series name
- Each post automatically links to others in series

### Use Case 2: Documentation Site
- Tag docs by feature/topic
- Related docs auto-link for easy navigation
- Users discover related information automatically

### Use Case 3: Magazine/News Site
- Tag articles by topic and author
- Related articles auto-link
- Keeps readers engaged longer

### Use Case 4: Portfolio Site
- Tag projects by service type
- Similar projects auto-link
- Showcases related work

### Use Case 5: E-Commerce Blog
- Tag product-related posts
- Cross-link product reviews and guides
- Improve SEO and conversions

---

## 📊 Monitoring Results

### Check Link Coverage

Periodically review:
1. Open recent posts
2. Check source code for `<a>` tags
3. Verify links are relevant
4. Ensure no broken links

### SEO Impact

Monitor (after 2-3 months):
- Internal link count per page
- Average session duration (should increase)
- Pages per session (should increase)
- Bounce rate (should decrease)

### Performance Metrics

Watch for:
- Post save time (should be < 2 seconds)
- Database query count
- Server memory usage
- Cache hit rate

---

## 🔍 Troubleshooting

### "No links are being created"

**Check:**
1. Plugin enabled in settings
2. Posts have matching tags
3. Posts are published (not drafts)
4. Words exist in content exactly as tag names
5. Clear cache and try again

### "Too many links"

**Solutions:**
1. Reduce "Max Related Posts" to 5
2. Use more specific tags
3. Remove generic tags

### "Wrong posts being linked"

**Solutions:**
1. Review tag strategy - too generic?
2. Use more specific tags
3. Clear cache
4. Check post dates (linking to old content?)

### "Slow post saving"

**Solutions:**
1. Reduce max related posts
2. Enable object caching
3. Clear old transients
4. Upgrade hosting

---

## ✅ Quick Reference

| Task | Action |
|------|--------|
| Enable plugin | Settings → Auto Tag Interlink → Enable |
| Change post types | Settings → Post Types → Select |
| Clear cache | Settings → Clear Cache button |
| Add keywords | Add custom field `keywords` to post |
| Disable temporarily | Uncheck "Enable Auto-Linking" |
| Increase links | Increase "Max Related Posts" |
| Decrease links | Decrease "Max Related Posts" |

---

**Happy Auto-Linking! 🎉**

For more information, see [README.md](README.md) for full documentation.
