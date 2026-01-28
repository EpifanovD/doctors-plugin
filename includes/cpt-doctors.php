<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_register_cpt() {
	$labels = array(
		'name'               => __( 'Доктора', 'doctors' ),
		'singular_name'      => __( 'Доктор', 'doctors' ),
		'menu_name'          => __( 'Доктора', 'doctors' ),
		'name_admin_bar'     => __( 'Доктор', 'doctors' ),
		'add_new'            => __( 'Добавить', 'doctors' ),
		'add_new_item'       => __( 'Добавить доктора', 'doctors' ),
		'new_item'           => __( 'Новый доктор', 'doctors' ),
		'edit_item'          => __( 'Редактировать доктора', 'doctors' ),
		'view_item'          => __( 'Просмотреть доктора', 'doctors' ),
		'all_items'          => __( 'Все доктора', 'doctors' ),
		'search_items'       => __( 'Искать докторов', 'doctors' ),
		'parent_item_colon'  => __( 'Родительские доктора:', 'doctors' ),
		'not_found'          => __( 'Доктора не найдены.', 'doctors' ),
		'not_found_in_trash' => __( 'В корзине докторов нет.', 'doctors' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'doctors' ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-heart',
	);

	register_post_type( 'doctors', $args );
}
add_action( 'init', 'doctors_register_cpt' );
