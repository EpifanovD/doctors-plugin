<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_apply_archive_filters( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'doctors' ) ) {
		return;
	}

	$query->set( 'posts_per_page', 9 );

	$tax_query = array();

	if ( isset( $_GET['specialization'] ) && '' !== $_GET['specialization'] ) {
		$specialization = sanitize_title( sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) );
		$tax_query[]    = array(
			'taxonomy' => 'specialization',
			'field'    => 'slug',
			'terms'    => $specialization,
		);
	}

	if ( isset( $_GET['city'] ) && '' !== $_GET['city'] ) {
		$city       = sanitize_title( sanitize_text_field( wp_unslash( $_GET['city'] ) ) );
		$tax_query[] = array(
			'taxonomy' => 'city',
			'field'    => 'slug',
			'terms'    => $city,
		);
	}

	if ( ! empty( $tax_query ) ) {
		if ( count( $tax_query ) > 1 ) {
			$tax_query['relation'] = 'AND';
		}
		$query->set( 'tax_query', $tax_query );
	}

	if ( isset( $_GET['sort'] ) && '' !== $_GET['sort'] ) {
		$sort = sanitize_text_field( wp_unslash( $_GET['sort'] ) );

		$meta_key = '';
		$order    = 'DESC';

		switch ( $sort ) {
			case 'rating_desc':
				$meta_key = '_doctor_rating';
				$order    = 'DESC';
				break;
			case 'price_asc':
				$meta_key = '_doctor_price';
				$order    = 'ASC';
				break;
			case 'experience_desc':
				$meta_key = '_doctor_experience';
				$order    = 'DESC';
				break;
		}

		if ( $meta_key ) {
			$meta_query   = $query->get( 'meta_query' );
			$meta_query   = is_array( $meta_query ) ? $meta_query : array();
			$meta_query[] = array(
				'key'     => $meta_key,
				'compare' => 'EXISTS',
				'type'    => 'NUMERIC',
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'meta_key', $meta_key );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', $order );
		}
	}
}
add_action( 'pre_get_posts', 'doctors_apply_archive_filters' );
