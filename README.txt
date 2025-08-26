=== Adaptive Testimonials ===
Contributors: Kelvin Wambugu
Donate link: https://nurudigitalmarketing.com/donate
Tags: testimonials, reviews, elementor, responsive, testimonial
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A beautiful, responsive testimonials plugin that automatically adapts to your WordPress theme's branding and design. Features full Elementor integration.

== Description ==

Adaptive Testimonials is a powerful WordPress plugin that helps you showcase customer feedback beautifully and professionally. The plugin automatically adapts to your theme's colors, fonts, and design system while providing full Elementor integration for visual editing.

= Key Features =

* **Auto-Adapts to Your Theme** - Automatically inherits colors, fonts, and styling
* **Full Elementor Integration** - Custom widget with live preview and visual controls
* **Responsive Design** - Perfect on all devices (desktop, tablet, mobile)
* **Multiple Layouts** - Grid, list, and carousel display options
* **Star Ratings** - 5-star rating system with customizable colors
* **Author Photos** - Upload customer photos with automatic resizing
* **Featured Testimonials** - Mark important testimonials as featured
* **Easy Management** - Simple admin interface for adding/editing testimonials
* **Performance Optimized** - Lightweight and fast loading
* **Accessibility Ready** - ARIA labels, keyboard navigation, screen reader support
* **RTL Language Support** - Works with right-to-left languages
* **Dark Mode Compatible** - Supports dark themes automatically

= Elementor Integration =

The plugin includes a custom Elementor widget with:
* Live preview in Elementor editor
* Visual styling controls (no coding required)
* Responsive settings for all devices
* Typography and color controls
* Spacing and layout options
* Real-time customization

= Display Options =

* **Shortcode Support** - Use [testimonials] anywhere
* **Flexible Parameters** - Control limit, layout, featured status
* **Multiple Layouts** - Choose from grid, list, or carousel
* **Responsive Columns** - Different layouts for each device size
* **Custom Styling** - Full control over appearance

= Perfect For =

* Business websites showcasing customer reviews
* Service providers displaying client feedback
* E-commerce sites featuring product testimonials
* Portfolio websites with client recommendations
* Any WordPress site needing social proof

== Installation ==

= Automatic Installation =

1. Login to your WordPress admin panel
2. Navigate to Plugins > Add New
3. Search for "Adaptive Testimonials"
4. Click "Install Now" and then "Activate"

= Manual Installation =

1. Download the plugin ZIP file
2. Login to your WordPress admin panel
3. Navigate to Plugins > Add New > Upload Plugin
4. Choose the ZIP file and click "Install Now"
5. Activate the plugin after installation

= File Upload Installation =

1. Upload the `adaptive-testimonials` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Testimonials' in your admin menu to start adding testimonials

== Usage ==

= Adding Testimonials =

1. Go to WordPress Admin > Testimonials
2. Fill out the testimonial form:
   * Customer name (required)
   * Company and position (optional)
   * Testimonial content (required)
   * Star rating (1-5 stars)
   * Customer photo URL (optional)
   * Featured status (optional)
3. Click "Add Testimonial"

= Using with Elementor =

1. Open Elementor editor on any page
2. Search for "Testimonials" in the widget panel
3. Drag the widget to your desired location
4. Customize using visual controls:
   * Number of testimonials to display
   * Layout (Grid, List, Carousel)
   * Colors, typography, and spacing
   * Responsive settings for all devices
5. Preview and publish

= Using Shortcodes =

Basic usage:
`[testimonials]`

Advanced usage:
`[testimonials limit="6" featured="true" layout="grid" show_rating="true"]`

Shortcode parameters:
* `limit` - Number of testimonials to display (default: 10)
* `featured` - Show only featured testimonials ("true" or "false")
* `layout` - Display layout ("list" or "grid")
* `show_rating` - Show star ratings ("true" or "false")

== Frequently Asked Questions ==

= Does this work with Elementor? =

Yes! The plugin includes a custom Elementor widget with full visual controls, live preview, and responsive settings. You can style everything through Elementor's interface without any coding.

= Will it match my theme's design? =

Absolutely! The plugin automatically inherits your theme's colors, fonts, and styling through modern CSS custom properties. It supports WordPress 5.9+ global styles and works with any well-coded theme.

= Is it mobile responsive? =

Yes, the plugin is built with a mobile-first approach and looks perfect on all devices. The Elementor widget includes responsive controls so you can customize the layout for desktop, tablet, and mobile separately.

= Can I customize the appearance? =

Yes, you have full control over the styling:
* Use Elementor's visual controls (recommended)
* Add custom CSS through WordPress Customizer
* Modify the plugin's CSS files (advanced users)

