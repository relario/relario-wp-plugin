<?php
// This template can be called with the following code; \Relario\get_template( 'buttons/donate.php' );
//
// Override this template by creating a new file in your active theme 'wp-content/your-theme/relario-pay/buttons/donate.php' or 'wp-content/your-theme/buttons/donate.php'

use function Relario_PAY\Relario;

/**
 * @var $type string Type of button (fixed/dynamic).
 * @var $text string Donate button text.
 * @var $sms_count int Default sms count
 * @var $size string Button size.
 */

?><form class="relario-support-wrap" style="display: flex; align-items:flex-start; flex-direction: column;" data-smsCount="<?php echo absint( $sms_count ); ?>" <?php echo sprintf( '%s', $attributes_html ); ?>><?php

	if ( $type == 'dynamic' ) :
		?><select class="relario-support-quantity" style="display: none;">
			<option value="0" disabled selected><?php _e( 'Select number of sms', 'relario-pay' ); ?></option><?php

			for ( $i = $min; $i <= $max; $i++ ) :
				?><option value="<?php echo absint( $i ); ?>"><?php echo absint( $i ) . ' SMS';  ?></option><?php
			endfor;
		?></select><?php
	endif;


	?><span class="relario-button-wrap" style="display: flex; flex-direction: column; align-items: flex-start;">
		<a href="#" class="relario-support relario-<?php echo esc_attr( $type ); ?>"><?php
			\Relario_PAY\get_template( 'buttons/button.php', array(
				'text' => $text,
				'size' => $size,
			) );
		?></a>
		<a href="https://relario.com" target="_blank" style="justify-self: center; font-size: 0.7em; color: #4b3867;"><?php _e( 'Powered by relario PAY. Support is given by sending SMS. Standard international rates apply.', 'relario-pay' ); ?></a>
	</span>

</form>
