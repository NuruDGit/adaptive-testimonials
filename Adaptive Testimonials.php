<?php
/**
 * Plugin Name: Adaptive Testimonials
 * Plugin URI: https://nurudigitalmarketing.com
 * Description: A clean, responsive testimonials plugin that adapts to your theme's branding and design.
 * Version: 1.0.0
 * Author: Kelvin Wambugu
 * License: GPL v2 or later
 * Text Domain: adaptive-testimonials
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('AT_VERSION', '1.0.0');

class AdaptiveTestimonials {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_shortcode('testimonials', array($this, 'testimonials_shortcode'));
        
        // Admin hooks
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('wp_ajax_save_testimonial', array($this, 'save_testimonial'));
        add_action('wp_ajax_delete_testimonial', array($this, 'delete_testimonial'));
        
        // Elementor integration
        add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widget'));
        add_action('elementor/elements/categories_registered', array($this, 'register_elementor_category'));
    }
    
    public function init() {
        $this->create_table();
        load_plugin_textdomain('adaptive-testimonials', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style(
            'adaptive-testimonials-style',
            AT_PLUGIN_URL . 'assets/style.css',
            array(),
            AT_VERSION
        );
        
        wp_enqueue_script(
            'adaptive-testimonials-script',
            AT_PLUGIN_URL . 'assets/script.js',
            array('jquery'),
            AT_VERSION,
            true
        );
    }
    
    public function admin_enqueue_scripts($hook) {
        if ('toplevel_page_adaptive-testimonials' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'adaptive-testimonials-admin',
            AT_PLUGIN_URL . 'assets/admin.css',
            array(),
            AT_VERSION
        );
        
        wp_enqueue_script(
            'adaptive-testimonials-admin',
            AT_PLUGIN_URL . 'assets/admin.js',
            array('jquery'),
            AT_VERSION,
            true
        );
        
        wp_localize_script('adaptive-testimonials-admin', 'atAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('at_nonce')
        ));
    }
    
    public function create_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'adaptive_testimonials';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            company varchar(100),
            position varchar(100),
            content text NOT NULL,
            rating tinyint(1) DEFAULT 5,
            image_url varchar(255),
            date_created datetime DEFAULT CURRENT_TIMESTAMP,
            is_featured tinyint(1) DEFAULT 0,
            status varchar(20) DEFAULT 'active',
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public function admin_menu() {
        add_menu_page(
            __('Testimonials', 'adaptive-testimonials'),
            __('Testimonials', 'adaptive-testimonials'),
            'manage_options',
            'adaptive-testimonials',
            array($this, 'admin_page'),
            'dashicons-format-quote',
            30
        );
    }
    
    public function admin_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'adaptive_testimonials';
        
        // Handle form submission
        if (isset($_POST['submit_testimonial']) && wp_verify_nonce($_POST['at_nonce'], 'save_testimonial')) {
            $this->handle_form_submission();
        }
        
        $testimonials = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_created DESC");
        
        ?>
        <div class="wrap">
            <h1><?php _e('Testimonials Management', 'adaptive-testimonials'); ?></h1>
            
            <div class="at-admin-container">
                <div class="at-form-section">
                    <h2><?php _e('Add New Testimonial', 'adaptive-testimonials'); ?></h2>
                    <form method="post" class="at-form">
                        <?php wp_nonce_field('save_testimonial', 'at_nonce'); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th><label for="name"><?php _e('Name', 'adaptive-testimonials'); ?></label></th>
                                <td><input type="text" id="name" name="name" required class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="company"><?php _e('Company', 'adaptive-testimonials'); ?></label></th>
                                <td><input type="text" id="company" name="company" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="position"><?php _e('Position', 'adaptive-testimonials'); ?></label></th>
                                <td><input type="text" id="position" name="position" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="content"><?php _e('Testimonial', 'adaptive-testimonials'); ?></label></th>
                                <td><textarea id="content" name="content" required rows="5" class="large-text"></textarea></td>
                            </tr>
                            <tr>
                                <th><label for="rating"><?php _e('Rating', 'adaptive-testimonials'); ?></label></th>
                                <td>
                                    <select id="rating" name="rating">
                                        <option value="5">5 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="2">2 Stars</option>
                                        <option value="1">1 Star</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="image_url"><?php _e('Image URL', 'adaptive-testimonials'); ?></label></th>
                                <td><input type="url" id="image_url" name="image_url" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="is_featured"><?php _e('Featured', 'adaptive-testimonials'); ?></label></th>
                                <td><input type="checkbox" id="is_featured" name="is_featured" value="1"></td>
                            </tr>
                        </table>
                        
                        <?php submit_button(__('Add Testimonial', 'adaptive-testimonials'), 'primary', 'submit_testimonial'); ?>
                    </form>
                </div>
                
                <div class="at-list-section">
                    <h2><?php _e('Existing Testimonials', 'adaptive-testimonials'); ?></h2>
                    <div class="at-shortcode-info">
                        <p><strong><?php _e('Shortcode:', 'adaptive-testimonials'); ?></strong> [testimonials]</p>
                        <p><strong><?php _e('Options:', 'adaptive-testimonials'); ?></strong> [testimonials limit="3" featured="true" layout="grid"]</p>
                    </div>
                    
                    <?php if ($testimonials): ?>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php _e('Name', 'adaptive-testimonials'); ?></th>
                                    <th><?php _e('Company', 'adaptive-testimonials'); ?></th>
                                    <th><?php _e('Content', 'adaptive-testimonials'); ?></th>
                                    <th><?php _e('Rating', 'adaptive-testimonials'); ?></th>
                                    <th><?php _e('Featured', 'adaptive-testimonials'); ?></th>
                                    <th><?php _e('Actions', 'adaptive-testimonials'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($testimonials as $testimonial): ?>
                                    <tr>
                                        <td><?php echo esc_html($testimonial->name); ?></td>
                                        <td><?php echo esc_html($testimonial->company); ?></td>
                                        <td><?php echo esc_html(wp_trim_words($testimonial->content, 10)); ?></td>
                                        <td><?php echo str_repeat('★', $testimonial->rating); ?></td>
                                        <td><?php echo $testimonial->is_featured ? '✓' : ''; ?></td>
                                        <td>
                                            <button class="button button-small delete-testimonial" data-id="<?php echo $testimonial->id; ?>">
                                                <?php _e('Delete', 'adaptive-testimonials'); ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p><?php _e('No testimonials found. Add your first one above!', 'adaptive-testimonials'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function handle_form_submission() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'adaptive_testimonials';
        
        $name = sanitize_text_field($_POST['name']);
        $company = sanitize_text_field($_POST['company']);
        $position = sanitize_text_field($_POST['position']);
        $content = sanitize_textarea_field($_POST['content']);
        $rating = intval($_POST['rating']);
        $image_url = esc_url_raw($_POST['image_url']);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'company' => $company,
                'position' => $position,
                'content' => $content,
                'rating' => $rating,
                'image_url' => $image_url,
                'is_featured' => $is_featured
            ),
            array('%s', '%s', '%s', '%s', '%d', '%s', '%d')
        );
        
        if ($result) {
            echo '<div class="notice notice-success"><p>' . __('Testimonial added successfully!', 'adaptive-testimonials') . '</p></div>';
        }
    }
    
    public function delete_testimonial() {
        if (!wp_verify_nonce($_POST['nonce'], 'at_nonce')) {
            wp_die('Security check failed');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'adaptive_testimonials';
        
        $id = intval($_POST['id']);
        $result = $wpdb->delete($table_name, array('id' => $id), array('%d'));
        
        if ($result) {
            wp_send_json_success('Testimonial deleted successfully');
        } else {
            wp_send_json_error('Failed to delete testimonial');
        }
    }
    
    public function testimonials_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'featured' => 'false',
            'layout' => 'list', // 'list' or 'grid'
            'show_rating' => 'true'
        ), $atts);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'adaptive_testimonials';
        
        $sql = "SELECT * FROM $table_name WHERE status = 'active'";
        
        if ($atts['featured'] === 'true') {
            $sql .= " AND is_featured = 1";
        }
        
        $sql .= " ORDER BY date_created DESC LIMIT " . intval($atts['limit']);
        
        $testimonials = $wpdb->get_results($sql);
        
        if (!$testimonials) {
            return '<p>' . __('No testimonials found.', 'adaptive-testimonials') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="at-testimonials at-layout-<?php echo esc_attr($atts['layout']); ?>">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="at-testimonial">
                    <?php if ($testimonial->image_url): ?>
                        <div class="at-testimonial-image">
                            <img src="<?php echo esc_url($testimonial->image_url); ?>" alt="<?php echo esc_attr($testimonial->name); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="at-testimonial-content">
                        <blockquote class="at-testimonial-text">
                            <?php echo esc_html($testimonial->content); ?>
                        </blockquote>
                        
                        <?php if ($atts['show_rating'] === 'true'): ?>
                            <div class="at-testimonial-rating">
                                <?php echo str_repeat('<span class="at-star">★</span>', $testimonial->rating); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="at-testimonial-author">
                            <div class="at-author-name"><?php echo esc_html($testimonial->name); ?></div>
                            <?php if ($testimonial->position || $testimonial->company): ?>
                                <div class="at-author-meta">
                                    <?php 
                                    $meta = array();
                                    if ($testimonial->position) $meta[] = esc_html($testimonial->position);
                                    if ($testimonial->company) $meta[] = esc_html($testimonial->company);
                                    echo implode(', ', $meta);
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    // Elementor Integration Methods
    public function register_elementor_widget() {
        // Check if Elementor is active
        if (!did_action('elementor/loaded')) {
            return;
        }
        
        // Include widget file
        require_once(AT_PLUGIN_PATH . 'includes/elementor-widget.php');
        
        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_Testimonials_Widget());
    }
    
    public function register_elementor_category($elements_manager) {
        $elements_manager->add_category(
            'adaptive-testimonials',
            [
                'title' => __('Testimonials', 'adaptive-testimonials'),
                'icon' => 'fa fa-plug',
            ]
        );
    }
}

// Initialize the plugin
new AdaptiveTestimonials();

// Activation hook
register_activation_hook(__FILE__, function() {
    $plugin = new AdaptiveTestimonials();
    $plugin->create_table();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    // Clean up if needed
});

?>