= Does it work without Elementor? =

Yes! While Elementor integration is a key feature, the plugin works perfectly with shortcodes on any WordPress site. You can use `[testimonials]` anywhere shortcodes are supported.

= How do I add customer photos? =

In the WordPress admin testimonials page, there's an "Image URL" field where you can paste the URL of a customer photo. The plugin automatically resizes and crops the image to look professional.

= Can I mark testimonials as featured? =

Yes! When adding or editing testimonials, there's a "Featured" checkbox. You can then display only featured testimonials using the shortcode parameter `featured="true"` or the Elementor widget setting.

= Is it accessible? =

Yes, the plugin includes full accessibility support:
* ARIA labels for screen readers
* Keyboard navigation support
* High contrast mode compatibility
* Semantic HTML structure

= Does it support RTL languages? =

Yes, the plugin includes RTL (right-to-left) language support for Arabic, Hebrew, and other RTL languages.

= Will it slow down my site? =

No! The plugin is performance-optimized with:
* Lightweight, efficient code
* Minimal database queries
* CSS and JS only loaded when needed
* Compatible with all major caching plugins

== Screenshots ==

1. Admin interface for managing testimonials - clean and intuitive
2. Elementor widget with visual controls and live preview
3. Grid layout showing multiple testimonials with ratings
4. List layout perfect for single-column display
5. Mobile responsive design looks great on all devices
6. Testimonials automatically adapt to your theme's colors
7. Featured testimonials with special highlighting
8. Admin form for adding new testimonials with validation

== Changelog ==

= 1.0.0 =
* Initial release
* Full Elementor integration with custom widget
* Responsive design with mobile-first approach
* Automatic theme adaptation using CSS custom properties
* Admin management interface with form validation
* Shortcode support with flexible parameters
* Star rating system (1-5 stars)
* Featured testimonials functionality
* Author photo support with automatic resizing
* Accessibility features (ARIA labels, keyboard navigation)
* RTL language support
* Dark mode compatibility
* Performance optimizations
* Cross-browser compatibility
* Caching plugin compatibility

== Upgrade Notice ==

= 1.0.0 =
Initial release of Adaptive Testimonials. Install to start showcasing customer feedback beautifully!

== Advanced Usage ==

= Custom CSS Examples =

Add custom styling through Appearance > Customize > Additional CSS:

```css
/* Custom testimonial card background */
.at-testimonial {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Custom star colors */
.at-star {
    color: #ffd700;
}

/* Custom author name styling */
.at-author-name {
    font-weight: bold;
    color: #333;
}
```

= PHP Template Usage =

For developers who want to display testimonials in theme templates:

```php
// Display testimonials in PHP
echo do_shortcode('[testimonials limit="3" layout="grid"]');

// Or get testimonials data directly
global $wpdb;
$testimonials = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}adaptive_testimonials WHERE status = 'active' ORDER BY date_created DESC LIMIT 5");
foreach ($testimonials as $testimonial) {
    // Process testimonial data
}
```

= Hooks and Filters =

For advanced customization, developers can use these hooks:

* `at_testimonial_content` - Filter testimonial content before display
* `at_testimonial_author` - Filter author information
* `at_before_testimonial` - Action before each testimonial
* `at_after_testimonial` - Action after each testimonial

== Support ==

For support, documentation, and feature requests:

* Plugin documentation: https://nurudigitalmarketing.com/docs
* Support forum: https://nurudigitalmarketing.com/support
* GitHub repository: https://github.com/nuru-digital/adaptive-testimonials

== Requirements ==

* WordPress 5.0 or higher
* PHP 7.4 or higher
* Elementor 3.0+ (optional, for visual editing)
* Modern web browser (Chrome, Firefox, Safari, Edge)

== Browser Support ==

* Chrome (latest)
* Firefox (latest)  
* Safari (latest)
* Edge (latest)
* Mobile browsers (iOS Safari, Chrome Mobile)
* Internet Explorer 11+ (limited support)

== Privacy Policy ==

This plugin does not collect any personal data from your website visitors. All testimonial data is stored locally in your WordPress database. If you use customer photos, ensure you have permission to display them publicly.

== Credits ==

* Developed with ❤️ for the WordPress community
* Icons provided by WordPress Dashicons
* Compatible with WordPress coding standards
* Follows accessibility guidelines (WCAG 2.1)

== Donations ==

If you find this plugin helpful, consider supporting its development:
https://nurudigitalmarketing.com/donate

Your support helps maintain and improve this plugin for the entire WordPress community!