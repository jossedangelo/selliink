<?php
/**
 * Template for displaying complete button in content lesson.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-lesson/button-complete.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.3
 */

defined( 'ABSPATH' ) || exit();

if ( ! isset( $item ) || ! isset( $user ) || ! isset( $course ) ) {
	return;
}

if ( $item->is_preview() && ! $user->has_enrolled_course( $course->get_id() ) ) {
	return;
}

$message_confirm_complete_item = sprintf( '%s "%s"?', __( 'Do you want to complete lesson', 'socialv' ), $item->get_title() );
$completed                     = $user->has_completed_item( $item->get_id(), $course->get_id() );

if ( $completed ) :
	?>
	<div>
		<?php
		echo sprintf(
			'%s %s',
			esc_html__( 'You have completed this lesson at ', 'socialv' ),
			$user->get_item_data( $item->get_id(), $course->get_id(), 'end_time' )
		)
		?>
	</div>
	<button class="lp-button completed" disabled>
		<i class="icon-check"></i><?php esc_html_e( 'Completed', 'socialv' ); ?>
	</button>
<?php else : ?>

	<form method="post" name="learn-press-form-complete-lesson"
		action="<?php echo add_query_arg( [ 'complete-lesson' => '' ], LP_Settings::url_handle_lp_ajax() ); ?>"
		class="learn-press-form form-button <?php echo esc_attr( $completed ) ? 'completed' : ''; ?>"
		data-title="<?php echo esc_attr( __( 'Complete lesson', 'learnpress' ) ); ?>"
		data-confirm="<?php echo esc_attr( $message_confirm_complete_item ); ?>">

		<?php do_action( 'learn-press/lesson/before-complete-button' ); ?>

		<input type="hidden" name="lesson_id" value="<?php echo esc_attr( $item->get_id() ); ?>"/>
		<input type="hidden" name="course_id" value="<?php echo esc_attr( $course->get_id() ); ?>"/>
		<input type="hidden" name="nonce"
			value="<?php echo wp_create_nonce( 'wp_rest' ); ?>"/>
		<input type="hidden" name="lp-load-ajax" value="user_complete_lesson"/>
		<button class="lp-button button-complete-lesson lp-btn-complete-item"
			type="submit">
			<?php echo esc_html__( 'Complete', 'learnpress' ); ?>
		</button>
		<?php do_action( 'learn-press/lesson/after-complete-button' ); ?>
	</form>
<?php endif; ?>
