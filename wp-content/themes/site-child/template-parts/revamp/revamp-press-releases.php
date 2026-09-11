<?php
/**
 * Modernised Press releases resource page renderer.
 *
 * Expects the global $rv config (set by page-irrigation-and-drainage.php):
 *   type, root_class, kicker, title, lead, actions[],
 *   featured (title|date|text) — preserved from the legacy page content,
 *   updates[] (title|link|date|excerpt|image) — live story posts,
 *   resource_links[] (icon|title|desc|url) — other resource hubs.
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

$rv_root_class = isset( $rv['root_class'] ) ? $rv['root_class'] : 'site-revamp--resource';
$rv_featured   = isset( $rv['featured'] )   ? $rv['featured']   : array();
$rv_updates    = isset( $rv['updates'] )    ? $rv['updates']    : array();
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

	<?php if ( ! empty( $rv_featured ) ) : ?>
		<section class="rv-section rv-release" aria-labelledby="rv-release-title">
			<div class="rv-container">
				<article class="rv-card rv-release__card">
					<div class="rv-release__body">
						<span class="rv-case-card__badge">Featured release</span>
						<?php if ( ! empty( $rv_featured['date'] ) ) : ?>
							<p class="rv-release__meta"><?php echo esc_html( $rv_featured['date'] ); ?></p>
						<?php endif; ?>
						<h2 id="rv-release-title" class="rv-release__title"><?php echo esc_html( $rv_featured['title'] ); ?></h2>
						<?php if ( ! empty( $rv_featured['text'] ) ) : ?>
							<div class="rv-release__text"><?php echo wp_kses_post( $rv_featured['text'] ); ?></div>
						<?php endif; ?>
					</div>
				</article>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $rv_updates ) ) : ?>
		<section class="rv-section rv-updates" aria-labelledby="rv-updates-title">
			<div class="rv-container">
				<div class="rv-section-head">
					<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>Latest updates</p>
					<h2 id="rv-updates-title" class="rv-section-title">News from SITE</h2>
					<p class="rv-section-sub">Stories and announcements from our teams and partners.</p>
				</div>
				<div class="rv-cases__grid">
					<?php foreach ( $rv_updates as $rv_update ) : ?>
						<article class="rv-card rv-case-card">
							<div class="rv-case-card__body">
								<span class="rv-case-card__badge"><?php echo esc_html( isset( $rv_update['type'] ) ? $rv_update['type'] : 'Update' ); ?></span>
								<h3 class="rv-case-card__title">
									<a href="<?php echo esc_url( $rv_update['link'] ); ?>"><?php echo esc_html( $rv_update['title'] ); ?></a>
								</h3>
								<?php if ( ! empty( $rv_update['excerpt'] ) ) : ?>
									<p class="rv-case-card__text"><?php echo esc_html( $rv_update['excerpt'] ); ?></p>
								<?php endif; ?>
								<div class="rv-case-card__foot">
									<?php if ( ! empty( $rv_update['date'] ) ) : ?>
										<span class="rv-case-card__meta"><?php echo esc_html( $rv_update['date'] ); ?></span>
									<?php endif; ?>
									<a class="rv-text-link" href="<?php echo esc_url( $rv_update['link'] ); ?>">
										Read more <i class="fa fa-angle-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="rv-section rv-resource-links" aria-labelledby="rv-resource-links-title">
		<div class="rv-container">
			<div class="rv-section-head">
				<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>Keep exploring</p>
				<h2 id="rv-resource-links-title" class="rv-section-title">More resources</h2>
			</div>
			<div class="rv-resource-links__grid">
				<?php foreach ( $rv['resource_links'] as $rv_rl ) : ?>
					<a class="rv-card rv-resource-link" href="<?php echo esc_url( $rv_rl['url'] ); ?>">
						<span class="rv-resource-link__icon" aria-hidden="true"><i class="fa <?php echo esc_attr( $rv_rl['icon'] ); ?>"></i></span>
						<span class="rv-resource-link__text">
							<span class="rv-resource-link__title"><?php echo esc_html( $rv_rl['title'] ); ?></span>
							<span class="rv-resource-link__desc"><?php echo esc_html( $rv_rl['desc'] ); ?></span>
						</span>
						<span class="rv-resource-link__arrow" aria-hidden="true"><i class="fa fa-angle-right"></i></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/revamp/revamp-cta' );
	?>

</main>