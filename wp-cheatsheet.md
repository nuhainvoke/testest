# WordPress Dev Cheatsheet
> Laravel dev reference. Code-heavy, no fluff.

---

## Template Hierarchy (most specific wins)

| URL | WP looks for (in order) |
|---|---|
| `/services/my-service/` | `single-service.php` → `single.php` → `index.php` |
| `/services/` | `archive-service.php` → `archive.php` → `index.php` |
| `/` | `front-page.php` → `home.php` → `index.php` |
| `/about/` | `page-about.php` → `page-{id}.php` → `page.php` → `index.php` |
| `/?s=query` | `search.php` → `index.php` |
| 404 | `404.php` → `index.php` |

---

## The Loop

```php
if (have_posts()) :
    while (have_posts()) : the_post();
        the_title();
        the_content();
        the_permalink();
        the_ID();
        get_the_date();
    endwhile;
endif;
```

---

## WP_Query

```php
$q = new WP_Query([
    'post_type'      => 'service',       // post, page, or CPT slug
    'posts_per_page' => 10,              // -1 = all
    'post_status'    => 'publish',
    'orderby'        => 'date',          // date, title, menu_order, rand
    'order'          => 'DESC',          // ASC | DESC
    'category_name'  => 'news',
    'tag'            => 'featured',
    'meta_key'       => 'service_price',
    'meta_value'     => '100',
    'meta_compare'   => '>',             // =, !=, >, <, >=, <=, LIKE
    'paged'          => get_query_var('paged'),
]);

if ($q->have_posts()) :
    while ($q->have_posts()) : $q->the_post();
        // loop body
    endwhile;
    wp_reset_postdata(); // always reset
endif;
```

---

## ACF

```php
get_field('field_name');                    // returns value
the_field('field_name');                    // echoes value
echo esc_html(get_field('field_name'));     // safe echo

// Repeater field
if (have_rows('repeater_field')) :
    while (have_rows('repeater_field')) : the_row();
        echo get_sub_field('sub_field_name');
    endwhile;
endif;

// Image field (returns array)
$img = get_field('hero_image');
echo $img['url'];
echo $img['alt'];
```

---

## Hooks

```php
// Action — do something at a point
add_action('init', function() { /* ... */ });
add_action('wp_enqueue_scripts', function() { /* ... */ });
add_action('save_post', function($post_id) { /* ... */ });

// Filter — intercept and modify a value, must return
add_filter('the_title', function($title) {
    return strtoupper($title);
});

// Remove
remove_action('hook_name', 'callback_fn', $priority);
remove_filter('hook_name', 'callback_fn', $priority);
```

### Common hooks

| Hook | When |
|---|---|
| `init` | WP loaded, before headers |
| `wp_enqueue_scripts` | Enqueue CSS/JS frontend |
| `admin_enqueue_scripts` | Enqueue CSS/JS in admin |
| `wp_head` | Inside `<head>` |
| `wp_footer` | Before `</body>` |
| `save_post` | Post saved/updated |
| `template_redirect` | Before template loads |
| `the_content` | Filter post content output |

---

## Enqueue Scripts & Styles

```php
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'my-style',                          // handle
        get_stylesheet_directory_uri() . '/css/main.css',
        [],                                  // dependencies
        '1.0.0'                              // version
    );

    wp_enqueue_script(
        'my-script',
        get_stylesheet_directory_uri() . '/js/main.js',
        ['jquery'],                          // dependencies
        '1.0.0',
        true                                 // load in footer
    );
});
```

---

## Register Custom Post Type

```php
add_action('init', function() {
    register_post_type('service', [
        'label'        => 'Services',
        'public'       => true,
        'show_in_menu' => true,
        'supports'     => ['title', 'editor', 'thumbnail'],
        'menu_icon'    => 'dashicons-hammer',
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'services'],
        'show_in_rest' => true,              // enable Gutenberg
    ]);
});
```

---

## Register Taxonomy

```php
add_action('init', function() {
    register_taxonomy('service_category', 'service', [
        'label'        => 'Service Categories',
        'hierarchical' => true,              // true = category, false = tag
        'rewrite'      => ['slug' => 'service-category'],
        'show_in_rest' => true,
    ]);
});
```

---

## Conditional Tags

```php
is_front_page()       // homepage
is_home()             // blog index
is_single()           // single post
is_single('slug')     // specific post
is_page('about')      // specific page by slug
is_archive()          // any archive
is_post_type_archive('service')
is_tax('service_category')
is_user_logged_in()
is_admin()
current_user_can('edit_posts')
```

---

## Escape Output (always escape before echo)

