# WordPress Local Development Setup

This is a WordPress development environment set up using [Local by Flywheel](https://localwp.com/), a powerful local WordPress development tool that simulates real hosting environments on your machine.

## 📁 Project Structure

```
testest/
├── app/                      # WordPress application root
│   ├── public/              # WordPress public files (web root)
│   │   ├── wp-admin/        # WordPress admin panel
│   │   ├── wp-content/      # Themes, plugins, and uploads
│   │   ├── wp-includes/     # WordPress core functionality
│   │   ├── wp-*.php         # Core WordPress files
│   │   └── index.php        # Main entry point
│   └── (other app files)
├── conf/                     # Configuration files for services
│   ├── mysql/               # MySQL database configuration
│   ├── nginx/               # Nginx web server configuration
│   └── php/                 # PHP-FPM configuration
├── logs/                     # Service log files
│   ├── mysql/               # Database logs
│   ├── nginx/               # Web server logs
│   ├── php/                 # PHP logs
│   └── mailpit/             # Email capture logs
├── sql/                      # Database files
│   └── local.sql            # SQL backups/exports
└── README.md                # This file
```

## 🚀 Getting Started

### 1. Start the Local Environment
- Open **Local** application
- Select the **testest** site
- Click **Start** (or use the Start button in Local's interface)

### 2. Access Your WordPress Site
Once the environment is running:
- **Site URL:** Available in Local's interface (typically `http://testest.local`)
- **WordPress Admin:** `http://testest.local/wp-admin`
- **Database:** Access via Local's database tab

### 3. Login to WordPress Admin
- Use the credentials set up in Local
- Found in Local's dashboard under "site info"

## ⚙️ Configuration

### WordPress Configuration (`app/public/wp-config.php`)
The main WordPress configuration file containing:
- Database connection settings
- Authentication keys and salts
- Debug settings
- File permissions
- Other WordPress constants

**Note:** In Local environments, most settings are pre-configured. Avoid modifying unless you know what you're doing.

### Database (`conf/mysql/`)
- **Location:** `conf/mysql/my.cnf.hbs`
- **Database Name:** `local` (typically)
- **Access:** Through Local's interface or database tools
- **Backups:** Located in `sql/local.sql`

### Web Server (`conf/nginx/`)
- **Main Config:** `conf/nginx/nginx.conf.hbs`
- **Site Config:** `conf/nginx/site.conf.hbs`
- Handles routing and URL rewriting for WordPress

### PHP (`conf/php/`)
- **FPM Config:** `conf/php/php-fpm.conf.hbs`
- **PHP Settings:** `conf/php/php.ini.hbs`
- Adjust upload limits, memory, execution time here if needed

## 📝 Key WordPress Directories

### `wp-content/`
This is where all your custom code lives:

```
wp-content/
├── themes/              # Your WordPress themes
│   └── your-theme/      # Custom theme folder
├── plugins/             # Your WordPress plugins
│   └── your-plugin/     # Custom plugin folder
├── uploads/             # Media library files
├── mu-plugins/          # Must-use plugins (auto-load)
└── languages/           # Translation files
```

**Important:** Only modify files in `wp-content/`. Never directly edit WordPress core files.

### `wp-admin/` and `wp-includes/`
- **Don't modify these** - They're WordPress core files
- Updates will overwrite any changes you make

## 🛠️ Common Tasks

### Access the Database
1. In Local app, click the site
2. Go to **Database** tab
3. Click **Open Adminer** or **View Database** to manage your database

### Export/Backup Database
1. Go to **Database** in Local
2. Click **Export** to download SQL file
3. Save to `sql/` folder for version control

### Import Database
1. Place SQL file in `sql/`
2. In Local, go to **Database** tab
3. Click **Import** and select your file

### Enable WordPress Debug Mode
Edit `app/public/wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```
Logs will appear in `app/public/wp-content/debug.log`

### View Emails Sent
Local includes **Mailpit** for capturing emails:
- Access through Local's **Utilities** tab
- Useful for testing forms and notifications

### Check Logs
- **PHP Errors:** `logs/php/`
- **Nginx/Web Server:** `logs/nginx/`
- **Database:** `logs/mysql/`
- **WordPress Debug:** `app/public/wp-content/debug.log` (if debug enabled)

## 🎨 Working with Themes

### Understanding Theme Structure
```
themes/your-theme/
├── functions.php        # Theme functions and hooks
├── style.css            # Main stylesheet
├── index.php            # Main template file
├── header.php           # Header template
├── footer.php           # Footer template
├── home.php             # Homepage template
├── single.php           # Single post template
├── archive.php          # Archive template
├── page.php             # Page template
├── 404.php              # 404 template
└── assets/              # CSS, JS, images
```

### Installing a Theme
1. Download theme (from WordPress.org or purchased)
2. Place in `app/public/wp-content/themes/`
3. Go to WordPress Admin → Appearance → Themes
4. Activate the theme

### Creating a Custom Theme
1. Create a folder: `app/public/wp-content/themes/my-theme/`
2. Create minimal `style.css` with theme header:
```css
/*
Theme Name: My Theme
Theme URI: http://example.com
Description: My custom WordPress theme
Version: 1.0
Author: Your Name
*/
```
3. Create `index.php` with basic template structure
4. Go to Admin → Appearance → Themes and activate

## 🔌 Working with Plugins

### Installing a Plugin
1. Download plugin (from WordPress.org or elsewhere)
2. Place in `app/public/wp-content/plugins/`
3. Go to WordPress Admin → Plugins
4. Click Activate

### Creating a Custom Plugin
1. Create folder: `app/public/wp-content/plugins/my-plugin/`
2. Create `my-plugin.php` with plugin header:
```php
<?php
/*
Plugin Name: My Plugin
Plugin URI: http://example.com
Description: My custom plugin
Version: 1.0
Author: Your Name
*/

// Plugin code here
```
3. Go to Admin → Plugins and activate

## 📚 WordPress Hooks & Concepts

### Actions vs Filters
- **Actions:** Allow you to run custom code at specific WordPress events
- **Filters:** Allow you to modify and return data

Example in `functions.php`:
```php
// Action: Run code when post is published
add_action('publish_post', 'my_function');

// Filter: Modify post content
add_filter('the_content', 'my_filter_function');
```

### The Loop
WordPress's core mechanism for displaying posts:
```php
<?php
if (have_posts()) :
    while (have_posts()) : the_post();
        echo get_the_title();
    endwhile;
endif;
```

## 🐛 Troubleshooting

### Site Not Loading
- ✅ Restart Local environment
- ✅ Check logs in `logs/` folder
- ✅ Verify database is running via Local

### White Screen of Death (WSOD)
- ✅ Enable debug mode in `wp-config.php`
- ✅ Check `app/public/wp-content/debug.log`
- ✅ Disable all plugins via admin or database

### Database Connection Error
- ✅ Ensure MySQL is running in Local
- ✅ Check credentials in `wp-config.php`
- ✅ Verify via Local's Database tab

### Memory/Timeout Issues
- ✅ Increase PHP memory in `conf/php/php.ini.hbs`
- ✅ Restart PHP-FPM via Local

### Missing Uploads or Assets
- ✅ Check file permissions
- ✅ Verify `wp-content/uploads/` folder exists
- ✅ Check nginx config in `conf/nginx/site.conf.hbs`

## 📖 Learning Resources

### Official WordPress Documentation
- [WordPress Codex](https://codex.wordpress.org/) - Comprehensive reference
- [WordPress Developer Handbook](https://developer.wordpress.org/) - Modern development guide
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)

### Essential Hooks & Functions
- [Action Hooks Reference](https://adninistrator.wordpress.org/reference/hooks/action/)
- [Filter Hooks Reference](https://developer.wordpress.org/reference/hooks/filter/)
- [Template Tags](https://developer.wordpress.org/plugins/templates/template-tags/)

### Local by Flywheel Resources
- [Local Documentation](https://localwp.com/help-docs/)
- [Community Forum](https://community.localwp.com/)

## 💾 Version Control Best Practices

### What to Commit
- ✅ Theme files (`wp-content/themes/`)
- ✅ Custom plugins (`wp-content/plugins/`)
- ✅ Custom configurations
- ✅ README.md and documentation

### What NOT to Commit
- ❌ `app/public/wp-admin/` - WordPress core
- ❌ `app/public/wp-includes/` - WordPress core
- ❌ `wp-content/uploads/` - User-generated media
- ❌ `wp-content/plugins/` - Third-party plugins (document them instead)
- ❌ `wp-content/cache/` - Cache files
- ❌ `.env` - Sensitive data

### Create a `.gitignore`
```
# WordPress core
/wp-admin/
/wp-includes/
wp-config.php

# Uploads and cache
/wp-content/uploads/
/wp-content/cache/
/wp-content/backup/

# Node modules and build files
node_modules/
dist/
build/

# System files
.DS_Store
Thumbs.db
*.log

# Local environment
.env
.env.local
```

## 🚀 Next Steps

1. **Explore the WordPress Admin** - Familiarize yourself with menus and settings
2. **Create a Simple Theme** - Build a basic custom theme to understand structure
3. **Create a Simple Plugin** - Write a plugin that adds custom functionality
4. **Read WordPress Hooks** - Understand actions and filters
5. **Build Something** - Create a custom post type or integrate an API

## 📞 Getting Help

- **Local App Help:** Built-in help and documentation in Local
- **WordPress Support:** [wordpress.org support forums](https://wordpress.org/support/forums/)
- **Stack Overflow:** Tag your questions with `wordpress` and `php`
- **Slack Communities:** WordPress community Slack channels

---

**Happy coding! 🎉**

Last updated: May 2026
