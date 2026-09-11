<?php
/**
 * Modernised Video Gallery page renderer.
 *
 * Expects the global $rv config (set by page-gallery-full-width-2.php):
 *   type, root_class, kicker, title, lead, actions[],
 *   videos[] (title|url|meta) — the real YouTube links from the legacy page.
 *
 * Videos open on YouTube (no third-party embeds or thumbnail requests).
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Scope: shells publish $rv on $GLOBALS (see revamp-programme.php). */
$rv = isset( $GLOBALS['site_child_revamp'] ) && is_array( $GLOBALS['site_child_revamp'] )
	? $GLOBALS['site_child_revamp']
	: array();

$rv_root_class = isset( $rv['root_class'] ) ? $rv['root_class'] : 'site-revamp--gallery';
$rv_videos     = isset( $rv['videos'] )     ? $rv['videos']     : array();
?>

<main id="primary" class="site-main site-revamp <?php echo esc_attr( $rv_root_class ); ?>">

	<?php
	get_template_part( 'template-parts/revamp/revamp-hero' );
	?>

	<?php if ( get_the_content() ) : ?>
		<section class="rv-section rv-content" aria-label="Page content">
			<div class="rv-container">
				<div class="rv-content__inner">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="rv-section rv-videos" aria-labelledby="rv-videos-title">
		<div class="rv-container">
			<div class="rv-section-head">
				<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>Video gallery</p>
				<h2 id="rv-videos-title" class="rv-section-title">Stories on screen</h2>
				<p class="rv-section-sub">Watch our videos on YouTube — training, graduations and community moments.</p>
			</div>

			<?php if ( ! empty( $rv_videos ) ) : ?>
				<div class="rv-videos__grid">
					<?php foreach ( $rv_videos as $rv_video ) : ?>
						<a class="rv-card rv-video-card" href="<?php echo esc_url( $rv_video['url'] ); ?>" target="_blank" rel="noopener">
							<span class="rv-video-card__play" aria-hidden="true"><i class="fa fa-play"></i></span>
							<span class="rv-video-card__body">
								<span class="rv-video-card__title"><?php echo esc_html( $rv_video['title'] ); ?></span>
								<span class="rv-video-card__meta"><?php echo esc_html( isset( $rv_video['meta'] ) ? $rv_video['meta'] : 'SITE Enterprise Promotion · YouTube' ); ?></span>
								<span class="rv-text-link">Watch on YouTube <i class="fa fa-angle-right" aria-hidden="true"></i></span>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="rv-empty" role="status">
					<div class="rv-empty__icon" aria-hidden="true"><i class="fa fa-video-camera"></i></div>
					<h3 class="rv-empty__title">Videos are on the way.</h3>
					<p class="rv-empty__text">We are preparing short films from our programmes. Check back shortly.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/revamp/revamp-cta' );
	?>

</main>