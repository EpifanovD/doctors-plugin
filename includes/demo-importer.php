<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Doctors demo importer (minimal):
 * - Admin page with 1 button: Import
 * - Data stored in PHP array
 * - Images loaded from: doctors/assets/demo-images/
 */

add_action( 'admin_menu', function () {
	add_submenu_page(
		'edit.php?post_type=doctors',
		__( 'Демо-данные', 'doctors' ),
		__( 'Демо-данные', 'doctors' ),
		'manage_options',
		'doctors-demo',
		'doctors_demo_page'
	);
} );

function doctors_demo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Недостаточно прав.', 'doctors' ) );
	}

	$notice = '';
	$error  = '';

	if ( isset( $_GET['doctors_demo'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$flag = sanitize_text_field( wp_unslash( $_GET['doctors_demo'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'imported' === $flag ) {
			$notice = __( 'Демо-данные импортированы.', 'doctors' );
		} elseif ( 'error' === $flag ) {
			$notice = __( 'Ошибка импорта демо-данных.', 'doctors' );
			$error  = (string) get_option( 'doctors_demo_last_error', '' );
		}
	}

	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Doctors — демо-данные', 'doctors' ); ?></h1>

		<?php if ( $notice ) : ?>
			<div class="notice notice-info is-dismissible">
				<p><?php echo esc_html( $notice ); ?></p>
				<?php if ( $error ) : ?>
					<p><code><?php echo esc_html( $error ); ?></code></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'doctors_demo_import', 'doctors_demo_nonce' ); ?>
			<input type="hidden" name="action" value="doctors_demo_import" />
			<?php submit_button( __( 'Импортировать демо-данные', 'doctors' ), 'primary' ); ?>
		</form>
	</div>
	<?php
}

add_action( 'admin_post_doctors_demo_import', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Недостаточно прав.', 'doctors' ) );
	}

	if (
		! isset( $_POST['doctors_demo_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doctors_demo_nonce'] ) ), 'doctors_demo_import' )
	) {
		wp_die( esc_html__( 'Некорректный nonce.', 'doctors' ) );
	}

	$ok   = doctors_demo_import();
	$base = admin_url( 'edit.php?post_type=doctors&page=doctors-demo' );
	wp_safe_redirect( add_query_arg( 'doctors_demo', $ok ? 'imported' : 'error', $base ) );
	exit;
} );