```php
esc_html($str)        // text content — strips tags
esc_attr($str)        // HTML attributes
esc_url($url)         // URLs (href, src)
esc_js($str)          // inline JS
wp_kses_post($html)   // allows safe HTML (like post content)
intval($num)          // integers
```

---

## Sanitize Input (always sanitize on save)

```php
sanitize_text_field($_POST['name'])
sanitize_email($_POST['email'])
sanitize_url($_POST['url'])
absint($_POST['count'])            // positive integer
wp_kses_post($_POST['content'])    // allows safe HTML
```

---

## Template Parts

```php
// Loads {theme}/template-parts/card-service.php
get_template_part('template-parts/card-service');

// Pass post type context
get_template_part('template-parts/card', 'service');
// looks for card-service.php first, then card.php

// Pass data (WP 5.5+)
get_template_part('template-parts/card', 'service', [
    'title' => get_the_title(),
]);
// Inside partial: $args['title']
```

---

## Common Functions

```php
get_the_ID()                        // current post ID
get_the_title($id)                  // title by ID
get_permalink($id)                  // URL by ID
get_post_meta($id, 'key', true)     // raw post meta (single value)
get_the_post_thumbnail_url($id, 'full') // featured image URL
get_template_directory_uri()        // parent theme URL
get_stylesheet_directory_uri()      // child theme URL (use this)
get_stylesheet_directory()          // child theme file path
home_url('/')                       // site URL
admin_url('edit.php')               // admin URL
```

---

## Menus

```php
// Register in functions.php
add_action('init', function() {
    register_nav_menus([
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu',
    ]);
});

// Display in template
wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => 'nav',
    'container_class'=> 'main-nav',
    'menu_class'     => 'nav-list',
    'depth'          => 2,
]);
```

---

## User Roles (default)

| Role | Can do |
|---|---|
| `subscriber` | Read only, manage own profile |
| `contributor` | Write posts, can't publish |
| `author` | Write + publish own posts |
| `editor` | Manage all posts/pages/comments |
| `administrator` | Everything |

```php
current_user_can('edit_posts')
current_user_can('manage_options')  // admin-level
wp_get_current_user()->roles        // returns array of roles
```

---

## Options API

```php
get_option('my_setting', 'default');
update_option('my_setting', 'value');
delete_option('my_setting');
```

---

## WP-CLI (run in Local site shell)

```bash
wp post list                                   # list posts
wp post list --post_type=service               # CPT
wp post create --post_title="X" --post_status=publish --post_type=service
wp post update 42 --post_title="New Title"
wp post delete 42 --force

wp user list
wp user create john john@test.com --role=editor --user_pass=pass

wp plugin list
wp plugin activate advanced-custom-fields
wp plugin deactivate advanced-custom-fields

wp theme list
wp cache flush
wp rewrite flush                               # flush permalinks

wp search-replace 'old-domain.com' 'new-domain.com' --dry-run
wp search-replace 'old-domain.com' 'new-domain.com'
```

---

## Nonces (CSRF protection)

```php
// Generate
wp_nonce_field('my_action', 'my_nonce');        // outputs hidden input
$nonce = wp_create_nonce('my_action');          // returns string

// Verify (on form submit / AJAX handler)
if (!isset($_POST['my_nonce']) || !wp_verify_nonce($_POST['my_nonce'], 'my_action')) {
    wp_die('Security check failed');
}
```

---

## AJAX

```php
// functions.php
add_action('wp_ajax_my_action', 'handle_my_action');           // logged-in
add_action('wp_ajax_nopriv_my_action', 'handle_my_action');   // guests too

function handle_my_action() {
    check_ajax_referer('my_nonce_action', 'nonce');
    $data = sanitize_text_field($_POST['data']);
    wp_send_json_success(['result' => $data]);
    // or wp_send_json_error(['message' => 'failed']);
}

// JS
fetch(ajaxurl, {
    method: 'POST',
    body: new URLSearchParams({
        action: 'my_action',
        nonce: myVars.nonce,
        data: 'hello',
    })
}).then(r => r.json()).then(console.log);
```

---

## Dev vs Client (quick ref)

```
Code  (PHP/CSS/JS)  →  Git  →  deploy to server
Content (posts, ACF values)  →  DB  →  never in Git
```

| Owns | Dev | Client |
|---|---|---|
| Theme files | ✓ | |
| CPT + ACF structure | ✓ | |
| ACF field values | | ✓ |
| Posts / pages | | ✓ |
| Media uploads | | ✓ |

---

*Keep this open while building. Last updated: 2026-05-15*
