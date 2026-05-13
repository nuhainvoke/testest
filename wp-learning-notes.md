# WordPress Learning Notes
> Laravel dev learning WP for client work. Started: 2026-05-13

---

## Table of Contents

1. [Learning Path](#learning-path-laravel-aware)
2. [Mental Model: WP vs Laravel](#mental-model-wp-vs-laravel)
3. [Child Themes](#child-themes)
4. [Hooks System](#hooks-system)
5. [Common Action Hooks](#common-action-hooks)
6. [Adding & Editing Content](#adding--editing-content)
7. [Tools](#tools)
8. [Viewing All Content](#viewing-all-content)
9. [Database](#database)
10. [Project Folder Structure](#project-folder-structure-local-by-flywheel)
11. [Oxygen Plugin](#oxygen-plugin)

---

## Learning Path (Laravel-Aware)

1. **Hooks system** — actions vs filters, priority, `remove_action`
2. **Template hierarchy** — how WP picks which PHP file to render
3. **`WP_Query`** — fetch posts (like Eloquent but uglier)
4. **Theme structure** — `functions.php`, `style.css` header, template files
5. **Plugin structure** — when to use plugin vs theme code
6. **WP-CLI** — Artisan equivalent
7. **ACF (Advanced Custom Fields)** — custom fields for posts, clients need this always

---

## Mental Model: WP vs Laravel

| Laravel | WordPress |
|---|---|
| `.env` + `config/` | `wp-config.php` |
| Service providers / boot | `functions.php` + `add_action('init', ...)` |
| Events & Listeners | **Actions** (`do_action` / `add_action`) |
| Middleware filters | **Filters** (`apply_filters` / `add_filter`) |
| Blade templates | Template hierarchy (plain PHP files, no engine) |
| Eloquent | `WP_Query`, `$wpdb->get_results()` |
| Routes | Template hierarchy + rewrite rules |
| Artisan | WP-CLI |
| Composer packages | Plugins |
| `app/` structure | No enforced structure — chaos by default |

---

## Child Themes

**Rule:** Never edit parent theme directly. Updates wipe changes.

Child theme inherits parent, override only what you need. Laravel analogue: extending base class and overriding methods.

### Minimal Structure

```
wp-content/themes/my-child-theme/
├── style.css          ← required, declares parent
├── functions.php      ← enqueue parent styles + your additions
└── (any template file you want to override)
```

### style.css
Header comment is mandatory. `Template:` must match parent theme folder name exactly.

```css
/*
 * Theme Name: My Child Theme
 * Template: twentytwentyfour
 */
```

### functions.php
```php
<?php
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
});
```

### Override Templates
Copy file from parent → paste into child at same relative path. WP loads child version first.

```
parent: wp-content/themes/twentytwentyfour/header.php
child:  wp-content/themes/my-child-theme/header.php  ← this wins
```

**Activate:** WP Admin → Appearance → Themes → activate child theme.

---

## Hooks System

WP runs on **actions** and **filters**. No controllers, no routes file — hooks everywhere.

### Actions
Do something at a point in lifecycle. Fire-and-forget.

```php
// add_action( hook_name, callback, priority, accepted_args )
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('my-style', get_stylesheet_uri());
});
```

### Filters
Intercept and modify a value. Must return value.

```php
// add_filter( hook_name, callback, priority, accepted_args )
add_filter('the_title', function($title) {
    return strtoupper($title);
});
```

### Remove Hooks
```php
remove_action('hook_name', 'callback_function', $priority);
remove_filter('hook_name', 'callback_function', $priority);
```

> Priority default = 10. Lower number runs first. Same priority = order added.

---

## Common Action Hooks

| Hook | When it fires |
|---|---|
| `init` | WP loaded, before headers sent |
| `wp_enqueue_scripts` | Enqueue CSS/JS on frontend |
| `admin_enqueue_scripts` | Enqueue CSS/JS in admin |
| `wp_head` | Inside `<head>` tag |
| `wp_footer` | Before `</body>` |
| `save_post` | When post saved/updated |
| `template_redirect` | Before template loads (like middleware) |

---

## Adding & Editing Content

### GUI — Gutenberg Block Editor

**Access:** WP Admin → Posts → Add New (or Pages → Add New)

Each element = block (paragraph, image, heading, etc.)

- **Add block:** click `+` → search → insert
- **Drag block:** hover → grab 6-dot handle left side → drag
- **Edit block:** click → edit inline, toolbar appears at top
- **Publish:** top-right → Publish button

**Key blocks:**

| Block | Use |
|---|---|
| Paragraph | Body text |
| Heading | H1–H6 |
| Image | Single image |
| Gallery | Multiple images |
| Columns | Layout columns |
| Group | Wrapper/container |
| HTML | Raw HTML when needed |

---

### Code — PHP

```php
// Create post
$post_id = wp_insert_post([
    'post_title'   => 'My New Post',
    'post_content' => '<p>Hello world</p>',
    'post_status'  => 'publish',   // draft, publish, private
    'post_type'    => 'post',      // post, page, or custom post type
]);

// Update existing post
wp_update_post([
    'ID'           => $post_id,
    'post_title'   => 'Updated Title',
    'post_content' => '<p>Updated content</p>',
]);

// Delete post
wp_delete_post($post_id);        // moves to trash
wp_delete_post($post_id, true);  // force delete, skip trash
```

Laravel analogue: `wp_insert_post` ≈ `Post::create()`, `wp_update_post` ≈ `->update()`, `wp_delete_post` ≈ `::destroy()`

---

### Code — WP-CLI

Open site shell in Local → your site → **Open Site Shell**

```bash
# Create post
wp post create --post_title="My Post" --post_content="Hello" --post_status=publish

# List posts
wp post list

# Update post
wp post update 42 --post_title="New Title"

# Delete post
wp post delete 42

# Create page
wp post create --post_type=page --post_title="About" --post_status=publish
```

---

## Tools

- **Local by Flywheel** — local WP dev environment (already using)
- **WP-CLI** — command line for WP (like Artisan)
- **ACF** — Advanced Custom Fields plugin, essential for client work
- **Query Monitor** — debug plugin, like Laravel Debugbar

---

## Viewing All Content

**GUI:** WP Admin → Posts → All Posts (or Pages → All Pages)

**WP-CLI:**
```bash
wp post list                    # all posts
wp post list --post_type=page   # pages only
wp post list --post_status=any  # include drafts/trash
```

---

## Database

All content stored in MySQL. No flat files for posts/pages.

`app/sql/local.sql` = SQL dump (backup snapshot).

**Key tables:**

| Table | Stores |
|---|---|
| `wp_posts` | All posts, pages, custom post types |
| `wp_postmeta` | Extra fields per post (ACF data lives here) |
| `wp_users` | Users |
| `wp_options` | Site settings, plugin config |
| `wp_terms` | Categories, tags |

Laravel analogue: `wp_posts` ≈ main model table. `wp_postmeta` ≈ EAV pattern.

Access DB: Local → your site → **Database** tab.

### Connect DBeaver to Local MySQL

Local uses Unix socket internally + exposes random TCP port. Default 3306 won't work.

**Step 1 — find MySQL TCP port:**
```bash
lsof -i -P | grep mysqld
# Look for: TCP localhost:XXXXX (LISTEN) — that number is the port
```

**Step 2 — grant TCP access (MySQL 8 syntax):**
```bash
wp db cli
```
```sql
CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;
```

**Step 3 — DBeaver connection settings:**

| Field | Value |
|---|---|
| Driver | MySQL |
| Host | `127.0.0.1` |
| Port | *(port from Step 1, e.g. `10003`)* |
| Database | `local` |
| Username | `root` |
| Password | `root` |

**Step 4 — Driver properties tab, add:**

| Property | Value |
|---|---|
| `allowPublicKeyRetrieval` | `true` |
| `useSSL` | `false` |

**Important:** Site must be running in Local before connecting. Port changes if site restarts — re-run `lsof` command to get new port.

---

## Project Folder Structure (Local by Flywheel)

```
testest/
├── app/
│   ├── public/          ← WordPress root (web server points here)
│   │   ├── wp-admin/    ← Admin panel (don't touch)
│   │   ├── wp-includes/ ← WP core files (don't touch)
│   │   ├── wp-content/  ← YOUR WORK LIVES HERE
│   │   │   ├── themes/  ← themes (active + child themes)
│   │   │   ├── plugins/ ← plugins
│   │   │   └── uploads/ ← media files (images, PDFs)
│   │   └── wp-config.php ← DB credentials + constants (like .env)
│   └── sql/
│       └── local.sql    ← DB dump/backup
├── conf/
│   ├── mysql/           ← MySQL config
│   ├── nginx/           ← Nginx server config
│   └── php/             ← PHP config (version, extensions)
└── logs/
    ├── mysql/           ← DB logs
    ├── nginx/           ← server access/error logs
    └── php/             ← PHP error logs
```

**Rule:** Only edit inside `app/public/wp-content/`. Everything else = Local-managed infrastructure.

Laravel analogue:
- `wp-content/` ≈ `app/` + `resources/`
- `wp-config.php` ≈ `.env` + `config/database.php`
- `conf/` ≈ Docker/Sail config

---

## Oxygen Plugin

Visual site builder — team uses this instead of Gutenberg/Elementor.

**What it is:**
- Drag-drop UI to design pages and templates
- Outputs clean HTML/CSS (leaner than Elementor, faster sites)
- Full control over markup structure
- Dev-friendly, steeper learning curve than Elementor

**How it works:**
- Replaces default WordPress editor on pages/templates
- Open any page → click **"Edit with Oxygen"**
- Build with "elements" (divs, text, images, repeaters, etc.)

**Key concepts:**

| Concept | What it does |
|---|---|
| **Templates** | Global layouts — header, footer, single post, archive |
| **Reusable Parts** | Saved components reused across templates |
| **Dynamic Data** | Pull ACF fields, post meta, WP data into design |
| **Selectors** | Oxygen's CSS class system (instead of IDs) |
| **Design Sets** | Pre-built template kits |

**Tight integrations:** WooCommerce + ACF

**UI layout:**
- Left panel = Structure (element tree)
- Right panel = Style (CSS properties for selected element)

**vs Elementor:**
- Elementor = beginner-friendly, more bloat
- Oxygen = dev-friendly, cleaner output, more control

---

*Notes updated as learning progresses.*
