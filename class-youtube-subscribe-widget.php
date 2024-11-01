<?php
/**
 * Description: Youtube Subscribe Widget
 * Author: Osadchyi Serhii
 * Author URI: https://github.com/RDSergij
 *
 * @package Monster_Youtube_Subscribe_Widget
 *
 * @since 0.1
 */
if ( ! class_exists( 'Youtube_Subscribe_Widget' ) ) {

	/**
	 * Adds Youtube_Subscribe_Widget widget.
	 */
	class Youtube_Subscribe_Widget extends Cherry_Abstract_Widget {
		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			//$this->widget_cssclass    = 'youtube-subscribe widget-about-author';
			$this->widget_description =  __( 'YouTube subscribe Widget', 'youtube-subscribe' );
			$this->widget_id          = 'youtube-subscribe_widget';
			$this->widget_name        = __( 'YouTube Subscribe', 'youtube-subscribe' );
			$this->settings           = array(
				'title'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Title', 'youtube-subscribe' ),
					),
				'app_key'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'API key', 'youtube-subscribe' ),
					),
				'channel_title'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Channel Title', 'youtube-subscribe' ),
					),
				'channel_url'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Channel URL', 'youtube-subscribe' ),
					),
				'novideo'  => array(
					'type'  => 'text',
					'value' => esc_html__( 'novideo', 'youtube-subscribe' ),
					'label' => esc_html__( ' Text to display when there are no video ', 'youtube-subscribe' ),
					),
				'one_video'  => array(
					'type'  => 'text',
					'value' => esc_html__( 'video', 'youtube-subscribe' ),
					'label' => esc_html__( 'Text to display when there is one video', 'youtube-subscribe' ),
					),
				'many_videos'  => array(
					'type'  => 'text',
					'value' => esc_html__( 'videos', 'youtube-subscribe' ),
					'label' => esc_html__( 'Text to display when there is more than one video', 'youtube-subscribe' ),
					),
			);
			parent::__construct();
		}

		/**
		 * Get data about channel from YoutubeAPI
		 *
		 * @return array
		 */
		private function get_channel_data( $channel_url, $app_key = '' ) {

			$url_parts = explode( '/', esc_url( $channel_url ) );

			$url = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id=' . end( $url_parts ) . '&key=' . $app_key;

			$result = Youtube_Subscribe_Helper::get_contents( $url );

			return $result ? json_decode( $result, true ) : false;
		}

		/**
		 * Frontend view
		 *
		 * @param type $args array.
		 * @param type $instance array.
		 */
		public function widget( $args, $instance ) {
			if ( empty( $instance['app_key'] ) ) {
				return;
			}

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$channel_data = $this->get_channel_data( Youtube_Subscribe_Helper::array_get( $instance, 'channel_url' ), Youtube_Subscribe_Helper::array_get( $instance, 'app_key', '' ) );

			if ( empty( $channel_data['items'][0]['statistics']['subscriberCount'] ) ) {
				$args['subscriber_count'] = 0;
			} else {
				$args['subscriber_count'] = Youtube_Subscribe_Helper::array_get( $channel_data['items'][0]['statistics'], 'subscriberCount', 0 );
			}

			if ( empty( $channel_data['items'][0]['statistics']['videoCount'] ) ) {
				$args['video_count'] = Youtube_Subscribe_Helper::array_get( $instance, 'novideo' );
			} else {
				$args['video_count'] = Youtube_Subscribe_Helper::array_get( $channel_data['items'][0]['statistics'], 'videoCount', 0 );
				if ( 1 == $args['video_count'] ) {
					$args['video_count'] = $args['video_count'] . ' ' . Youtube_Subscribe_Helper::array_get( $instance, 'one_video' );
				} else {
					$args['video_count'] = $args['video_count'] . ' ' . Youtube_Subscribe_Helper::array_get( $instance, 'many_videos' );
				}
			}

			$file = Youtube_Subscribe_Helper::get_view_file( );
			if ( $file ) {
				ob_start();

				$this->setup_widget_data( $args, $instance );
				$this->widget_start( $args, $instance );

				include $file;

				$this->widget_end( $args );
				$this->reset_widget_data();

				echo $this->cache_widget( $args, ob_get_clean() );

			} else {
				echo 'Template not found';
			}
		}
	}
}