function doctors_demo_data() {
	return array(
		array(
			'title'          => 'Андрей Евгеньевич Быков',
			'slug'           => 'andrej-evgenevich-bykov',
			'experience'     => 20,
			'price'          => 2000,
			'rating'         => 5,
			'city'           => array( 'name' => 'Москва', 'slug' => 'moskva' ),
			'specialization' => array( array( 'name' => 'Терапевт', 'slug' => 'terapevt' ) ),
			'image'          => 'andrej-evgenevich-bykov.jpg',
		),
		array(
			'title'          => 'Анастасия Константиновна Кисегач',
			'slug'           => 'anastasiya-konstantinovna-kisegach',
			'experience'     => 21,
			'price'          => 3000,
			'rating'         => 5,
			'city'           => array( 'name' => 'Москва', 'slug' => 'moskva' ),
			'specialization' => array( array( 'name' => 'Главврач', 'slug' => 'glavvrach' ) ),
			'image'          => 'anastasiya-konstantinovna-kisegach.jpg',
		),
		array(
			'title'          => 'Борис Аркадьевич Левин',
			'slug'           => 'boris-arkadevich-levin',
			'experience'     => 5,
			'price'          => 1500,
			'rating'         => 4,
			'city'           => array( 'name' => 'Москва', 'slug' => 'moskva' ),
			'specialization' => array( array( 'name' => 'Интерн', 'slug' => 'intern' ) ),
			'image'          => 'boris-arkadevich-levin.jpg',
		),
		array(
			'title'          => 'Варвара Николаевна Черноус',
			'slug'           => 'varvara-nikolaevna-chernous',
			'experience'     => 4,
			'price'          => 1300,
			'rating'         => 4,
			'city'           => array( 'name' => 'Екатеринбург', 'slug' => 'ekaterinburg' ),
			'specialization' => array( array( 'name' => 'Интерн', 'slug' => 'intern' ) ),
			'image'          => 'varvara-nikolaevna-chernous.jpg',
		),
		array(
			'title'          => 'Виктор Алексеевич Романенко',
			'slug'           => 'viktor-alekseevich-romanenko',
			'experience'     => 4,
			'price'          => 900,
			'rating'         => 3,
			'city'           => array( 'name' => 'Санкт-Петербург', 'slug' => 'sankt-peterburg' ),
			'specialization' => array( array( 'name' => 'Интерн', 'slug' => 'intern' ) ),
			'image'          => 'viktor-alekseevich-romanenko.jpg',
		),
		array(
			'title'          => 'Иван Натанович Купитман',
			'slug'           => 'ivan-natanovich-kupitman',
			'experience'     => 32,
			'price'          => 10000,
			'rating'         => 4,
			'city'           => array( 'name' => 'Екатеринбург', 'slug' => 'ekaterinburg' ),
			'specialization' => array( array( 'name' => 'Венеролог', 'slug' => 'venerolog' ) ),
			'image'          => 'ivan-natanovich-kupitman.jpg',
		),
		array(
			'title'          => 'Любовь Михайловна Скрябина',
			'slug'           => 'lyubov-mihajlovna-skryabina',
			'experience'     => 15,
			'price'          => 1200,
			'rating'         => 3,
			'city'           => array( 'name' => 'Екатеринбург', 'slug' => 'ekaterinburg' ),
			'specialization' => array( array( 'name' => 'Медсестра', 'slug' => 'medsestra' ) ),
			'image'          => 'lyubov-mihajlovna-skryabina.jpg',
		),
		array(
			'title'          => 'Семён Семёнович Лобанов',
			'slug'           => 'semyon-semyonovich-lobanov',
			'experience'     => 4,
			'price'          => 900,
			'rating'         => 0,
			'city'           => array( 'name' => 'Москва', 'slug' => 'moskva' ),
			'specialization' => array( array( 'name' => 'Интерн', 'slug' => 'intern' ) ),
			'image'          => 'semyon-semyonovich-lobanov.jpg',
		),
		array(
			'title'          => 'Софья Яковлевна Калинина',
			'slug'           => 'sofya-yakovlevna-kalinina',
			'experience'     => 2,
			'price'          => 800,
			'rating'         => 5,
			'city'           => array( 'name' => 'Санкт-Петербург', 'slug' => 'sankt-peterburg' ),
			'specialization' => array( array( 'name' => 'Интерн', 'slug' => 'intern' ) ),
			'image'          => 'sofya-yakovlevna-kalinina.jpg',
		),
		array(
			'title'          => 'Фил Ричардс',
			'slug'           => 'fil-richards',
			'experience'     => 4,
			'price'          => 2000,
			'rating'         => 4,
			'city'           => array( 'name' => 'Вашингтон', 'slug' => 'vashington' ),
			'specialization' => array( array( 'name' => 'Интерн', 'slug' => 'intern' ) ),
			'image'          => 'fil-richards.jpg',
		),
	);
}

function doctors_demo_error( $message ) {
	update_option( 'doctors_demo_last_error', (string) $message, false );
	return false;
}

function doctors_demo_image_path( $filename ) {
	$filename = wp_basename( (string) $filename );
	if ( '' === $filename ) {
		return '';
	}

	$path = trailingslashit( DOCTORS_PLUGIN_DIR ) . 'assets/demo-images/' . $filename;
	return ( file_exists( $path ) && is_readable( $path ) ) ? $path : '';
}

function doctors_demo_term_id( $taxonomy, $name, $slug = '' ) {
	$name = sanitize_text_field( (string) $name );
	if ( '' === $name ) {
		return 0;
	}

	$slug = sanitize_title( (string) $slug );
	if ( $slug ) {
		$by_slug = get_term_by( 'slug', $slug, $taxonomy );
		if ( $by_slug && ! empty( $by_slug->term_id ) ) {
			return (int) $by_slug->term_id;
		}
	}

	$term = term_exists( $name, $taxonomy );
	if ( ! $term ) {
		$args = $slug ? array( 'slug' => $slug ) : array();
		$term = wp_insert_term( $name, $taxonomy, $args );
	}

	return ( is_array( $term ) && ! empty( $term['term_id'] ) ) ? (int) $term['term_id'] : 0;
}

