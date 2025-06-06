<?php

/**
 * Template for displaying instructor of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/instructor.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.1
 */

defined('ABSPATH') || exit();
$course = learn_press_get_course();
if (!$course) {
	return;
}
/**
 * @var LP_User
 */
$instructor = $course->get_instructor();
?>

<div class="course-author">
	<?php do_action('learn-press/before-single-course-instructor'); ?>
	<div class="lp-course-author">
		<div class="course-author__pull-left">
			<?php echo wp_kses_post($instructor->get_profile_picture()); ?>
		</div>

		<div class="course-author__pull-right">
			<?php
			if (class_exists('BuddyPress') && class_exists('LearnPress')) {
				do_action('socialv_social_icon');
				
			} else {
				$socials = $instructor->get_profile_social($instructor->get_id());
				if ($socials) : ?>
					<div class="socialv-profile-left">
						<li class="learnpress_default_icone d-flex align-items-center gap-2">
							<?php echo wp_kses_post(implode('', $socials)); ?>
						</li>
					</div>
			<?php endif;
			} ?>
			<div class="author-title"><?php echo wp_kses_post($course->get_instructor_html()); ?></div>
			<div class="author-description margin-bottom">

				<?php
				/**
				 * LP Hook
				 *
				 * @since 4.0.0
				 */
				do_action('learn-press/begin-course-instructor-description', $instructor);

				echo wp_kses_post($instructor->get_description());

				/**
				 * LP Hook
				 *
				 * @since 4.0.0
				 */
				do_action('learn-press/end-course-instructor-description', $instructor);

				?>
			</div>

			<?php
			/**
			 * LP Hook
			 *
			 * @since 4.0.0
			 */
			do_action('learn-press/after-course-instructor-description', $instructor);
			?>

			<?php

			/**
			 * LP Hook
			 *
			 * @since 4.0.0
			 */
			do_action('learn-press/after-course-instructor-socials', $instructor);

			?>
		</div>
	</div>
	<?php do_action('learn-press/after-single-course-instructor'); ?>

</div>