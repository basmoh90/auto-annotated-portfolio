# Auto Tag Interlink

Automatically inserts internal links between posts based on shared Tags and optional keywords. Production-ready, OOP, namespaced, and uses WordPress best practices.

## Features
- Auto-links words in post content that match the post's tags and optional keyword fields
- Round-robin distribution across multiple related posts for each keyword
- Skips linking inside `<a>`, `<code>`, `<pre>`, `<script>`, `<style>`
- Optional toggle to allow linking within headings (H1–H6)
- Caches related post lookups using transients; bulk invalidation via version bump
- Admin settings under Settings → Auto Tag Interlink

## Installation
1. Download the plugin folder `auto-tag-interlink` or build a ZIP.
2. Upload to `wp-content/plugins/` or via Plugins → Add New → Upload Plugin.
3. Activate the plugin.
4. Visit Settings → Auto Tag Interlink to configure.

## How It Works
- On post save (publish/update), the plugin gathers the post's Tags and values from custom meta fields (default: `keywords`, comma-separated).
- It finds related posts per token using:
  1) posts sharing the Tag by name;
  2) posts whose configured meta fields contain the token (LIKE query) if more results are needed.
- It parses the post content with `DOMDocument` and replaces occurrences of tokens in text nodes with internal links to related posts. It avoids prohibited tags and optionally headings.
- Round-robin selection distributes links among the related posts, with unlimited repetitions allowed.

## Settings
- Enable auto-linking
- Enable linking inside headings
- Included post types (default: post)
- Keyword meta field names (default: `keywords`; comma-separated)
- Max related posts per tag (default: 10)
- Clear Cache button to invalidate all cached related lookups

## Performance
- Related lookups are cached per token using `get_transient`/`set_transient`.
- Global cache is invalidated when tags, configured keyword meta fields, or plugin settings change.

## Known Limitations
- Token matching is literal and case-insensitive; pluralization and stemming are not handled.
- Very short tokens (less than 2 characters) are ignored.
- If your content has heavy shortcodes that mutate HTML after save, prefer applying links on the final content render via `the_content` filter; this plugin applies on save for performance.

## Manual Test Steps
1. Ensure you have several published posts with overlapping Tags and/or a `keywords` custom field containing comma-separated values.
2. In Settings → Auto Tag Interlink, keep defaults or adjust options and save. Consider enabling linking inside headings for testing.
3. Edit a post that contains those token words in the content and save/update it.
4. Open the post on the frontend and verify that matching token words are now linked to related posts. Links should alternate among related posts for repeated matches.
5. Confirm links do not appear inside existing links or code blocks. Toggle the headings setting to verify behavior.
6. Use the Clear Cache button, then update a post to repopulate caches.

## Uninstall
- Deactivate and remove the plugin from the Plugins screen. Transients expire automatically; options stored:
  - `ati_options`
  - `ati_cache_version`
