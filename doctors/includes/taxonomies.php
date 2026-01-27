<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_register_taxonomies() {
	$specialization_labels = array(
		'name'              => __( 'Специализации', 'doctors' ),
		'singular_name'     => __( 'Специализация', 'doctors' ),
		'search_items'      => __( 'Поиск специализаций', 'doctors' ),
		'all_items'         => __( 'Все специализации', 'doctors' ),
		'parent_item'       => __( 'Родительская специализация', 'doctors' ),
		'parent_item_colon' => __( 'Родительская специализация:', 'doctors' ),
		'edit_item'         => __( 'Редактировать специализацию', 'doctors' ),
		'update_item'       => __( 'Обновить специализацию', 'doctors' ),
		'add_new_item'      => __( 'Добавить специализацию', 'doctors' ),
		'new_item_name'     => __( 'Название новой специализации', 'doctors' ),
		'menu_name'         => __( 'Специализации', 'doctors' ),
	);

	register_taxonomy(
		'specialization',
		'doctors',
		array(
			'hierarchical'      => true,
			'labels'            => $specialization_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'rewrite'           => array( 'slug' => 'specialization' ),
			'show_in_rest'      => true,
		)
	);

	$city_labels = array(
		'name'                       => __( 'Города', 'doctors' ),
		'singular_name'              => __( 'Город', 'doctors' ),
		'search_items'               => __( 'Поиск городов', 'doctors' ),
		'popular_items'              => __( 'Популярные города', 'doctors' ),
		'all_items'                  => __( 'Все города', 'doctors' ),
		'edit_item'                  => __( 'Редактировать город', 'doctors' ),
		'update_item'                => __( 'Обновить город', 'doctors' ),
		'add_new_item'               => __( 'Добавить город', 'doctors' ),
		'new_item_name'              => __( 'Название нового города', 'doctors' ),
		'separate_items_with_commas' => __( 'Разделяйте города запятыми', 'doctors' ),
		'add_or_remove_items'        => __( 'Добавить или удалить города', 'doctors' ),
		'choose_from_most_used'      => __( 'Выберите из популярных городов', 'doctors' ),
		'not_found'                  => __( 'Города не найдены.', 'doctors' ),
		'menu_name'                  => __( 'Города', 'doctors' ),
	);

	register_taxonomy(
		'city',
		'doctors',
		array(
			'hierarchical'          => false,
			'labels'                => $city_labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => false,
			'rewrite'               => array( 'slug' => 'city' ),
			'show_in_rest'          => true,
		)
	);
}
add_action( 'init', 'doctors_register_taxonomies' );
