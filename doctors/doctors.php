<?php
/**
 * Plugin Name: Doctors
 * Description: Кастомный тип записей, таксономии, метабоксы, шаблоны и фильтры для врачей.
 * Version: 1.0.0
 * Author: Епифанов Дмитрий
 * Text Domain: doctors
 *
 * Контакты: tg: @Courage891, tel: 89128283655
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DOCTORS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DOCTORS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once DOCTORS_PLUGIN_DIR . 'includes/cpt-doctors.php';
require_once DOCTORS_PLUGIN_DIR . 'includes/taxonomies.php';
require_once DOCTORS_PLUGIN_DIR . 'includes/metaboxes.php';
require_once DOCTORS_PLUGIN_DIR . 'includes/query-filters.php';

function doctors_activate() {
	doctors_register_cpt();
	doctors_register_taxonomies();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'doctors_activate' );

function doctors_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'doctors_deactivate' );

function doctors_template_include( $template ) {
	if ( is_singular( 'doctors' ) ) {
		$custom = DOCTORS_PLUGIN_DIR . 'templates/single-doctors.php';
		if ( file_exists( $custom ) ) {
			return $custom;
		}
	}

	if ( is_post_type_archive( 'doctors' ) ) {
		$custom = DOCTORS_PLUGIN_DIR . 'templates/archive-doctors.php';
		if ( file_exists( $custom ) ) {
			return $custom;
		}
	}

	return $template;
}
add_filter( 'template_include', 'doctors_template_include' );
