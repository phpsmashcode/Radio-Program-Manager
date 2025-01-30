<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dev-appstation.pantheonsite.io/
 * @since      1.0.0
 *
 * @package    Radio_Program_Manager
 * @subpackage Radio_Program_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Radio_Program_Manager
 * @subpackage Radio_Program_Manager/public
 * @author     Hariprasad Vijayan <hariprasad148@gmail.com>
 */
class Radio_Program_Manager_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Radio_Program_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Radio_Program_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/radio-program-manager-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Radio_Program_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Radio_Program_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Enqueue Vue.js
		wp_enqueue_script('vue-js', 'https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js', [], '3.0.0', true);

		// Enqueue Axios (if needed)
		wp_enqueue_script('axios', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', [], null, true);


		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/radio-program-manager-public.js', array('jquery', 'vue-js', 'axios'), $this->version, false);

		// Localize the script to pass dynamic data (like AJAX URL)
		wp_localize_script(
			$this->plugin_name,
			'programScheduleData',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('program_schedule_nonce')
			)
		);
	}

	public function register_shortcodes()
	{

		// Register shortcodes
		add_shortcode('program_schedule', array($this, 'render_program_schedule_shortcode'));
	}

	public function render_program_schedule_shortcode()
	{
		ob_start();
		require_once plugin_dir_path(__FILE__) . 'partials/programs-schedule-shortcode.php';
		return ob_get_clean();
	}

	public function ajax_get_weekly_schedule()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'program_schedule_nonce')) {
			wp_send_json_error(['message' => 'Nonce verification failed'], 400);
			return;
		}

		$week_offset = isset($_POST['week_offset']) ? intval($_POST['week_offset']) : 0;

		if ($week_offset >= 0) {
			// Calculate start and end dates for the requested week
			$start_date = date('Y-m-d', strtotime("monday this week +{$week_offset} week"));
			$end_date = date('Y-m-d', strtotime("sunday this week +{$week_offset} week"));
		} else {
			$start_date = date('Y-m-d', strtotime("monday this week {$week_offset} week"));
			$end_date = date('Y-m-d', strtotime("sunday this week {$week_offset} week"));
		}

		$args = [
			'post_type'      => 'programs',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => '_program_start_date',
					'value'   => $end_date,
					'compare' => '<=',
					'type'    => 'DATE'
				],
				[
					'key'     => '_program_end_date',
					'value'   => $start_date,
					'compare' => '>=',
					'type'    => 'DATE'
				]
			]
		];

		$query = new WP_Query($args);
		$schedule = [];

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();

				$program_start_date = get_post_meta(get_the_ID(), '_program_start_date', true);
				$program_end_date   = get_post_meta(get_the_ID(), '_program_end_date', true);
				$broadcast_schedule = get_post_meta(get_the_ID(), '_broadcast_schedule', true);
				$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');


				$start_ts = strtotime($start_date);
				$end_ts = strtotime($end_date);

				$program_start_ts = strtotime($program_start_date);
				$program_end_ts = strtotime($program_end_date);


				if (!empty($broadcast_schedule)) {
					$broadcast_schedule = json_decode($broadcast_schedule, true);

					for ($i = 0; $i < 7; $i++) {
						$current_day_ts = strtotime("+{$i} days", $start_ts);
						$current_day_str = date('Y-m-d', $current_day_ts);
						$day_name = date('D', $current_day_ts); // Mon, Tue, Wed, etc.

						foreach ($broadcast_schedule as $entry) {
							$day = $entry['day'];
							$time = $entry['time'];

							if ($day_name == $day && $current_day_ts >= $program_start_ts && $current_day_ts <= $program_end_ts) {
								$schedule[$day][] = [
									'name' => get_the_title(),
									'time' => $time,
									'day' => $day,
									'thumbnail' => $thumbnail ?: '',
								];
							}
						}
					}
				}
			}
		}

		wp_reset_postdata();
		wp_send_json_success($schedule);
	}
}
