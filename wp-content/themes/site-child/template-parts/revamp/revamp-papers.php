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

$rv_root_class     = isset( $rv['root_class'] )     ? $rv['root_class']     : 'site-revamp--resource';
$rv_papers         = isset( $rv['papers'] )         ? $rv['papers']         : array();
$rv_resource_links = isset( $rv['resource_links'] ) && is_array( $rv['resource_links'] ) ? $rv['resource_links'] : array();
?>

<main id="primary" class="site-main site-revamp <?php echo esc_attr( $rv_root_class ); ?>">

	<?php get_template_part( 'template-parts/revamp/revamp-hero' ); ?>

	<?php
	/*
	 * A legacy <ol class="rv-papers-legacy-list"> used to sit here and is
	 * deliberately gone. Both of its entries duplicated papers already rendered
	 * as download cards by the rv-papers grid below, and it had no stylesheet at
	 * all, so it printed as a bare browser-default numbered list. Restyling it
	 * would still have left the same two papers on the page twice — the grid is
	 * now the single, authoritative rendering. No content was lost.
	 */
	?>
	<section class="rv-section rv-intro" aria-labelledby="rv-intro-title">
		<div class="rv-container">
			<div class="rv-intro__inner">
				<h2 id="rv-intro-title" class="rv-section-title">
					<?php echo esc_html( isset( $rv['intro_head'] ) ? $rv['intro_head'] : 'Research, reports and learning' ); ?>
				</h2>
				<?php if ( ! empty( $rv['intro_text'] ) ) : ?>
					<div class="rv-intro__text">
						<?php echo wp_kses_post( $rv['intro_text'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $rv_papers ) ) : ?>
		<section class="rv-section rv-papers" aria-label="Papers and publications">
			<div class="rv-container">
				<div class="rv-papers__grid">
					<?php
					foreach ( $rv_papers as $rv_paper ) :
						$rv_paper_type  = isset( $rv_paper['type'] ) ? $rv_paper['type'] : 'Research paper';
						$rv_paper_title = isset( $rv_paper['title'] ) ? $rv_paper['title'] : '';
						$rv_paper_url   = isset( $rv_paper['url'] ) ? $rv_paper['url'] : '';

						/* '' when the PDF cannot be resolved, so the size is omitted. */
						$rv_paper_size = site_child_upload_size( $rv_paper_url );

						/* Suggest a readable, portable filename instead of the raw upload
						   name. sanitize_file_name() alone keeps typographic punctuation
						   such as the em dash, so transliterate to ASCII first and
						   collapse whatever is left into single dashes. */
						$rv_paper_file = $rv_paper_title
							? ltrim( sanitize_file_name( preg_replace( '/[^A-Za-z0-9._-]+/', '-', remove_accents( $rv_paper_title ) ) ), '.-_' ) . '.pdf'
							: '';

						/* Visible text stays first so the accessible name still contains
						   "Download PDF" — WCAG 2.5.3 Label in Name. */
						$rv_paper_label = 'Download PDF: ' . $rv_paper_title;
						if ( $rv_paper_size ) {
							$rv_paper_label .= ' (' . $rv_paper_size . ')';
						}
						?>
						<article class="rv-card rv-paper-card">
							<div class="rv-paper-card__icon" aria-hidden="true">
								<i class="fa fa-file-pdf-o"></i>
							</div>
							<div class="rv-paper-card__body">
								<span class="rv-paper-card__badge">
									<?php echo esc_html( $rv_paper_type ); ?>
								</span>
								<h3 class="rv-paper-card__title">
									<?php echo esc_html( $rv_paper_title ); ?>
								</h3>
								<div class="rv-paper-card__foot">
									<span class="rv-paper-card__meta">
										<?php
										echo $rv_paper_size
											? esc_html( 'PDF · ' . $rv_paper_size )
											: esc_html( 'PDF' );
										?>
									</span>
									<a class="rv-btn rv-btn--primary rv-paper-card__download"
										href="<?php echo esc_url( $rv_paper_url ); ?>"
										<?php if ( $rv_paper_file ) : ?>
											download="<?php echo esc_attr( $rv_paper_file ); ?>"
										<?php endif; ?>
										target="_blank" rel="noopener"
										aria-label="<?php echo esc_attr( $rv_paper_label ); ?>">
										<i class="fa fa-download" aria-hidden="true"></i> Download PDF
									</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $rv_resource_links ) ) : ?>
		<section class="rv-section rv-resource-links" aria-labelledby="rv-resource-links-title">
			<div class="rv-container">
				<div class="rv-section-head">
					<p class="rv-eyebrow"><span class="rv-eyebrow__dot" aria-hidden="true"></span>Keep exploring</p>
					<h2 id="rv-resource-links-title" class="rv-section-title">More resources</h2>
				</div>
				<div class="rv-resource-links__grid">
					<?php foreach ( $rv_resource_links as $rv_rl ) : ?>
						<a class="rv-card rv-resource-link" href="<?php echo esc_url( $rv_rl['url'] ); ?>">
							<span class="rv-resource-link__icon" aria-hidden="true">
								<i class="fa <?php echo esc_attr( $rv_rl['icon'] ); ?>"></i>
							</span>
							<span class="rv-resource-link__text">
								<span class="rv-resource-link__title"><?php echo esc_html( $rv_rl['title'] ); ?></span>
								<span class="rv-resource-link__desc"><?php echo esc_html( $rv_rl['desc'] ); ?></span>
							</span>
							<span class="rv-resource-link__arrow" aria-hidden="true">
								<i class="fa fa-angle-right"></i>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>