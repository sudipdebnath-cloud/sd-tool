<?php
/*
Plugin Name: Countdown Timer
Description: A simple countdown timer plugin.
Version: 1.0
Author: Sudip Debnath (SD)
*/

// Prevent direct access to this file
if ( !defined('ABSPATH') ) {
    exit;
}

// Enqueue necessary scripts and styles
function countdown_timer_enqueue_scripts() {
    wp_enqueue_script('sd-countdown-timer', plugins_url('/sd-countdown-timer.js', __FILE__), array('jquery'), time(), true);
    wp_enqueue_style('sd-countdown-timer', plugins_url('/sd-countdown-timer.css', __FILE__), array(), time());
}
add_action('wp_enqueue_scripts', 'countdown_timer_enqueue_scripts');

// Create a shortcode to display the countdown timer
function sd_countdown_timer_shortcode($atts) {
    static $instance = 0;
    $instance++;

    $atts = shortcode_atts(array(
        'date' => '',
    ), $atts, 'countdown_timer');

    if (empty($atts['date'])) {
        return;
    }

    $unique_id = 'countdown-timer-' . $instance;

    $output = '<div id="' . esc_attr($unique_id) . '" class="countdown-timer" data-date="' . esc_attr($atts['date']) . '">';
    $output .= '<div class="countdown-section"><div class="days"><span>0</span><span>0</span><span>0</span></div><span class="label">Days</span></div>';
    $output .= '<div class="countdown-section"><div class="hours"><span>0</span><span>0</span></div><span class="label">Hrs</span></div>';
    $output .= '<div class="countdown-section"><div class="minutes"><span>0</span><span>0</span></div><span class="label">Mins</span></div>';
    $output .= '<div class="countdown-section"><div class="seconds"><span>0</span><span>0</span></div><span class="label">Secs</span></div>';
    $output .= '</div>';
    return $output;
}
add_shortcode('sd_countdown_timer', 'sd_countdown_timer_shortcode');