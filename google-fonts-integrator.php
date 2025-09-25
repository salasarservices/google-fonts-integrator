<?php
/*
Plugin Name: Google Fonts Integrator
Plugin URI: https://github.com/salasarservices/google-fonts-integrator
Description: Integrate 20 Google Fonts (including Poppins) into your WordPress site. Select header and body fonts, font sizes, and font weights via the Customizer.
Version: 1.1.0
Author: salasarservices
Author URI: https://github.com/salasarservices
License: GPL2
Text Domain: google-fonts-integrator
*/

if (!defined('ABSPATH')) exit;

// List of 20 Google Fonts (add more if needed)
function gfi_get_fonts_list() {
    return array(
        'Roboto' => 'Roboto',
        'Open Sans' => 'Open Sans',
        'Lato' => 'Lato',
        'Montserrat' => 'Montserrat',
        'Oswald' => 'Oswald',
        'Poppins' => 'Poppins',
        'Raleway' => 'Raleway',
        'Nunito' => 'Nunito',
        'Merriweather' => 'Merriweather',
        'Ubuntu' => 'Ubuntu',
        'Bebas Neue' => 'Bebas Neue',
        'Rubik' => 'Rubik',
        'Cabin' => 'Cabin',
        'Quicksand' => 'Quicksand',
        'Work Sans' => 'Work Sans',
        'PT Sans' => 'PT Sans',
        'Playfair Display' => 'Playfair Display',
        'Josefin Sans' => 'Josefin Sans',
        'Fira Sans' => 'Fira Sans',
        'Source Sans Pro' => 'Source Sans Pro'
    );
}

// Map font names to Google Fonts URLs
function gfi_get_google_font_url($fonts, $weights) {
    $font_map = array(
        'Roboto' => 'Roboto',
        'Open Sans' => 'Open+Sans',
        'Lato' => 'Lato',
        'Montserrat' => 'Montserrat',
        'Oswald' => 'Oswald',
        'Poppins' => 'Poppins',
        'Raleway' => 'Raleway',
        'Nunito' => 'Nunito',
        'Merriweather' => 'Merriweather',
        'Ubuntu' => 'Ubuntu',
        'Bebas Neue' => 'Bebas+Neue',
        'Rubik' => 'Rubik',
        'Cabin' => 'Cabin',
        'Quicksand' => 'Quicksand',
        'Work Sans' => 'Work+Sans',
        'PT Sans' => 'PT+Sans',
        'Playfair Display' => 'Playfair+Display',
        'Josefin Sans' => 'Josefin+Sans',
        'Fira Sans' => 'Fira+Sans',
        'Source Sans Pro' => 'Source+Sans+Pro'
    );
    $urls = array();
    foreach ($fonts as $font) {
        if (isset($font_map[$font])) {
            $urls[] = 'https://fonts.googleapis.com/css?family=' . $font_map[$font] . ':' . implode(',', $weights) . '&display=swap';
        }
    }
    return $urls;
}

// Enqueue Google Fonts (header/body, weights)
function gfi_enqueue_google_fonts() {
    $header_font = get_theme_mod('gfi_header_font', 'Roboto');
    $body_font = get_theme_mod('gfi_body_font', 'Open Sans');
    $font_weights = get_theme_mod('gfi_font_weight', array('400', '700'));
    if (!is_array($font_weights)) $font_weights = array($font_weights);
    $fonts = array_unique(array($header_font, $body_font));
    $urls = gfi_get_google_font_url($fonts, $font_weights);
    foreach ($urls as $i => $url) {
        wp_enqueue_style('gfi-google-font-' . $i, $url, false);
    }
}
add_action('wp_enqueue_scripts', 'gfi_enqueue_google_fonts');