function doctors_demo_import() {
	delete_option( 'doctors_demo_last_error' );

	$data = doctors_demo_data();
	if ( empty( $data ) ) {
		return doctors_demo_error( 'Пустой массив демо-данных.' );
	}

	// Make import deterministic: delete previous demo, then create again.
	doctors_demo_reset();

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_dir();
	if ( ! empty( $upload['error'] ) ) {
		return doctors_demo_error( (string) $upload['error'] );
	}

	$demo_dir = trailingslashit( $upload['basedir'] ) . 'doctors-demo';
	if ( ! wp_mkdir_p( $demo_dir ) ) {
		return doctors_demo_error( 'Не удалось создать папку uploads/doctors-demo.' );
	}

	$created_posts       = array();
	$created_attachments = array();

	foreach ( $data as $row ) {
		$title = isset( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '';
		if ( '' === $title ) {
			continue;
		}

		$slug = isset( $row['slug'] ) ? sanitize_title( (string) $row['slug'] ) : '';
		if ( '' === $slug ) {
			return doctors_demo_error( 'Не задан slug для записи: ' . $title );
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'doctors',
				'post_status' => 'publish',
				'post_title'  => $title,
				'post_name'   => $slug,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return doctors_demo_error( $post_id->get_error_message() );
		}

		$created_posts[] = (int) $post_id;

		update_post_meta( $post_id, '_doctor_experience', isset( $row['experience'] ) ? absint( $row['experience'] ) : 0 );
		update_post_meta( $post_id, '_doctor_price', isset( $row['price'] ) ? absint( $row['price'] ) : 0 );
		update_post_meta( $post_id, '_doctor_rating', isset( $row['rating'] ) ? (float) $row['rating'] : 0 );

		if ( ! empty( $row['city'] ) ) {
			$city_name = is_array( $row['city'] ) && isset( $row['city']['name'] ) ? (string) $row['city']['name'] : (string) $row['city'];
			$city_slug = is_array( $row['city'] ) && isset( $row['city']['slug'] ) ? (string) $row['city']['slug'] : '';
			$city_id   = doctors_demo_term_id( 'city', $city_name, $city_slug );
			if ( $city_id ) {
				wp_set_object_terms( $post_id, array( $city_id ), 'city', false );
			}
		}

		if ( ! empty( $row['specialization'] ) && is_array( $row['specialization'] ) ) {
			$spec_ids = array();
			foreach ( $row['specialization'] as $spec ) {
				$spec_name = is_array( $spec ) && isset( $spec['name'] ) ? (string) $spec['name'] : (string) $spec;
				$spec_slug = is_array( $spec ) && isset( $spec['slug'] ) ? (string) $spec['slug'] : '';
				$tid       = doctors_demo_term_id( 'specialization', $spec_name, $spec_slug );
				if ( $tid ) {
					$spec_ids[] = $tid;
				}
			}
			if ( $spec_ids ) {
				wp_set_object_terms( $post_id, $spec_ids, 'specialization', false );
			}
		}

		$src = doctors_demo_image_path( isset( $row['image'] ) ? $row['image'] : '' );
		if ( ! $src ) {
			return doctors_demo_error( 'Не найден файл изображения: ' . ( isset( $row['image'] ) ? (string) $row['image'] : '' ) );
		}

		$ext       = pathinfo( wp_basename( $src ), PATHINFO_EXTENSION );
		$dst_name  = wp_unique_filename( $demo_dir, $slug . ( $ext ? '.' . $ext : '' ) );
		$dst_path  = trailingslashit( $demo_dir ) . $dst_name;
		$dst_url   = trailingslashit( $upload['baseurl'] ) . 'doctors-demo/' . $dst_name;

		if ( ! @copy( $src, $dst_path ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			return doctors_demo_error( 'Не удалось скопировать изображение: ' . wp_basename( $src ) );
		}

		$filetype = wp_check_filetype( $dst_name, null );
		$attach_id = wp_insert_attachment(
			array(
				'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'image/jpeg',
				'post_title'     => $title,
				'post_status'    => 'inherit',
				'guid'           => $dst_url,
			),
			$dst_path,
			$post_id
		);

		if ( is_wp_error( $attach_id ) || ! $attach_id ) {
			return doctors_demo_error( 'Не удалось создать attachment для: ' . $dst_name );
		}

		$created_attachments[] = (int) $attach_id;

		$meta = wp_generate_attachment_metadata( $attach_id, $dst_path );
		if ( $meta ) {
			wp_update_attachment_metadata( $attach_id, $meta );
		}

		set_post_thumbnail( $post_id, $attach_id );
	}

	update_option(
		'doctors_demo_ids',
		array(
			'posts'       => array_values( array_unique( array_map( 'absint', $created_posts ) ) ),
			'attachments' => array_values( array_unique( array_map( 'absint', $created_attachments ) ) ),
		),
		false
	);

	return true;
}

function doctors_demo_reset() {
	$ids = get_option( 'doctors_demo_ids' );
	if ( ! is_array( $ids ) ) {
		delete_option( 'doctors_demo_ids' );
		return true;
	}

	$attachments = ! empty( $ids['attachments'] ) && is_array( $ids['attachments'] ) ? $ids['attachments'] : array();
	$posts       = ! empty( $ids['posts'] ) && is_array( $ids['posts'] ) ? $ids['posts'] : array();

	foreach ( $attachments as $aid ) {
		$aid = absint( $aid );
		if ( $aid ) {
			wp_delete_attachment( $aid, true );
		}
	}

	foreach ( $posts as $pid ) {
		$pid = absint( $pid );
		if ( $pid ) {
			wp_delete_post( $pid, true );
		}
	}

	delete_option( 'doctors_demo_ids' );
	return true;
}