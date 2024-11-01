<?php
/**
 * Frontend view
 *
 * @package TM_Youtube_Channel_Widget
 */
?>
		<div class="channel-name">
			<h5 class="txt-heading text-primary"> <?php echo $instance['channel_title']; ?></h5>
			<p> <?php echo $args['video_count']; ?></p>
			<span class="icon icon-lg icon-secondary fa fa-3x fa-youtube"></span>
		</div>

		<div class="button-cnt">
			<a href="<?php echo $instance['channel_url']; ?>" class="btn btn-primary">
				<?php echo __( 'Subscribe', 'youtube-subscribe' ); ?>
			</a>

			<div class="youtube-cnt">
				<p><?php echo $args['subscriber_count']; ?></p>
			</div>
		</div>
