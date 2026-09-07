<?php
/**
 * Front Page Template
 * 
 * @package SITE Child
 */

get_header();
?>

<main id="primary" class="site-main front-page-content">

	<?php
	// Keep the current hero carousel intact.
	get_template_part( 'template-parts/homepage/header-hero' );
	?>

	<section class="site-org-intro-section">
		<div class="container">
			<div class="row site-about-row">
				<div class="col-md-7 margin-bottom-30">
					<div class="intro-content-block">
						<span class="site-about-kicker">About SITE Enterprise Promotion</span>
						<h2>Building resilient livelihoods through enterprise-led development.</h2>
						<p class="site-about-lead">
							SITE is a Kenyan not-for-profit development organization working with communities, entrepreneurs, women, youth, and marginalized groups to turn skills into sustainable incomes and dignified work.
						</p>

						<div class="site-about-values">
							<div class="site-about-value">
								<span class="site-about-value-icon"><i class="fa fa-eye"></i></span>
								<div>
									<h3>Vision</h3>
									<p>Better quality of life for people and communities through economic dignity.</p>
								</div>
							</div>
							<div class="site-about-value">
								<span class="site-about-value-icon"><i class="fa fa-bullseye"></i></span>
								<div>
									<h3>Mission</h3>
									<p>To reduce poverty by strengthening livelihoods, competitive markets, and community-owned enterprises.</p>
								</div>
							</div>
						</div>

						<div class="site-about-actions">
							<a class="site-about-primary-link" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">More About Us</a>
							<a class="site-about-secondary-link" href="<?php echo esc_url( home_url( '/partnership/' ) ); ?>">Partner With Us</a>
						</div>
					</div>
				</div>
				<div class="col-md-5 margin-bottom-30">
					<div class="intro-badge-wrapper">
						<img src="<?php echo esc_url( content_url( 'uploads/2021/11/aniversery.png' ) ); ?>" alt="SITE transforming lives since 1996" class="img-responsive center-block">
						<div class="site-about-proof">
							<strong>Since 1996</strong>
							<span>Promoting inclusive enterprise, employment, and sustainable livelihoods across Kenya.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/homepage/focus-areas' );
	get_template_part( 'template-parts/homepage/impact-counter' );
	get_template_part( 'template-parts/homepage/featured-ecosystem' );
	get_template_part( 'template-parts/homepage/partner-carousel' );
	get_template_part( 'template-parts/homepage/location-map' );
	?>

</main>

<?php
get_footer();
