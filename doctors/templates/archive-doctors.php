<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$current_specialization = isset( $_GET['specialization'] ) ? sanitize_title( sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) ) : '';
$current_city           = isset( $_GET['city'] ) ? sanitize_title( sanitize_text_field( wp_unslash( $_GET['city'] ) ) ) : '';
$current_sort           = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';

$specialization_terms = get_terms(
	array(
		'taxonomy'   => 'specialization',
		'hide_empty' => false,
	)
);
$city_terms = get_terms(
	array(
		'taxonomy'   => 'city',
		'hide_empty' => false,
	)
);
?>
<main class="doctors-archive">
	<header>
		<h1><?php echo esc_html( post_type_archive_title( '', false ) ); ?></h1>
	</header>

	<form method="get" class="doctors-filters" action="<?php echo esc_url( get_post_type_archive_link( 'doctors' ) ); ?>">
		<label>
			<?php echo esc_html__( 'Специализация', 'doctors' ); ?>
			<select name="specialization">
				<option value=""><?php echo esc_html__( 'Все', 'doctors' ); ?></option>
				<?php if ( ! empty( $specialization_terms ) && ! is_wp_error( $specialization_terms ) ) : ?>
					<?php foreach ( $specialization_terms as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_specialization, $term->slug ); ?>>
							<?php echo esc_html( $term->name ); ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</label>

		<label>
			<?php echo esc_html__( 'Город', 'doctors' ); ?>
			<select name="city">
				<option value=""><?php echo esc_html__( 'Все', 'doctors' ); ?></option>
				<?php if ( ! empty( $city_terms ) && ! is_wp_error( $city_terms ) ) : ?>
					<?php foreach ( $city_terms as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_city, $term->slug ); ?>>
							<?php echo esc_html( $term->name ); ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</label>

		<label>
			<?php echo esc_html__( 'Сортировка', 'doctors' ); ?>
			<select name="sort">
				<option value=""><?php echo esc_html__( 'По умолчанию', 'doctors' ); ?></option>
				<option value="rating_desc" <?php selected( $current_sort, 'rating_desc' ); ?>>
					<?php echo esc_html__( 'Рейтинг (по убыванию)', 'doctors' ); ?>
				</option>
				<option value="price_asc" <?php selected( $current_sort, 'price_asc' ); ?>>
					<?php echo esc_html__( 'Цена (по возрастанию)', 'doctors' ); ?>
				</option>
				<option value="experience_desc" <?php selected( $current_sort, 'experience_desc' ); ?>>
					<?php echo esc_html__( 'Стаж (по убыванию)', 'doctors' ); ?>
				</option>
			</select>
		</label>

		<button type="submit"><?php echo esc_html__( 'Применить', 'doctors' ); ?></button>
	</form>

	<?php if ( have_posts() ) : ?>
		<div class="doctors-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				$experience = absint( get_post_meta( get_the_ID(), '_doctor_experience', true ) );
				$price      = absint( get_post_meta( get_the_ID(), '_doctor_price', true ) );
				$rating     = floatval( get_post_meta( get_the_ID(), '_doctor_rating', true ) );
				$spec_terms = get_the_terms( get_the_ID(), 'specialization' );
				if ( ! empty( $spec_terms ) && ! is_wp_error( $spec_terms ) ) {
					$spec_terms = array_slice( $spec_terms, 0, 2 );
				} else {
					$spec_terms = array();
				}
				?>
				<article <?php post_class( 'doctors-card' ); ?>>
					<a href="<?php echo esc_url( get_permalink() ); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="doctors-card-thumb">
								<?php echo wp_kses_post( get_the_post_thumbnail( get_the_ID(), 'thumbnail' ) ); ?>
							</div>
						<?php endif; ?>
						<h2 class="doctors-card-title"><?php echo esc_html( get_the_title() ); ?></h2>
					</a>

					<div class="doctors-card-meta">
						<div>
							<strong><?php echo esc_html__( 'Specialization', 'doctors' ); ?>:</strong>
							<?php
							if ( ! empty( $spec_terms ) ) {
								$spec_names = wp_list_pluck( $spec_terms, 'name' );
								echo esc_html( implode( ', ', $spec_names ) );
							} else {
								echo esc_html( '—' );
							}
							?>
						</div>
						<div>
							<strong><?php echo esc_html__( 'Experience', 'doctors' ); ?>:</strong>
							<?php echo esc_html( $experience ? number_format_i18n( $experience ) : '—' ); ?>
						</div>
						<div>
							<strong><?php echo esc_html__( 'Price from', 'doctors' ); ?>:</strong>
							<?php echo esc_html( $price ? number_format_i18n( $price ) : '—' ); ?>
						</div>
						<div>
							<strong><?php echo esc_html__( 'Rating', 'doctors' ); ?>:</strong>
							<?php echo esc_html( $rating ? number_format_i18n( $rating, 1 ) : '—' ); ?>
						</div>
					</div>

					<a class="doctors-card-link" href="<?php echo esc_url( get_permalink() ); ?>">
						<?php echo esc_html__( 'View profile', 'doctors' ); ?>
					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<?php
		global $wp_query;
		$big        = 999999999;
		$paged      = max( 1, get_query_var( 'paged' ) );
		$add_args   = array();
		if ( $current_specialization ) {
			$add_args['specialization'] = $current_specialization;
		}
		if ( $current_city ) {
			$add_args['city'] = $current_city;
		}
		if ( $current_sort ) {
			$add_args['sort'] = $current_sort;
		}

		$pagination = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'current'   => $paged,
				'total'     => $wp_query->max_num_pages,
				'type'      => 'array',
				'add_args'  => $add_args,
				'prev_text' => __( 'Previous', 'doctors' ),
				'next_text' => __( 'Next', 'doctors' ),
			)
		);

		if ( ! empty( $pagination ) ) :
			?>
			<nav class="doctors-pagination">
				<?php foreach ( $pagination as $link ) : ?>
					<?php echo wp_kses_post( $link ); ?>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<p><?php echo esc_html__( 'No doctors found.', 'doctors' ); ?></p>
	<?php endif; ?>
</main>
<?php

get_footer();
