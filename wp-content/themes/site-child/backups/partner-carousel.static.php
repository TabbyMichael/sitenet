<?php
/**
 * Partners Strip — logo wall (this was referenced by front-page.php but the
 * file was missing, silently breaking the section).
 *
 * Uses the site_partner CPT with the ACF "Partner Logo" field.
 * Gracefully renders nothing when no partners exist.
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site_partners = new WP_Query(
	array(
		'post_type'      => 'site_partner',
		'posts_per_page' => 18,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>

<section class="site-partners-section">
	<div class="container">
		<div class="site-section-title">
			<h2>Our Donors</h2>
		</div>
		<div class="site-donor-grid">
			<div class="site-donor-logo site-donor-logo-text">APT</div>
			<div class="site-donor-logo site-donor-logo-text">CIPE</div>
			<div class="site-donor-logo site-donor-logo-text">KMT</div>
			<div class="site-donor-logo site-donor-logo-text">MESPT</div>
			<div class="site-donor-logo site-donor-logo-text">MCI Relief</div>
			<div class="site-donor-logo">
				<img src="<?php echo esc_url( content_url( 'uploads/2025/03/ilo-scaled-1.jpg' ) ); ?>" alt="International Labour Organization">
			</div>
			<div class="site-donor-logo">
				<img src="<?php echo esc_url( content_url( 'uploads/2021/11/eu.jpg' ) ); ?>" alt="European Union">
			</div>
		</div>

		<div class="site-section-title">
			<h2>Our Partners</h2>
		</div>
		<?php if ( $site_partners->have_posts() ) : ?>
			<div class="site-partner-logo-grid">
				<?php while ( $site_partners->have_posts() ) : ?>
					<?php $site_partners->the_post(); ?>
					<?php
					$site_logo    = function_exists( 'get_field' ) ? get_field( 'partner_logo' ) : array();
					$site_logo_id = isset( $site_logo['ID'] ) ? absint( $site_logo['ID'] ) : ( is_numeric( $site_logo ) ? absint( $site_logo ) : 0 );
					if ( ! $site_logo_id ) {
						continue;
					}
					?>
					<a class="partner-logo-box" href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
						<?php echo wp_get_attachment_image( $site_logo_id, 'medium', false, array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
					</a>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<div class="site-donor-grid site-partner-fallback-grid">
				<div class="site-donor-logo">
					<img src="<?php echo esc_url( content_url( 'uploads/2021/11/logo-150x90.png' ) ); ?>" alt="SITE partner">
				</div>
				<div class="site-donor-logo site-donor-logo-text">Kenya</div>
				<div class="site-donor-logo site-donor-logo-text">County Gov.</div>
				<div class="site-donor-logo site-donor-logo-text">Youth Fund</div>
				<div class="site-donor-logo site-donor-logo-text">NCA</div>
				<div class="site-donor-logo site-donor-logo-text">FAO</div>
				<div class="site-donor-logo site-donor-logo-text">DCA</div>
				<div class="site-donor-logo site-donor-logo-text">ADSE</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php wp_reset_postdata(); ?>
