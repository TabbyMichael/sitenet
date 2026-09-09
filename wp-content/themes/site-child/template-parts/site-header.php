<?php
/**
 * SITE Enterprise Promotion — Modern Header (child theme override).
 *
 * Replaces the parent theme header-default layout with a two-level header:
 *   1. Slim charcoal utility bar  — identity + contact + social.
 *   2. White main navigation bar  — logo (far left) + primary menu + CTA.
 *
 * Loaded automatically by header.php's get_template_part( 'headers/header', … ),
 * which resolves this file from the child theme before the parent theme copy.
 *
 * Styling: assets/css/header.css
 * Behaviour: assets/js/header.js (vanilla, no dependencies)
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Brand logo — official SITE logo (assets/images/logo.png, 599x542).
// Referenced as a theme asset so the header works without any DB/Customizer dependency.
$site_logo        = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo.png' );
$site_logo_retina = '';
$site_logo_srcset = $site_logo;
?>

<header class="site-header" id="site-header">

	<?php if ( 'hide' !== get_theme_mod( 'qt_topbar', 'show' ) && 'hide' !== get_field( 'topbar' ) ) : ?>

		<!-- LEVEL 1 — UTILITY BAR -->
		<div class="site-utility">
			<div class="site-container site-utility__inner">

				<span class="site-utility__brand">SITE Enterprise Promotion</span>

				<ul class="site-utility__list">

					<li class="site-utility__item site-utility__item--location">
						<svg class="site-utility__icon" width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M12 21s-7-5.1-7-11a7 7 0 1 1 14 0c0 5.9-7 11-7 11z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="10" r="2.6" stroke="currentColor" stroke-width="2"/></svg>
						<a href="https://www.google.com/maps/dir/?api=1&amp;destination=Waleeh%20Motors%2C%20Ngong%20Road%2C%20Nairobi%2C%20Kenya" target="_blank" rel="noopener noreferrer" aria-label="Get directions to Waleeh Motors, Ngong Road, Nairobi">ISMA Building, Along Ngong Road, Nairobi</a>
					</li>

					<li class="site-utility__item site-utility__item--phone">
						<svg class="site-utility__icon" width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
						<a href="tel:+254721229029">+254 721 229029</a>
					</li>

					<li class="site-utility__item site-utility__item--hours">
						<svg class="site-utility__icon" width="12" height="12" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3.5 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
						<span>Mon&ndash;Fri: 08:00&ndash;17:00</span>
					</li>

					<li class="site-utility__item site-utility__social">
						<a href="https://x.com/siteenterprise" target="_blank" rel="noopener noreferrer" aria-label="SITE Enterprise Promotion on X (Twitter)">
							<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M17.53 3H20.5l-6.49 7.42L21.64 21h-5.98l-4.68-6.12L5.62 21H2.64l6.94-7.93L2.36 3h6.13l4.23 5.59L17.53 3zm-1.05 16.2h1.66L7.16 4.7H5.38l11.1 14.5z"/></svg>
						</a>
						<a href="https://www.instagram.com/siteenterprise/" target="_blank" rel="noopener noreferrer" aria-label="SITE Enterprise Promotion on Instagram">
							<svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/><circle cx="17.2" cy="6.8" r="1.3" fill="currentColor"/></svg>
						</a>
						<a href="https://www.youtube.com/@SiteEnterpriseKenya" target="_blank" rel="noopener noreferrer" aria-label="SITE Enterprise Promotion on YouTube">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.76-1.77C18.28 5 12 5 12 5s-6.28 0-7.84.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.76 1.77C5.72 19 12 19 12 19s6.28 0 7.84-.43a2.5 2.5 0 0 0 1.76-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8zM10 15.2V8.8L15.6 12 10 15.2z"/></svg>
						</a>
					</li>

				</ul>
			</div>
		</div>

	<?php endif; ?>



	<!-- LEVEL 2 — MAIN NAVIGATION -->
	<div class="site-nav" id="site-nav">
		<div class="site-container site-nav__inner">

			<a
				href="<?php echo esc_url( home_url( '/' ) ); ?>"
				class="site-logo"
				rel="home"
				aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> &mdash; <?php esc_attr_e( 'Home', 'site-child' ); ?>"
			>
				<?php if ( '' !== $site_logo ) : ?>
					<img
						src="<?php echo $site_logo; // esc_url() applied above. ?>"
						srcset="<?php echo esc_attr( $site_logo_srcset ); ?>"
						alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
						width="152"
						height="138"
						fetchpriority="high"
						decoding="async"
					/>
				<?php else : ?>
					<span class="site-logo__text"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</a>



			<nav id="site-nav-menu" class="site-nav__menu" aria-label="<?php esc_attr_e( 'Primary navigation', 'site-child' ); ?>" data-header-menu>
				<?php
				if ( has_nav_menu( 'primary' ) ) :
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							/*
							 * Intentionally NOT "main-navigation": the parent theme's
							 * legacy jQuery targets that class and would inject old
							 * mobile toggles into the rebuilt markup.
							 */
							'menu_class'     => 'site-nav__list',
							'walker'         => new Aria_Walker_Nav_Menu(),
							/*
							 * No role="menubar": modern dropdown pattern uses plain
							 * list semantics + aria-expanded on the parent item.
							 *
							 * No id="%1$s" either: WP would emit the legacy
							 * menu-primary-navigation id, which the old child
							 * stylesheet targets with ID-specificity rules
							 * (white nav text, gold pill hovers) that would
							 * fight this rebuilt header.
							 */
							'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
						)
					);
				endif;
				?>
			</nav>
			<div class="site-nav__actions">

				<button
					type="button"
					class="site-theme-toggle"
					data-theme-toggle
					aria-pressed="false"
					aria-label="<?php esc_attr_e( 'Switch to dark mode', 'site-child' ); ?>"
					title="<?php esc_attr_e( 'Switch color theme', 'site-child' ); ?>"
				>
					<span class="screen-reader-text"><?php esc_html_e( 'Toggle color theme', 'site-child' ); ?></span>
					<svg class="site-theme-toggle__icon site-theme-toggle__icon--sun" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false">
						<circle cx="12" cy="12" r="5.2" fill="currentColor"/>
						<g stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
							<line x1="12" y1="2.6" x2="12" y2="6.2"/>
							<line x1="21.4" y1="5.4" x2="17.6" y2="8.2"/>
							<line x1="21.4" y1="12" x2="17.6" y2="12"/>
							<line x1="21.4" y1="18.6" x2="17.6" y2="15.8"/>
							<line x1="12" y1="21.4" x2="12" y2="17.8"/>
							<line x1="2.6" y1="18.6" x2="6.4" y2="15.8"/>
							<line x1="2.6" y1="12" x2="6.4" y2="12"/>
							<line x1="2.6" y1="5.4" x2="6.4" y2="8.2"/>
						</g>
					</svg>
					<svg class="site-theme-toggle__icon site-theme-toggle__icon--moon" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false">
						<mask id="site-theme-moon-mask">
							<rect x="0" y="0" width="24" height="24" fill="#ffffff"/>
							<circle cx="17.4" cy="9.4" r="9.6" fill="#000000"/>
						</mask>
						<circle cx="12" cy="12" r="9.2" fill="currentColor" mask="url(#site-theme-moon-mask)"/>
						<circle cx="7.4" cy="6.4" r="1.4" fill="currentColor"/>
						<circle cx="5.8" cy="16.4" r="1.05" fill="currentColor"/>
					</svg>
				</button>

			<button
				type="button"
				class="site-nav__toggle"
				data-header-toggle
				aria-expanded="false"
				aria-controls="site-nav-menu"
			>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'site-child' ); ?></span>
				<span class="site-nav__toggle-bars" aria-hidden="true">
					<span class="site-nav__toggle-bar"></span>
					<span class="site-nav__toggle-bar"></span>
					<span class="site-nav__toggle-bar"></span>
				</span>
			</button>

			</div>

		</div>
	</div>

	<?php
	/*
	 * Legacy compatibility shim.
	 *
	 * The parent theme's RequireJS bundle (main.min.js → stickynav.js) calls
	 * $( '.navigation' ).offset().top at load time. With no .navigation element
	 * left in this rebuilt header, jQuery 3 would throw a TypeError and abort
	 * the entire bundle (tooltips, accordions, carousels, hash-scrolling).
	 *
	 * This hidden span keeps the legacy selector alive with zero visual or
	 * behavioural impact: it measures 0×0 at (0,0), so stickynav.js never
	 * toggles anything this header uses (the new scrolled state is handled
	 * by assets/js/header.js via .site-header--scrolled).
	 */
	?>
	<span class="navigation" style="display:none" aria-hidden="true"></span>

</header>
