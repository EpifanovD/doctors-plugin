<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$experience = absint( get_post_meta( get_the_ID(), '_doctor_experience', true ) );
	$price      = absint( get_post_meta( get_the_ID(), '_doctor_price', true ) );
	$rating     = floatval( get_post_meta( get_the_ID(), '_doctor_rating', true ) );
	$summary    = has_excerpt() ? get_the_excerpt() : apply_filters( 'the_content', get_the_content() );
	?>
	<main class="doctors-single">
		<article <?php post_class(); ?>>
			<h1><?php echo esc_html( get_the_title() ); ?></h1>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="doctors-thumbnail">
					<?php echo wp_kses_post( get_the_post_thumbnail( get_the_ID(), 'large' ) ); ?>
				</div>
			<?php endif; ?>

			<div class="doctors-summary">
				<?php echo wp_kses_post( $summary ); ?>
			</div>

			<ul class="doctors-meta">
				<li>
					<strong><?php echo esc_html__( 'Experience', 'doctors' ); ?>:</strong>
					<?php echo esc_html( $experience ? number_format_i18n( $experience ) : '—' ); ?>
				</li>
				<li>
					<strong><?php echo esc_html__( 'Price from', 'doctors' ); ?>:</strong>
					<?php echo esc_html( $price ? number_format_i18n( $price ) : '—' ); ?>
				</li>
				<li>
					<strong><?php echo esc_html__( 'Rating', 'doctors' ); ?>:</strong>
					<?php echo esc_html( $rating ? number_format_i18n( $rating, 1 ) : '—' ); ?>
				</li>
				<li>
					<strong><?php echo esc_html__( 'Specialization', 'doctors' ); ?>:</strong>
					<?php
					$specializations = get_the_terms( get_the_ID(), 'specialization' );
					if ( ! empty( $specializations ) && ! is_wp_error( $specializations ) ) {
						$names = wp_list_pluck( $specializations, 'name' );
						echo esc_html( implode( ', ', $names ) );
					} else {
						echo esc_html( '—' );
					}
					?>
				</li>
				<li>
					<strong><?php echo esc_html__( 'City', 'doctors' ); ?>:</strong>
					<?php
					$cities = get_the_terms( get_the_ID(), 'city' );
					if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) {
						$names = wp_list_pluck( $cities, 'name' );
						echo esc_html( implode( ', ', $names ) );
					} else {
						echo esc_html( '—' );
					}
					?>
				</li>
			</ul>
		</article>
	</main>
	<?php
endwhile;

get_footer();
