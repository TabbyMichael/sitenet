<?php
/**
 * Homepage Impact Counter Section
 * 
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$metrics = array(
	array(
		'number' => '35,000+',
		'label'  => 'Men',
		'icon'   => 'fa-users',
	),
	array(
		'number' => '100,000+',
		'label'  => 'Women And Girls',
		'icon'   => 'fa-female',
	),
	array(
		'number' => '51,000+',
		'label'  => 'Persons With Disabilities (PWDs)',
		'icon'   => 'fa-briefcase',
	),
	array(
		'number' => '460+',
		'label'  => 'Refugees',
		'icon'   => 'fa-group',
	),
	array(
		'number' => '20,000+',
		'label'  => 'Youth',
		'icon'   => 'fa-child',
	),
);
?>

<section class="site-impact-counter-section">
	<div class="container">
		<div class="site-section-title site-modern-section-title">
			<span class="site-section-kicker">Our Reach</span>
			<h2>Our Impact In Numbers</h2>
		</div>

		<div class="impact-strip">
			<?php foreach ( $metrics as $m ) : ?>
				<div class="impact-box">
					<div class="impact-icon"><i class="fa <?php echo esc_attr( $m['icon'] ); ?>"></i></div>
					<div class="impact-number"><?php echo esc_html( $m['number'] ); ?></div>
					<div class="impact-label"><?php echo esc_html( $m['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
