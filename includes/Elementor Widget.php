<?php
/**
 * Elementor Testimonials Widget
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Elementor_Testimonials_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'adaptive_testimonials';
    }

    public function get_title() {
        return __('Testimonials', 'adaptive-testimonials');
    }

    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['testimonials', 'reviews', 'feedback', 'quotes'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'adaptive-testimonials'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => __('Number of Testimonials', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50,
                'step' => 1,
                'default' => 6,
            ]
        );

        $this->add_control(
            'featured_only',
            [
                'label' => __('Featured Only', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'adaptive-testimonials'),
                'label_off' => __('No', 'adaptive-testimonials'),
                'return_value' => 'true',
                'default' => 'false',
            ]
        );

        $this->add_control(
            'show_rating',
            [
                'label' => __('Show Rating', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'adaptive-testimonials'),
                'label_off' => __('Hide', 'adaptive-testimonials'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'list' => __('List', 'adaptive-testimonials'),
                    'grid' => __('Grid', 'adaptive-testimonials'),
                    'carousel' => __('Carousel', 'adaptive-testimonials'),
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'condition' => [
                    'layout' => ['grid', 'carousel'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .at-testimonials.at-layout-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Container
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => __('Container', 'adaptive-testimonials'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_gap',
            [
                'label' => __('Gap Between Items', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .at-testimonials.at-layout-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .at-testimonials.at-layout-list .at-testimonial' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Testimonial Cards
        $this->start_controls_section(
            'card_style_section',
            [
                'label' => __('Testimonial Cards', 'adaptive-testimonials'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => __('Background', 'adaptive-testimonials'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .at-testimonial',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'label' => __('Border', 'adaptive-testimonials'),
                'selector' => '{{WRAPPER}} .at-testimonial',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .at-testimonial' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'label' => __('Box Shadow', 'adaptive-testimonials'),
                'selector' => '{{WRAPPER}} .at-testimonial',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .at-testimonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Content
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Content', 'adaptive-testimonials'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __('Text Color', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .at-testimonial-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __('Typography', 'adaptive-testimonials'),
                'selector' => '{{WRAPPER}} .at-testimonial-text',
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => __('Margin', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .at-testimonial-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Author
        $this->start_controls_section(
            'author_style_section',
            [
                'label' => __('Author', 'adaptive-testimonials'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'author_name_color',
            [
                'label' => __('Name Color', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .at-author-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_name_typography',
                'label' => __('Name Typography', 'adaptive-testimonials'),
                'selector' => '{{WRAPPER}} .at-author-name',
            ]
        );

        $this->add_control(
            'author_meta_color',
            [
                'label' => __('Company/Position Color', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .at-author-meta' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_meta_typography',
                'label' => __('Company/Position Typography', 'adaptive-testimonials'),
                'selector' => '{{WRAPPER}} .at-author-meta',
            ]
        );

        $this->end_controls_section();

        // Style Section - Rating
        $this->start_controls_section(
            'rating_style_section',
            [
                'label' => __('Rating', 'adaptive-testimonials'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_rating' => 'true',
                ],
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => __('Star Color', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffc107',
                'selectors' => [
                    '{{WRAPPER}} .at-star' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_size',
            [
                'label' => __('Star Size', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .at-star' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Image
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => __('Author Image', 'adaptive-testimonials'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Size', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 150,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .at-testimonial-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __('Border', 'adaptive-testimonials'),
                'selector' => '{{WRAPPER}} .at-testimonial-image img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'adaptive-testimonials'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .at-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Convert settings to shortcode attributes
        $atts = [
            'limit' => $settings['limit'],
            'featured' => $settings['featured_only'],
            'layout' => $settings['layout'],
            'show_rating' => $settings['show_rating']
        ];
        
        // Add Elementor-specific classes
        echo '<div class="elementor-testimonials-wrapper">';
        
        // Use the existing shortcode function
        $testimonials_instance = new AdaptiveTestimonials();
        echo $testimonials_instance->testimonials_shortcode($atts);
        
        echo '</div>';
    }

    protected function content_template() {
        ?>
        <#
        view.addRenderAttribute( 'wrapper', 'class', 'elementor-testimonials-wrapper' );
        #>
        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
            <div class="at-testimonials at-layout-{{ settings.layout }}">
                <div class="at-testimonial">
                    <# if ( settings.show_rating === 'true' ) { #>
                    <div class="at-testimonial-rating">
                        <span class="at-star">★</span>
                        <span class="at-star">★</span>
                        <span class="at-star">★</span>
                        <span class="at-star">★</span>
                        <span class="at-star">★</span>
                    </div>
                    <# } #>
                    
                    <div class="at-testimonial-content">
                        <blockquote class="at-testimonial-text">
                            This is a preview of how your testimonials will look. The actual content will be loaded from your testimonials database.
                        </blockquote>
                        
                        <div class="at-testimonial-author">
                            <div class="at-author-name">John Doe</div>
                            <div class="at-author-meta">CEO, Example Company</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}