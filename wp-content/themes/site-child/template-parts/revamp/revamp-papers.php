<?php
/**
 * Modernised Papers resource page renderer.
 *
 * Expects the global $rv config (set by page-papers.php):
 *   type, root_class, kicker, title, lead, actions[],
 *   papers[] (title|type|url), resource_links[].
 *
 * Every paper links to a real PDF in the media library.
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
$rv_papers     = isset( $rv['papers'] )     ? $rv['papers']     : array();
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

	<section class="rv-section rv-intro" aria-labelledby="rv-intro-title">
		<div class="rv-container">
			<div class="rv-intro__inner">
				<h2 id="rv-intro-title" class="rv-section-title"><?php echo esc_html( isset( $rv['intro_head'] ) ? $rv['intro_head'] : 'Research, reports and learning' ); ?></h2>
				<div class="rv-intro__text"><?php echo wp_kses_post( isset( $rv['intro_text'] ) ? $rv['intro_text'] : '' ); ?></div>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $rv_papers ) ) : ?>
		<section class="rv-section rv-papers" aria-label="Papers and publications">
			<div class="rv-container">
				<div class="rv-papers__grid">
					<?php foreach ( $rv_papers as $rv_paper ) : ?>
						<article class="rv-card rv-paper-card">
							<div class="rv-paper-card__icon" aria-hidden="true">
								<i class="fa fa-file-pdf-o"></i>
							</div>
							<div class="rv-paper-card__body">
								<span class="rv-paper-card__badge"><?php echo esc_html( $rv_paper['type'] ); ?></span>
								<h3 class="rv-paper-card__title"><?php echo esc_html( $rv_paper['title'] ); ?></h3>
								<a class="rv-text-link rv-text-link--download" href="<?php echo esc_url( $rv_paper['url'] ); ?>" target="_blank" rel="noopener">
									<i class="fa fa-download" aria-hidden="true"></i> Download paper
								</a>
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