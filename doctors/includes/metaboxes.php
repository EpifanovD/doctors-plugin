<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function doctors_register_metaboxes() {
	add_meta_box(
		'doctors_details',
		__( 'Данные врача', 'doctors' ),
		'doctors_render_metabox',
		'doctors',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'doctors_register_metaboxes' );

function doctors_render_metabox( $post ) {
	wp_nonce_field( 'doctors_save_metabox', 'doctors_metabox_nonce' );

	$experience = get_post_meta( $post->ID, '_doctor_experience', true );
	$price      = get_post_meta( $post->ID, '_doctor_price', true );
	$rating     = get_post_meta( $post->ID, '_doctor_rating', true );

	?>
	<p>
		<label for="doctor_experience"><strong><?php echo esc_html__( 'Стаж врача (лет)', 'doctors' ); ?></strong></label><br />
		<input type="number" id="doctor_experience" name="doctor_experience" value="<?php echo esc_attr( $experience ); ?>" min="0" step="1" />
	</p>
	<p>
		<label for="doctor_price"><strong><?php echo esc_html__( 'Цена от', 'doctors' ); ?></strong></label><br />
		<input type="number" id="doctor_price" name="doctor_price" value="<?php echo esc_attr( $price ); ?>" min="0" step="1" />
	</p>
	<p>
		<label for="doctor_rating"><strong><?php echo esc_html__( 'Рейтинг (0-5)', 'doctors' ); ?></strong></label><br />
		<input type="number" id="doctor_rating" name="doctor_rating" value="<?php echo esc_attr( $rating ); ?>" min="0" max="5" step="0.1" />
	</p>
	<?php
}

function doctors_save_metabox( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['doctors_metabox_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doctors_metabox_nonce'] ) ), 'doctors_save_metabox' ) ) {
		return;
	}

	if ( ! isset( $_POST['post_type'] ) || 'doctors' !== $_POST['post_type'] ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['doctor_experience'] ) ) {
		$experience = absint( sanitize_text_field( wp_unslash( $_POST['doctor_experience'] ) ) );
		update_post_meta( $post_id, '_doctor_experience', $experience );
	}

	if ( isset( $_POST['doctor_price'] ) ) {
		$price = absint( sanitize_text_field( wp_unslash( $_POST['doctor_price'] ) ) );
		update_post_meta( $post_id, '_doctor_price', $price );
	}

	if ( isset( $_POST['doctor_rating'] ) ) {
		$rating = floatval( sanitize_text_field( wp_unslash( $_POST['doctor_rating'] ) ) );
		update_post_meta( $post_id, '_doctor_rating', $rating );
	}
}
add_action( 'save_post', 'doctors_save_metabox' );
