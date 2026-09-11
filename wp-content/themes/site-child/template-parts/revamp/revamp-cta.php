<?php
/**
 * Shared closing CTA for the modernised programme + resource pages.
 *
 * Expects the global $rv config: cta_title, cta_text, cta_url, cta_label.
 *
 * Scope note: shells publish their config on $GLOBALS['site_child_revamp']
 * before calling get_template_part(), because template parts load inside a
 * function scope (see revamp-programme.php).
 *
 * @package SITE Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rv = isset( $GLOBALS['site_child_revamp'] ) && is_array( $GLOBALS['site_child_revamp'] )
	? $GLOBALS['site_child_revamp']
	: array();

$rv_cta_title = isset( $rv['cta_title'] ) ? $rv['cta_title'] : 'Have a question about our work?';
$rv_cta_text  = isset( $rv['cta_text'] )  ? $rv['cta_text']  : 'Whether you are interested in our programmes, research, or potential collaboration, we would love to hear from you.';
$rv_cta_url   = isset( $rv['cta_url'] )   ? $rv['cta_url']   : '/contact-us/';
$rv_cta_label = isset( $rv['cta_label'] ) ? $rv['cta_label'] : 'Start a conversation';
?>

<section class="rv-cta" aria-labelledby="rv-cta-title">
	<div class="rv-container">
		<div class="rv-cta__inner">
			<div class="rv-cta__text">
				<p class="rv-eyebrow rv-eyebrow--on-accent">
					<span class="rv-eyebrow__dot" aria-hidden="true"></span>
					Get in touch
				</p>
				<h2 id="rv-cta-title" class="rv-cta__title"><?php echo esc_html( $rv_cta_title ); ?></h2>
				<p class="rv-cta__lead"><?php echo esc_html( $rv_cta_text ); ?></p>
			</div>
			<a class="rv-btn rv-btn--accent rv-cta__button" href="<?php echo esc_url( $rv_cta_url ); ?>">
				<?php echo esc_html( $rv_cta_label ); ?>
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</a>
		</div>
	</div>
</section>