<?php
/**
 * Arrow button view
 */

namespace HulkPlugins\SmoothScrollToTopButton;

use enshrined\svgSanitize\Sanitizer;
use HulkPlugins\SmoothScrollToTopButton\utils\Helper;

$settings  = Helper::get_settings();
$svg_icons = Helper::read_svg_icons();
$icon      = esc_attr( $settings['icon'] );

// Create a new sanitizer instance
$sanitizer = new Sanitizer();
$sanitizer->minify( true );

?>

<button
	type="button"
	id="hulk-ssttb-arrow-button"
	class="hulk-ssttb-arrow-button"
	data-position="<?php echo esc_attr( $settings['position'] ); ?>"
	data-visible="false"
	data-border="<?php echo ( $settings['enableButtonBorder'] === true ) ? 'true' : 'false'; ?>"
	data-animation="<?php echo esc_attr( $settings['animation'] ); ?>"
	data-distance-top="<?php echo esc_attr( intval( $settings['distanceTop'] ) ); ?>"
	data-distance-left="<?php echo esc_attr( intval( $settings['distanceLeft'] ) ); ?>"
	data-distance-right="<?php echo esc_attr( intval( $settings['distanceRight'] ) ); ?>"
	data-distance-bottom="<?php echo esc_attr( intval( $settings['distanceBottom'] ) ); ?>"
	data-width="<?php echo esc_attr( intval( $settings['width'] ) ); ?>"
	data-height="<?php echo esc_attr( intval( $settings['height'] ) ); ?>"
	data-orientation="<?php echo esc_attr( $settings['orientation'] ); ?>"
	data-reverse="<?php echo ( $settings['reverse'] === true ) ? 'true' : 'false'; ?>"
	data-device-desktop="<?php echo ( $settings['devices']['desktop'] === true ) ? 'true' : 'false'; ?>"
	data-device-tablet="<?php echo ( $settings['devices']['tablet'] === true ) ? 'true' : 'false'; ?>"
	data-device-mobile="<?php echo ( $settings['devices']['mobile'] === true ) ? 'true' : 'false'; ?>"
>
	<!-- Arrow -->
	<span class="hulk-ssttb-arrow-bg"></span>
	<!-- SVG Icon -->
	<?php if ( isset( $svg_icons[ "$icon.svg" ] ) ) : ?>
		<span class="hulk-ssttb-arrow-svg">
			<?php
			// phpcs:ignore
			echo $sanitizer->sanitize( $svg_icons["$icon.svg"] ); // The "svg-sanitize" library sanitized it.
			?>
		</span>
	<?php endif; ?>
	<!-- Text -->
	<?php if ( ! empty( trim( $settings['text'] ) ) ) : ?>
		<span class="hulk-ssttb-arrow-text"><?php echo wp_kses_post( $settings['text'] ); ?></span>
	<?php endif; ?>
</button>
