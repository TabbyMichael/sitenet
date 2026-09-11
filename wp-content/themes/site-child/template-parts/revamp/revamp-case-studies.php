<?php
/**
 * Modernised Case Studies resource page renderer.
 *
 * Expects the global $rv config (set by page-case-studys.php):
 *   type, root_class, kicker, title, lead, hero_image, hero_image_alt,
 *   actions[], cases[] (post cards with title|link|date|excerpt|image),
 *   showcases[] (media strip url|alt|caption), links to other resources.
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
$rv_cases      = isset( $rv['cases'] )      ? $rv['cases']      : array();
$rv_showcases  = isset( $rv['showcases'] )  ? $rv['showcases']  : array();
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

	<?php if ( ! empty( $rv_cases ) ) : ?>
		<section class="rv-section rv-cases" aria-labelledby="rv-cases-title">
			<div class="rv-container">
				<div class="rv-section-head">
					<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>Stories of change</p>
					<h2 id="rv-cases-title" class="rv-section-title">Case studies from our work</h2>
					<p class="rv-section-sub">Each case study documents a real SITE intervention — the challenge, the approach and the difference it made.</p>
				</div>
				<div class="rv-cases__grid">
					<?php $rv_index = 0; foreach ( $rv_cases as $rv_case ) :
				$rv_index++;
				$rv_case_is_pdf = ! empty( $rv_case['is_pdf'] );
				$rv_case_link   = esc_url( $rv_case['link'] );
				$rv_link_atts   = $rv_case_is_pdf ? ' target="_blank" rel="noopener"' : '';
				$rv_case_num    = sprintf( '%02d', $rv_index );
				?>
						<article class="rv-card rv-case-card">
							<?php if ( ! empty( $rv_case['image'] ) ) : ?>
								<a class="rv-case-card__media" href="<?php echo $rv_case_link; ?>"<?php echo $rv_link_atts; ?> tabindex="-1" aria-hidden="true">
									<img class="rv-case-card__img"
										src="<?php echo esc_url( $rv_case['image'] ); ?>"
										alt="<?php echo esc_attr( isset( $rv_case['alt'] ) ? $rv_case['alt'] : '' ); ?>"
										loading="lazy" decoding="async" width="640" height="400">
									<span class="rv-case-card__overlay" aria-hidden="true"></span>
									<span class="rv-case-card__index" aria-hidden="true"><?php echo esc_html( $rv_case_num ); ?></span>
								</a>
							<?php else : ?>
								<a class="rv-case-card__media rv-case-card__media--placeholder" href="<?php echo $rv_case_link; ?>"<?php echo $rv_link_atts; ?> tabindex="-1" aria-hidden="true">
									<i class="fa fa-book" aria-hidden="true"></i>
									<span class="rv-case-card__index" aria-hidden="true"><?php echo esc_html( $rv_case_num ); ?></span>
								</a>
							<?php endif; ?>
							<div class="rv-case-card__body">
								<span class="rv-case-card__badge">
									<span class="rv-case-card__badge-dot" aria-hidden="true"></span>
									<?php echo esc_html( isset( $rv_case['type'] ) ? $rv_case['type'] : 'Case study' ); ?>
								</span>
								<h3 class="rv-case-card__title">
									<a href="<?php echo $rv_case_link; ?>"<?php echo $rv_link_atts; ?>><?php echo esc_html( $rv_case['title'] ); ?></a>
								</h3>
								<?php if ( ! empty( $rv_case['excerpt'] ) ) : ?>
									<p class="rv-case-card__text"><?php echo esc_html( $rv_case['excerpt'] ); ?></p>
								<?php endif; ?>
								<div class="rv-case-card__foot">
									<?php if ( ! empty( $rv_case['date'] ) ) : ?>
										<span class="rv-case-card__meta"><?php echo esc_html( $rv_case['date'] ); ?></span>
									<?php endif; ?>
								<?php if ( $rv_case_is_pdf ) : ?>
									<a class="rv-btn rv-btn--primary rv-case-card__download" href="<?php echo $rv_case_link; ?>"<?php echo $rv_link_atts; ?>>
										<i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download case study
								</a>
								<?php else : ?>
									<a class="rv-btn rv-btn--secondary rv-case-card__download" href="<?php echo $rv_case_link; ?>">
										Read the case <i class="fa fa-angle-right" aria-hidden="true"></i>
								</a>
								<?php endif; ?>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $rv_showcases ) ) : ?>
		<section class="rv-section rv-imagery" aria-labelledby="rv-showcase-title">
			<div class="rv-container">
				<div class="rv-section-head">
					<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>From the field</p>
					<h2 id="rv-showcase-title" class="rv-section-title">Moments behind the impact</h2>
				</div>
				<div class="rv-showcase__grid">
					<?php foreach ( $rv_showcases as $rv_shot ) : ?>
						<figure class="rv-showcase__item">
							<img class="rv-showcase__img"
								src="<?php echo esc_url( $rv_shot['url'] ); ?>"
								alt="<?php echo esc_attr( isset( $rv_shot['alt'] ) ? $rv_shot['alt'] : '' ); ?>"
								loading="lazy" decoding="async" width="640" height="427">
							<?php if ( ! empty( $rv_shot['caption'] ) ) : ?>
								<figcaption><?php echo esc_html( $rv_shot['caption'] ); ?></figcaption>
							<?php endif; ?>
						</figure>
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