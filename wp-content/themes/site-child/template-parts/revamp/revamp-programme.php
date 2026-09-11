<?php
/**
 * Modernised programme detail page renderer.
 *
 * Expects the global $rv config (set by the page-{slug}.php shell):
 *   type, root_class, kicker, title, lead, hero_image, hero_image_alt,
 *   actions[], intro_head, intro_text, features[][icon|title|text|items],
 *   gallery[][] (url|alt), stories[] (queried live), stories_url,
 *   programmes[][kicker|title|summary|link|image|alt],
 *   cta_title, cta_text, cta_url, cta_label.
 *
 * Stories are read from the pre-built $rv['stories'] array so the section
 * always shows real content and degrades to a friendly empty state when
 * absent.
 *
 * Scope note: page-{slug}.php shells set $rv in the including file's scope
 * and then call get_template_part(), which loads this file inside a
 * function — so the caller's variable is NOT visible here. The shell must
 * therefore publish the config on $GLOBALS['site_child_revamp'] before
 * calling get_template_part() (done in every revamp shell).
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rv = isset( $GLOBALS['site_child_revamp'] ) && is_array( $GLOBALS['site_child_revamp'] )
	? $GLOBALS['site_child_revamp']
	: array();

$rv_root_class  = isset( $rv['root_class'] )  ? $rv['root_class']  : 'site-revamp--programme';
$rv_intro_head  = isset( $rv['intro_head'] )  ? $rv['intro_head']  : '';
$rv_intro_text  = isset( $rv['intro_text'] )  ? $rv['intro_text']  : '';
$rv_features    = isset( $rv['features'] )    ? $rv['features']    : array();
$rv_gallery     = isset( $rv['gallery'] )     ? $rv['gallery']     : array();
$rv_stories     = isset( $rv['stories'] )     ? $rv['stories']     : array();
$rv_stories_url = isset( $rv['stories_url'] ) ? $rv['stories_url'] : '/stories/';
$rv_programmes  = isset( $rv['programmes'] )  ? $rv['programmes']  : array();
?>

<main id="primary" class="site-main site-revamp <?php echo esc_attr( $rv_root_class ); ?>">

	<?php
	get_template_part( 'template-parts/revamp/revamp-hero' );
	?>

	<?php if ( $rv_intro_head || $rv_intro_text ) : ?>
		<section class="rv-section rv-intro" aria-labelledby="rv-intro-title">
			<div class="rv-container">
				<div class="rv-intro__inner">
					<?php if ( $rv_intro_head ) : ?>
						<h2 id="rv-intro-title" class="rv-section-title"><?php echo esc_html( $rv_intro_head ); ?></h2>
					<?php endif; ?>
					<?php if ( $rv_intro_text ) : ?>
						<div class="rv-intro__text"><?php echo wp_kses_post( $rv_intro_text ); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( get_the_content() ) : ?>
		<section class="rv-section rv-content" aria-label="Page content">
			<div class="rv-container">
				<div class="rv-content__inner">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $rv_features ) ) : ?>
		<section class="rv-section rv-features" aria-labelledby="rv-features-title">
			<div class="rv-container">
				<div class="rv-section-head">
					<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>What we do</p>
					<h2 id="rv-features-title" class="rv-section-title"><?php echo esc_html( $rv_intro_head ? $rv_intro_head : get_the_title() ); ?></h2>
					<p class="rv-section-sub"><?php echo esc_html( isset( $rv['features_sub'] ) ? $rv['features_sub'] : 'How we work with communities, enterprises and partners to create lasting change.' ); ?></p>
				</div>
				<div class="rv-features__grid">
					<?php foreach ( $rv_features as $rv_feature ) : ?>
						<article class="rv-card rv-feature-card">
							<div class="rv-feature-card__icon" aria-hidden="true">
								<i class="fa <?php echo esc_attr( $rv_feature['icon'] ); ?>"></i>
							</div>
							<h3 class="rv-feature-card__title"><?php echo esc_html( $rv_feature['title'] ); ?></h3>
							<p class="rv-feature-card__text"><?php echo esc_html( $rv_feature['text'] ); ?></p>
							<?php if ( ! empty( $rv_feature['items'] ) ) : ?>
								<ul class="rv-feature-card__list">
									<?php foreach ( $rv_feature['items'] as $rv_item ) : ?>
										<li><?php echo esc_html( $rv_item ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $rv_gallery ) ) : ?>
		<section class="rv-section rv-imagery" aria-label="Photo highlights">
			<div class="rv-container">
				<div class="rv-imagery__grid">
					<?php foreach ( $rv_gallery as $rv_shot ) : ?>
						<figure class="rv-imagery__item">
							<img class="rv-imagery__img"
								src="<?php echo esc_url( $rv_shot['url'] ); ?>"
								alt="<?php echo esc_attr( isset( $rv_shot['alt'] ) ? $rv_shot['alt'] : '' ); ?>"
								loading="lazy" decoding="async" width="640" height="427">
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $rv_programmes ) ) : ?>
		<section class="rv-section rv-programs" aria-labelledby="rv-programs-title">
			<div class="rv-container">
				<div class="rv-section-head">
					<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>Our programmes</p>
					<h2 id="rv-programs-title" class="rv-section-title">Explore related programmes</h2>
					<p class="rv-section-sub">Our work spans livelihoods, markets and resilience. See how these programmes connect.</p>
				</div>
				<div class="rv-programs__grid">
					<?php foreach ( $rv_programmes as $rv_programme ) : ?>
						<article class="rv-card rv-program-card">
							<a class="rv-program-card__media" href="<?php echo esc_url( $rv_programme['link'] ); ?>" tabindex="-1" aria-hidden="true">
								<img class="rv-program-card__img"
									src="<?php echo esc_url( $rv_programme['image'] ); ?>"
									alt="" loading="lazy" decoding="async" width="640" height="427">
							</a>
							<div class="rv-program-card__body">
								<span class="rv-program-card__kicker"><?php echo esc_html( $rv_programme['kicker'] ); ?></span>
								<h3 class="rv-program-card__title">
									<a href="<?php echo esc_url( $rv_programme['link'] ); ?>"><?php echo esc_html( $rv_programme['title'] ); ?></a>
								</h3>
								<p class="rv-program-card__text"><?php echo esc_html( $rv_programme['summary'] ); ?></p>
								<a class="rv-text-link" href="<?php echo esc_url( $rv_programme['link'] ); ?>">
									View programme <i class="fa fa-angle-right" aria-hidden="true"></i>
								</a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="rv-section rv-stories" aria-labelledby="rv-stories-title">
		<div class="rv-container">
			<div class="rv-section-head rv-section-head--row">
				<div>
					<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>Stories from the field</p>
					<h2 id="rv-stories-title" class="rv-section-title"><?php echo esc_html( isset( $rv['stories_head'] ) ? $rv['stories_head'] : 'Latest stories' ); ?></h2>
					<p class="rv-section-sub"><?php echo esc_html( isset( $rv['stories_text'] ) ? $rv['stories_text'] : 'Real updates from communities we work with.' ); ?></p>
				</div>
				<a class="rv-btn rv-btn--ghost" href="<?php echo esc_url( $rv_stories_url ); ?>">
					View all stories <i class="fa fa-angle-right" aria-hidden="true"></i>
				</a>
			</div>

			<?php if ( ! empty( $rv_stories ) ) : ?>
				<div class="rv-stories__grid">
					<?php foreach ( $rv_stories as $rv_story ) : ?>
						<article class="rv-card rv-story-card">
							<?php if ( $rv_story['image'] ) : ?>
								<a class="rv-story-card__media" href="<?php echo esc_url( $rv_story['link'] ); ?>" tabindex="-1" aria-hidden="true">
									<img class="rv-story-card__img"
										src="<?php echo esc_url( $rv_story['image'] ); ?>"
										alt="" loading="lazy" decoding="async" width="640" height="427">
								</a>
							<?php else : ?>
								<div class="rv-story-card__media rv-story-card__media--placeholder" aria-hidden="true">
									<i class="fa fa-file-text-o"></i>
								</div>
							<?php endif; ?>
							<div class="rv-story-card__body">
								<?php if ( ! empty( $rv_story['date'] ) ) : ?>
									<span class="rv-story-card__meta"><?php echo esc_html( $rv_story['date'] ); ?></span>
								<?php endif; ?>
								<h3 class="rv-story-card__title">
									<a href="<?php echo esc_url( $rv_story['link'] ); ?>"><?php echo esc_html( $rv_story['title'] ); ?></a>
								</h3>
								<a class="rv-text-link" href="<?php echo esc_url( $rv_story['link'] ); ?>">
									Read story <i class="fa fa-angle-right" aria-hidden="true"></i>
								</a>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="rv-empty" role="status">
					<div class="rv-empty__icon" aria-hidden="true"><i class="fa fa-folder-open"></i></div>
					<h3 class="rv-empty__title">New stories are on the way.</h3>
					<p class="rv-empty__text">We are preparing stories from this programme. Check back soon, or explore our other programmes.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/revamp/revamp-cta' );
	?>

</main>