<?php
/**
 * Shared hero for the modernised programme + resource pages.
 *
 * Expects the global $rv config (set by the page-{slug}.php shell):
 *   title, kicker, lead, hero_image, hero_image_alt, actions[]
 *
 * Locked boundaries: header + footer are untouched. This part outputs
 * ONLY the hero inside <main class="site-revamp ...">.
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

$rv_title       = isset( $rv['title'] )        ? $rv['title']        : get_the_title();
$rv_kicker      = isset( $rv['kicker'] )       ? $rv['kicker']       : '';
$rv_lead        = isset( $rv['lead'] )         ? $rv['lead']         : '';
$rv_image       = isset( $rv['hero_image'] )   ? $rv['hero_image']   : '';
$rv_image_alt   = isset( $rv['hero_image_alt'] ) ? $rv['hero_image_alt'] : '';
$rv_actions     = isset( $rv['actions'] )      ? $rv['actions']      : array();
?>

<section class="rv-hero" aria-labelledby="rv-page-title">
	<div class="rv-container">
		<div class="rv-hero__grid">
			<div class="rv-hero__copy">
				<?php if ( $rv_kicker ) : ?>
					<p class="rv-eyebrow">
						<span class="rv-eyebrow__dot" aria-hidden="true"></span>
						<?php echo esc_html( $rv_kicker ); ?>
					</p>
				<?php endif; ?>

				<h1 id="rv-page-title" class="rv-hero__title"><?php echo esc_html( $rv_title ); ?></h1>

				<?php if ( $rv_lead ) : ?>
					<p class="rv-hero__lead"><?php echo esc_html( $rv_lead ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $rv_actions ) ) : ?>
					<div class="rv-hero__actions">
						<?php
						foreach ( $rv_actions as $rv_action ) :
							$rv_variant = isset( $rv_action['variant'] ) ? $rv_action['variant'] : 'secondary';
							?>
							<a class="rv-btn rv-btn--<?php echo esc_attr( $rv_variant ); ?>"
								href="<?php echo esc_url( $rv_action['url'] ); ?>">
								<?php echo esc_html( $rv_action['label'] ); ?>
								<i class="fa fa-angle-right" aria-hidden="true"></i>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $rv_image ) : ?>
				<div class="rv-hero__media">
					<div class="rv-hero__frame">
						<img class="rv-hero__img"
							src="<?php echo esc_url( $rv_image ); ?>"
							alt="<?php echo esc_attr( $rv_image_alt ); ?>"
							width="1280" height="853" decoding="async">
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>