// Customizer Settings
function gfi_customize_register($wp_customize) {
    $fonts = gfi_get_fonts_list();
    $weights = array(
        '100' => 'Thin',
        '200' => 'Extra Light',
        '300' => 'Light',
        '400' => 'Normal',
        '500' => 'Medium',
        '600' => 'Semi Bold',
        '700' => 'Bold',
        '800' => 'Extra Bold',
        '900' => 'Black'
    );
    // Section
    $wp_customize->add_section('gfi_fonts_section', array(
        'title' => __('Google Fonts', 'google-fonts-integrator'),
        'priority' => 30,
    ));
    // Header Font
    $wp_customize->add_setting('gfi_header_font', array(
        'default' => 'Roboto',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('gfi_header_font', array(
        'label' => __('Header Font', 'google-fonts-integrator'),
        'section' => 'gfi_fonts_section',
        'type' => 'select',
        'choices' => $fonts,
    ));
    // Body Font
    $wp_customize->add_setting('gfi_body_font', array(
        'default' => 'Open Sans',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('gfi_body_font', array(
        'label' => __('Body Font', 'google-fonts-integrator'),
        'section' => 'gfi_fonts_section',
        'type' => 'select',
        'choices' => $fonts,
    ));
    // Font Weight
    $wp_customize->add_setting('gfi_font_weight', array(
        'default' => array('400', '700'),
        'sanitize_callback' => 'gfi_sanitize_font_weight',
    ));
    $wp_customize->add_control('gfi_font_weight', array(
        'label' => __('Font Weight (multiple allowed)', 'google-fonts-integrator'),
        'section' => 'gfi_fonts_section',
        'type' => 'select',
        'choices' => $weights,
        'input_attrs' => array(
            'multiple' => true,
            'style' => 'height: 120px;'
        ),
    ));
    // Header Font Size
    $wp_customize->add_setting('gfi_header_font_size', array(
        'default' => '2em',
        'sanitize_callback' => 'gfi_sanitize_css_size',
    ));
    $wp_customize->add_control('gfi_header_font_size', array(
        'label' => __('Header Font Size', 'google-fonts-integrator'),
        'section' => 'gfi_fonts_section',
        'type' => 'text',
        'description' => 'e.g. 2em, 24px, 2rem',
    ));
    // Body Font Size
    $wp_customize->add_setting('gfi_body_font_size', array(
        'default' => '1em',
        'sanitize_callback' => 'gfi_sanitize_css_size',
    ));
    $wp_customize->add_control('gfi_body_font_size', array(
        'label' => __('Body Font Size', 'google-fonts-integrator'),
        'section' => 'gfi_fonts_section',
        'type' => 'text',
        'description' => 'e.g. 1em, 16px, 1rem',
    ));
}
add_action('customize_register', 'gfi_customize_register');

// Sanitizers
function gfi_sanitize_font_weight($input) {
    $valid = array('100','200','300','400','500','600','700','800','900');
    if (is_array($input)) {
        return array_filter($input, function($w) use ($valid) { return in_array($w, $valid); });
    } elseif (in_array($input, $valid)) {
        return array($input);
    }
    return array('400','700');
}
function gfi_sanitize_css_size($input) {
    return preg_match('/^[\d\.]+(px|em|rem|%)?$/', $input) ? $input : '1em';
}

// Output custom CSS for fonts
function gfi_output_custom_font_css() {
    $header_font = get_theme_mod('gfi_header_font', 'Roboto');
    $body_font = get_theme_mod('gfi_body_font', 'Open Sans');
    $font_weights = get_theme_mod('gfi_font_weight', array('400', '700'));
    $header_size = get_theme_mod('gfi_header_font_size', '2em');
    $body_size = get_theme_mod('gfi_body_font_size', '1em');
    if (!is_array($font_weights)) $font_weights = array($font_weights);
    $header_font_css = esc_attr($header_font) . ', sans-serif';
    $body_font_css = esc_attr($body_font) . ', sans-serif';
    $header_weight = esc_attr($font_weights[0]); // Use first selected weight for header
    $body_weight = esc_attr(end($font_weights)); // Use last selected weight for body
    echo "<style type='text/css'>
        body { font-family: {$body_font_css}; font-size: {$body_size}; font-weight: {$body_weight}; }
        h1, h2, h3, h4, h5, h6 { font-family: {$header_font_css}; font-size: {$header_size}; font-weight: {$header_weight}; }
    </style>";
}
add_action('wp_head', 'gfi_output_custom_font_css');