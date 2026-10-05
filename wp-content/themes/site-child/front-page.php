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
						<span class="site-about-kicker">About SITE</span>
						<h2>Transforming Lives Since 1996</h2>
						<p class="site-about-lead">
							SITE Enterprise Promotion (SITE) is a Kenyan not-for-profit development organization, established in 1996, whose goal is the promotion of employment opportunities and economic growth among communities.
						</p>

						<div class="site-about-values">
							<div class="site-about-value">
								<span class="site-about-value-icon"><i class="fa fa-eye"></i></span>
								<div>
									<h3>Vision</h3>
									<p>Better quality of life for the people of our world.</p>
								</div>
							</div>
							<div class="site-about-value">
								<span class="site-about-value-icon"><i class="fa fa-bullseye"></i></span>
								<div>
									<h3>Mission</h3>
									<p>Our mission is to reduce poverty by working with communities to address their needs through sustainable livelihood projects. We empower enterprising individuals to enhance productivity, foster competitive markets, and sustainable businesses that generate income and job opportunities.</p>
								</div>
							</div>
						</div>

						<div class="site-about-actions">
							<a class="site-about-primary-link" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">More About Us</a>
						</div>
					</div>
				</div>
				<div class="col-md-5 margin-bottom-30">
					<div class="intro-badge-wrapper">
						<img src="<?php echo esc_url( content_url( 'uploads/2021/11/aniversery.png' ) ); ?>" alt="SITE transforming lives since 1996" class="img-responsive center-block">
						<div class="site-about-proof">
							<strong>Since 1996</strong>
							<span>Promoting inclusive enterprise and employment opportunities, and sustainable livelihoods.</span>
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
	get_template_part( 'template-parts/homepage/testimonials' );
	get_template_part( 'template-parts/homepage/partner-carousel' );
	get_template_part( 'template-parts/homepage/location-map' );
	?>

</main>

<?php
get_footer();
