<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor oEmbed Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
use Elementor\Core\Base\Document;
use ElementorPro\Modules\LoopBuilder\Documents\Loop as LoopDocument;
use ElementorPro\Modules\QueryControl\Controls\Template_Query;
use ElementorPro\Modules\QueryControl\Module as QueryControlModule;
class Elementor_oEmbed_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'oembed';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'oEmbed', 'elementor-oembed-widget' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-code';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'oembed', 'url', 'link' ];
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url() {
		return 'https://developers.elementor.com/docs/widgets/';
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'elementor-oembed-widget' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
    
        $this->add_control(
            'template_id',
            [
                'label' => esc_html__( 'Choose a template', 'elementor-pro' ),
                'type' => Template_Query::CONTROL_ID,
                'label_block' => true,
                'autocomplete' => [
                    'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
                    'query' => [
                        'post_status' => Document::STATUS_PUBLISH,
                        'meta_query' => [
                            [
                                'key' => Document::TYPE_META_KEY,
                                'value' => LoopDocument::get_type(),
                                'compare' => 'IN',
                            ],
                        ],
                    ],
                ],
                'actions' => [
                    'new' => [
                        'visible' => true,
                        'document_config' => [
                            'type' => LoopDocument::get_type(),
                        ],
                    ],
                    'edit' => [
                        'visible' => true,
                    ],
                ],
                'frontend_available' => true,
            ]
        );
    
        $this->add_control(
            'post_type',
            [
                'label' => __( 'Post Type', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_post_types(),
                'frontend_available' => true, // Make the control available in the frontend
                'default' => 'post',
            ]
        );
    
        // Taxonomy multiselect control
        $this->add_control(
            'taxonomies',
            [
                'label' => __( 'Taxonomies', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [],
                'options' => $this->get_all_taxonomies_options(),
                'style_transfer' => true,
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-dropdown' => 'width: 100%; margin-right: 10px;',
                    '{{WRAPPER}} .taxonomy-dropdowns' => 'display: flex; align-items: startdiffer;',
                    '{{WRAPPER}} .clear-selection-button' => 'display: none;',
                ],
            ]
        );
    
        $this->add_control(
            'taxonomies_name',
            [
                'label' => __( 'Taxonomies', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'taxonomy',
                        'label' => __( 'Select Taxonomy', 'text-domain' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => $this->get_all_taxonomies_options(),
                    ],
                    [
                        'name' => 'custom_name',
                        'label' => __( 'Custom Name', 'text-domain' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'label_block' => true,
                    ],
                ],
                'style_transfer' => true,
                'render_type' => 'ui', // Use 'ui' render type to render controls in the UI
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_section();
    
        // Select Style Section
        $this->start_controls_section(
            'select_style_section',
            [
                'label' => __( 'Select Style', 'text-domain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_control(
            'select_color',
            [
                'label' => __( 'Color', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-dropdown' => 'color: {{VALUE}};',
                ],
            ]
        );
    
        $this->add_control(
            'select_background_color',
            [
                'label' => __( 'Background Color', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-dropdown' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __( 'Border', 'text-domain' ),
                'selector' => '{{WRAPPER}} .taxonomy-dropdown',
            ]
        );

    
        $this->add_control(
            'select_border_radius',
            [
                'label' => __( 'Border Radius', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .taxonomy-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
    
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'select_typography',
                'label' => __( 'Typography', 'text-domain' ),
                'selector' => '{{WRAPPER}} .taxonomy-dropdown',
            ]
        );
    
        $this->end_controls_section();
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => __( 'Button Style', 'text-domain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-selection-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_background_color',
            [
                'label' => __( 'Background Color', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-selection-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_border_color',
            [
                'label' => __( 'Border Color', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clear-selection-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __( 'Typography', 'text-domain' ),
                'selector' => '{{WRAPPER}} .clear-selection-button',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => __( 'Border', 'text-domain' ),
                'selector' => '{{WRAPPER}} .clear-selection-button',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'other',
            [
                'label' => __( 'other', 'text-domain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    
        // Button to clear selections

    
        // Number of Columns Control
        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => __( '1', 'text-domain' ),
                    '2' => __( '2', 'text-domain' ),
                    '3' => __( '3', 'text-domain' ),
                    '4' => __( '4', 'text-domain' ),
                ],
                'default' => '3',
                'prefix_class' => 'elementor-grid%s-',
            ]
        );
    
        // Gap Control
        $this->add_responsive_control(
            'gap',
            [
                'label' => __( 'Gap', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter-grid' => 'grid-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    
        // Equal Height Cards Control
        $this->add_control(
            'equal_height',
            [
                'label' => __( 'Equal Height Cards', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'text-domain' ),
                'label_off' => __( 'No', 'text-domain' ),
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->end_controls_section();

    }
    
    private function get_post_types() {
        $post_types = get_post_types( [], 'objects' );
        $options = [];
        foreach ( $post_types as $post_type ) {
            $options[ $post_type->name ] = $post_type->label;
        }
        return $options;
    }

    private function get_all_taxonomies_options() {
        $taxonomies = get_taxonomies( [], 'objects' );
        $options = [];
        foreach ($taxonomies as $taxonomy) {
            $options[$taxonomy->name] = $taxonomy->label;
        }
        return $options;
    }
	
    protected function render() {
        $settings = $this->get_settings();
    
        // Render widget content
        ?>
        <div class="taxonomy-dropdowns">
    <?php foreach ($settings['taxonomies_name'] as $taxonomy): ?>
        <select class="taxonomy-dropdown" data-taxonomy="<?php echo $taxonomy['taxonomy']; ?>" style="margin-right: 10px;">
            <option value=""><?php echo esc_html( $taxonomy['custom_name'] ); ?></option>
            <?php foreach ($this->get_taxonomy_terms($taxonomy['taxonomy']) as $term): ?>
                <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
            <?php endforeach; ?>
            <option value="all"><?php echo esc_html__( 'All', 'text-domain' ); ?></option>
        </select>
    <?php endforeach; ?>
    <button class="clear-selection-button" style="display: none;"><?php echo __('Clear Selection', 'text-domain'); ?></button>

</div>


        <div class="filter-grid elementor-grid elementor-grid-<?php echo esc_attr( $settings['columns'] ); ?>">
        <script>
    jQuery(document).ready(function($) {
        var ajaxurl = 'http://localhost/larivenew/wp-admin/admin-ajax.php'; 
        // Function to check if any dropdown has a selected option
        function checkSelection() {
            var hasSelection = $('.taxonomy-dropdown').filter(function() {
                return $(this).val() !== '';
            }).length > 0;

            // Show or hide the clear selection button based on selection status
            if (hasSelection) {
                $('.clear-selection-button').show();
            } else {
                $('.clear-selection-button').hide();
            }
        }

        // Function to fetch all posts
        function fetchAllPosts() {
            $.ajax({
                url: ajaxurl, // WordPress AJAX URL
                method: 'POST',
                data: {
                    action: 'fetch_custom_query',
                    taxonomyData: '',
                },
                success: function(response) {
                    $('.filter-grid').html(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        // On change event for dropdowns
        $('.taxonomy-dropdown').on('change', function() {
            checkSelection();

            // Fetch custom query data
            var taxonomyData = {};
            $('.taxonomy-dropdown').each(function() {
                taxonomyData[$(this).data('taxonomy')] = $(this).val();
            });

            // Make AJAX request to fetch filtered posts
            $.ajax({
                url: ajaxurl, // WordPress AJAX URL
                method: 'POST',
                data: {
                    action: 'fetch_custom_query',
                    taxonomyData: taxonomyData,
                },
                success: function(response) {
                    $('.filter-grid').html(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        // Click event for clear selection button
        $('.clear-selection-button').on('click', function() {
            $('.taxonomy-dropdown').val('');
            $(this).hide();
            fetchAllPosts(); // Load all posts on clear selection
        });

        // Initial check on page load
        checkSelection();

        // Fetch all posts when the page initially loads
        fetchAllPosts();
    });
</script>

        <?php
    }
    
    
    private function get_taxonomy_terms($taxonomy) {
        return get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);
    }
}