<?php
namespace Elementor;

class Filter_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'my-custom-widget';
    }

    public function get_title() {
        return __( 'My Custom Widget', 'text-domain' );
    }

    public function get_icon() {
        return 'eicon-button'; // Choose an appropriate icon
    }

    public function get_categories() {
        return [ 'basic' ]; // Choose an appropriate category
    }

    protected function _register_controls() {
        // Define widget controls here
    }

    protected function render() {
        echo '<h1>sadasd</h1>';
    }
}

Plugin::instance()->widgets_manager->register_widget_type( new Filter_Elementor_Widget() );
