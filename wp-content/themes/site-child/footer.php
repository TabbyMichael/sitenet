<?php
/**
 * SITE Enterprise Promotion — Modern Footer (child theme override).
 *
 * Replaces the parent theme widget-area footer with a premium dark,
 * multi-section institutional footer:
 *
 *   1. Brand / mission top area + social links
 *   2. Four-column grid: About · Explore · Working Hours · Contact
 *   3. "Explore Our Work" CTA panel
 *   4. Copyright bar
 *   5. Floating circular back-to-top button
 *
 * Only links, contact details and social profiles that actually exist on
 * the site are used. Styling: assets/css/footer.css
 * Behaviour: assets/js/footer.js (IntersectionObserver reveal + smooth top).
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Brand logo — official SITE logo (assets/images/logo.png, 599x542).
// Referenced as a theme asset so the footer works without any DB/Customizer dependency.
$footer_logo        = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo.png' );
$footer_logo_retina = '';
$footer_logo_srcset = $footer_logo;

// Weekly schedule — the currently open day (Mon–Fri) gets a subtle gold dot.
$footer_open_day = wp_date( 'l' );
$footer_hours    = array(
	'Monday'    => '8:00 AM – 5:00 PM',
	'Tuesday'   => '8:00 AM – 5:00 PM',
	'Wednesday' => '8:00 AM – 5:00 PM',
	'Thursday'  => '8:00 AM – 5:00 PM',
	'Friday'    => '8:00 AM – 5:00 PM',
	'Saturday'  => 'CLOSED',
	'Sunday'    => 'CLOSED',
);

// Only real pages on this site.
$footer_explore = array(
	'About Us'    => home_url( '/about-us/' ),
	'Our Work'    => home_url( '/ourwork/' ),
	'Stories'     => home_url( '/blog/' ),
	'Blog'        => home_url( '/blog/' ),
	'Partnership' => home_url( '/make-an-appointment/' ),
	'Contact Us'  => home_url( '/contact-us/' ),
);
?>

<footer class="site-footer" id="site-footer">

	<!-- 1 — BRAND / MISSION TOP AREA -->
	<div class="site-footer__top">
		<div class="site-footer__container site-footer__top-inner" data-reveal>

			<div class="site-footer__brand">
				<?php if ( '' !== $footer_logo ) : ?>
					<a class="site-footer__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="SITE Enterprise Promotion — home">
						<img
							class="site-footer__logo"
							src="<?php echo $footer_logo; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedImage -- theme_mod URL, same pattern as header. ?>"
							srcset="<?php echo esc_attr( $footer_logo_srcset ); ?>"
							alt="SITE Enterprise Promotion logo"
							width="152"
							height="138"
							decoding="async"
						>
					</a>
				<?php else : ?>
					<a class="site-footer__logo-link site-footer__logo-link--text" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
					</a>
				<?php endif; ?>

				<p class="site-footer__mission">Creating opportunities.<br>Strengthening communities.</p>
			</div>

			<div class="site-footer__connect">
				<h2 class="site-footer__heading">Connect with SITE</h2>
				<ul class="site-footer__social">
					<li>
						<a href="https://x.com/siteenterprise" target="_blank" rel="noopener noreferrer" aria-label="SITE Enterprise Promotion on X (Twitter)">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M17.53 3H20.5l-6.49 7.42L21.64 21h-5.98l-4.68-6.12L5.62 21H2.64l6.94-7.93L2.36 3h6.13l4.23 5.59L17.53 3zm-1.05 16.2h1.66L7.16 4.7H5.38l11.1 14.5z"/></svg>
						</a>
					</li>
					<li>
						<a href="https://www.instagram.com/siteenterprise/" target="_blank" rel="noopener noreferrer" aria-label="SITE Enterprise Promotion on Instagram">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/><circle cx="17.2" cy="6.8" r="1.3" fill="currentColor"/></svg>
						</a>
					</li>
					<li>
						<a href="https://www.youtube.com/@SiteEnterpriseKenya" target="_blank" rel="noopener noreferrer" aria-label="SITE Enterprise Promotion on YouTube">
							<svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.76-1.77C18.28 5 12 5 12 5s-6.28 0-7.84.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.76 1.77C5.72 19 12 19 12 19s6.28 0 7.84-.43a2.5 2.5 0 0 0 1.76-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15.2V8.8L15.6 12 10 15.2z"/></svg>
						</a>
					</li>
				</ul>
			</div>

		</div>
	</div>

	<!-- 2 — FOUR-COLUMN GRID -->
	<div class="site-footer__middle">
		<div class="site-footer__container site-footer__grid">

			<section class="site-footer__col" data-reveal style="--reveal-delay:80ms" aria-label="About SITE">
				<h2 class="site-footer__heading">About SITE</h2>
				<p class="site-footer__about">
					SITE Enterprise Promotion (SITE) is a Kenyan not-for-profit development
					organization, established in 1996, whose goal is the promotion of employment
					opportunities and economic growth among communities.
				</p>
			</section>

			<nav class="site-footer__col" data-reveal style="--reveal-delay:140ms" aria-label="Footer navigation">
				<h2 class="site-footer__heading">Explore</h2>
				<ul class="site-footer__links">
					<?php foreach ( $footer_explore as $footer_label => $footer_url ) : ?>
						<?php $footer_is_external = ( 0 === strpos( $footer_url, 'http' ) && false === strpos( $footer_url, wp_parse_url( home_url(), PHP_URL_HOST ) ) ); ?>
						<li>
							<a href="<?php echo esc_url( $footer_url ); ?>"<?php echo $footer_is_external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
								<span><?php echo esc_html( $footer_label ); ?></span>
								<svg class="site-footer__link-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<section class="site-footer__col" data-reveal style="--reveal-delay:200ms" aria-label="Working hours">
				<h2 class="site-footer__heading">Working Hours</h2>
				<ul class="site-footer__hours">
					<?php foreach ( $footer_hours as $footer_day => $footer_time ) : ?>
						<li class="site-footer__hours-row<?php echo ( 'CLOSED' !== $footer_time && $footer_day === $footer_open_day ) ? ' is-open' : ''; ?>">
							<span class="site-footer__hours-day"><?php echo esc_html( $footer_day ); ?></span>
							<span class="site-footer__hours-time<?php echo 'CLOSED' === $footer_time ? ' is-closed' : ''; ?>"><?php echo esc_html( $footer_time ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

			<section class="site-footer__col" data-reveal style="--reveal-delay:260ms" aria-label="Contact details">
				<h2 class="site-footer__heading">Contact</h2>
				<ul class="site-footer__contact">
					<li>
						<a class="site-footer__contact-link" href="mailto:info@sitenet.org">
							<svg class="site-footer__contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="2"/><path d="m3 7 9 6 9-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
							<span>info@sitenet.org</span>
						</a>
					</li>
					<li>
						<a class="site-footer__contact-link" href="tel:+254721229029">
							<svg class="site-footer__contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
							<span>+254 (0) 721 229029</span>
						</a>
					</li>
					<li>
						<a class="site-footer__contact-link" href="https://www.google.com/maps/dir/?api=1&amp;destination=Waleeh%20Motors%2C%20Ngong%20Road%2C%20Nairobi%2C%20Kenya" target="_blank" rel="noopener noreferrer" aria-label="Get directions to Waleeh Motors, Ngong Road, Nairobi">
							<svg class="site-footer__contact-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M12 21s-7-5.1-7-11a7 7 0 1 1 14 0c0 5.9-7 11-7 11z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="10" r="2.6" stroke="currentColor" stroke-width="2"/></svg>
							<span>ISMA Building, Along Ngong Road, Nairobi</span>
						</a>
					</li>
				</ul>
			</section>

		</div>
	</div>

	<!-- 3 — CTA PANEL -->
	<div class="site-footer__container" data-reveal style="--reveal-delay:120ms">
		<div class="site-footer__cta">
			<div class="site-footer__cta-text">
				<p class="site-footer__cta-title">Interested in working with SITE?</p>
				<p class="site-footer__cta-sub">Discover our work and initiatives.</p>
			</div>
			<a class="site-footer__cta-button" href="<?php echo esc_url( home_url( '/ourwork/' ) ); ?>">
				Explore Our Work
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</a>
		</div>
	</div>

	<!-- 4 — COPYRIGHT BAR -->
	<div class="site-footer__bottom">
		<div class="site-footer__container site-footer__bottom-inner">
			<p class="site-footer__copyright">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> SITE Enterprise Promotion</p>
			<a class="site-footer__totop-link" href="#site-header">Back to top
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M12 19V5m-6 6 6-6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</a>
		</div>
	</div>

	<!-- 5 — FLOATING BACK-TO-TOP BUTTON -->
	<a class="site-footer__totop" href="#site-header" aria-label="Back to top">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M12 19V5m-6 6 6-6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
	</a>

</footer>

</div><!-- end layout boxed wrapper -->

<?php wp_footer(); ?>
</body>
</html>