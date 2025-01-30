<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dev-appstation.pantheonsite.io/
 * @since      1.0.0
 *
 * @package    Radio_Program_Manager
 * @subpackage Radio_Program_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Radio_Program_Manager
 * @subpackage Radio_Program_Manager/admin
 * @author     Hariprasad Vijayan <hariprasad148@gmail.com>
 */
class Radio_Program_Manager_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/radio-program-manager-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
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


		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/radio-program-manager-admin.js', array('jquery', 'vue-js'), $this->version, false);
	}

	public function register_post_types()
	{
		$labels = array(
			'name'                  => _x('Programs', 'Post Type General Name', 'radio-program-manager'),
			'singular_name'         => _x('Program', 'Post Type Singular Name', 'radio-program-manager'),
			'menu_name'             => __('Programs', 'radio-program-manager'),
			'name_admin_bar'        => __('Program', 'radio-program-manager'),
			'archives'              => __('Program Archives', 'radio-program-manager'),
			'attributes'            => __('Program Attributes', 'radio-program-manager'),
			'parent_item_colon'     => __('Parent Program:', 'radio-program-manager'),
			'all_items'             => __('All Programs', 'radio-program-manager'),
			'add_new_item'          => __('Add New Program', 'radio-program-manager'),
			'add_new'               => __('Add New', 'radio-program-manager'),
			'new_item'              => __('New Program', 'radio-program-manager'),
			'edit_item'             => __('Edit Program', 'radio-program-manager'),
			'update_item'           => __('Update Program', 'radio-program-manager'),
			'view_item'             => __('View Program', 'radio-program-manager'),
			'view_items'            => __('View Program', 'radio-program-manager'),
			'search_items'          => __('Search Program', 'radio-program-manager'),
			'not_found'             => __('Not found', 'radio-program-manager'),
			'not_found_in_trash'    => __('Not found in Trash', 'radio-program-manager'),
			'featured_image'        => __('Featured Image', 'radio-program-manager'),
			'set_featured_image'    => __('Set featured image', 'radio-program-manager'),
			'remove_featured_image' => __('Remove featured image', 'radio-program-manager'),
			'use_featured_image'    => __('Use as featured image', 'radio-program-manager'),
			'insert_into_item'      => __('Insert into program', 'radio-program-manager'),
			'uploaded_to_this_item' => __('Uploaded to this program', 'radio-program-manager'),
			'items_list'            => __('Programs list', 'radio-program-manager'),
			'items_list_navigation' => __('Programs list navigation', 'radio-program-manager'),
			'filter_items_list'     => __('Filter items program', 'radio-program-manager'),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __('Radio program entries.', 'radio-program-manager'),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => 'program'),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array('title', 'editor', 'thumbnail'),
			'menu_icon'          => 'dashicons-calendar-alt'
		);

		register_post_type('programs', $args);
	}

	public function add_meta_boxes()
	{
		// Program dates meta box
		add_meta_box(
			'program_dates_meta_box',
			__('Program Dates', 'radio-program-manager'),
			array($this, 'render_dates_meta_box'),
			'programs',
			'normal',
			'high'
		);
		// Broadcast schedule meta box
		add_meta_box(
			'broadcast_schedule_meta_box',
			__('Broadcast Schedule', 'radio-program-manager'),
			array($this, 'render_broadcast_schedule_meta_box'),
			'programs',
			'normal',
			'high'
		);
	}

	public function render_dates_meta_box($post)
	{
		// Add nonce for security
		wp_nonce_field('program_dates_meta_box', 'program_dates_nonce');

		// Get saved dates
		$start_date = get_post_meta($post->ID, '_program_start_date', true);
		$end_date = get_post_meta($post->ID, '_program_end_date', true);

		// Render meta box HTML
		include plugin_dir_path(__FILE__) . 'partials/program-dates-meta-box.php';
	}

	public function render_broadcast_schedule_meta_box($post)
	{
		// Add nonce for security
		wp_nonce_field('broadcast_schedule_meta_box', 'broadcast_schedule_nonce');

		// Get saved schedule
		$schedule = get_post_meta($post->ID, '_broadcast_schedule', true);

		// Render meta box HTML
		include plugin_dir_path(__FILE__) . 'partials/broadcast-schedule-meta-box.php';
	}



	public function save_meta_boxes($post_id, $post)
	{
		if (!$_POST) {
			return;
		}
		// Verify that the nonce is valid
		if (isset($_POST['program_dates_nonce']) && !wp_verify_nonce($_POST['program_dates_nonce'], 'program_dates_meta_box')) {
			return;
		}

		if (isset($_POST['broadcast_schedule_nonce']) && !wp_verify_nonce($_POST['broadcast_schedule_nonce'], 'broadcast_schedule_meta_box')) {
			return;
		}

		// If this is an autosave, we don't want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// Save program dates
		if (isset($_POST['program_start_date'])) {
			update_post_meta($post_id, '_program_start_date', sanitize_text_field($_POST['program_start_date']));
		}

		if (isset($_POST['program_end_date'])) {
			update_post_meta($post_id, '_program_end_date', sanitize_text_field($_POST['program_end_date']));
		}

		// Save broadcast schedule
		if (isset($_POST['broadcast_schedule'])) {
			update_post_meta($post_id, '_broadcast_schedule', sanitize_text_field($_POST['broadcast_schedule']));
		}
	}

	public function add_import_program_page()
	{
		// Add Import program menu
		add_submenu_page(
			'edit.php?post_type=programs',
			__('Import Programs', 'radio-schedule'),
			__('Import Programs', 'radio-schedule'),
			'manage_options',
			'program-import',
			array($this, 'render_import_program_page')
		);
	}

	public function render_import_program_page()
	{
		// Render import program html
		include plugin_dir_path(__FILE__) . 'partials/program-import-page.php';
	}
	public function handle_import_programs()
	{

		// Check permissions
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}

		// Verify nonce
		if (!isset($_POST['import_programs']) || !check_admin_referer('import_program_nonce', 'import_programs')) {
			return;
		}

		$file = $_FILES['csv_file']['tmp_name'];


		$csvData = file_get_contents($file);

		// Split the CSV content into rows
		$rows = explode("\n", $csvData);
		$header = str_getcsv(array_shift($rows));


		$status = [];

		$header = [];
		$row_number = 0;

		if (($handle = fopen($file, 'r')) !== false) {
			while (($row = fgetcsv($handle, 1000, ",")) !== false) {
				$row_number++;

				if ($row_number == 1) {
					$header = $row; // First row as header
					continue;
				}

				$program_details = array_combine($header, $row);
				

				$broadcastSchedule = $this->process_schedule_data($program_details["Broadcast Schedule"]);

				// Decode the broadcast schedule JSON string to handle it as an array
				$schedule = json_decode($broadcastSchedule, true);


				if (empty($program_details["Program Name"])) {
					$status[] = ['type' => 'warning', 'message' =>  __('Invalid Program Name in row: ' . $i, 'radio-program-manager')];
					continue;
				}
				if (empty($program_details["Program Description"])) {
					$status[] = ['type' => 'warning', 'message' =>  __('Invalid Program Description for : ' . $program_details["Program Name"], 'radio-program-manager')];
					continue;
				}
				// Check date format  (Format: YYYY-MM-DD, e.g., 2025-01-01)
				if (empty($program_details["Program Start Date"]) || $program_details["Program Start Date"] !== date('Y-m-d', strtotime($program_details["Program Start Date"]))) {

					$status[] = ['type' => 'warning', 'message' =>  __('Invalid Program Start Date for : ' . $program_details["Program Name"], 'radio-program-manager')];
					continue;
				}
				if (empty($program_details["Program End Date"]) || $program_details["Program End Date"] !== date('Y-m-d', strtotime($program_details["Program End Date"]))) {

					$status[] = ['type' => 'warning', 'message' =>  __('Invalid Program End Date for : ' . $program_details["Program Name"], 'radio-program-manager')];
					continue;
				}

				if (json_last_error() !== JSON_ERROR_NONE) {
					$status[] = ['type' => 'warning', 'message' =>  __('Invalid Broadcast Schedule for : ' . $program_details["Program Name"], 'radio-program-manager')];
					continue; // Skip this row if JSON is invalid
				}

				// Create program post
				$post_data = array(
					'post_title'    => wp_strip_all_tags($program_details['Program Name']),
					'post_content'  => wp_kses_post($program_details['Program Description'] ?? ''),
					'post_status'   => 'publish',
					'post_type'     => 'programs'
				);

				// Insert the post
				$post_id = wp_insert_post($post_data, true);

				// Save program dates
				update_post_meta($post_id, '_program_start_date', sanitize_text_field($program_details['Program Start Date']));
				update_post_meta($post_id, '_program_end_date', sanitize_text_field($program_details['Program End Date']));

				// Handle thumbnail if URL is provided
				if (!empty($program_details['Program Thumbnail'])) {
					$this->set_featured_image_from_url($post_id, $program_details['Program Thumbnail']);
				}

				if (isset($broadcastSchedule)) {
					update_post_meta($post_id, '_broadcast_schedule', sanitize_text_field($broadcastSchedule));
				}
			}
		}
		$status[] = ['type' => 'success', 'message' => __('Programs imported successfully.', 'radio-program-manager')];
		set_transient('program_import_status', $status, 30);
		// Redirect back with status
		wp_redirect(add_query_arg(
			array(
				'page' => 'program-import',
			),
			admin_url('edit.php?post_type=programs')
		));
	}
	/*
	 * Helper function to process schedule data
	 * */
	private function process_schedule_data($row)
	{
		$scheduleArray = json_decode($row, true);
		$newSchedule = [];
		// Check if JSON decoding was successful
		if (json_last_error() === JSON_ERROR_NONE) {


			// Loop through the original schedule and restructure the data
			foreach ($scheduleArray as $day => $time) {
				$newSchedule[] = [
					"day"  => $day,
					"time" => $time
				];
			}
			// Encode the new format back to JSON
			return json_encode($newSchedule);
		}
	}
	/**
	 * Helper function to set featured image from URL
	 */
	private function set_featured_image_from_url($post_id, $image_url)
	{
		// Get WordPress upload directory
		$upload_dir = wp_upload_dir();

		// Get image data
		$image_data = file_get_contents($image_url);
		if ($image_data === false) {
			return false; // Image download failed
		}

		$filename = wp_unique_filename($upload_dir['path'], basename($image_url));
		$file_path = trailingslashit($upload_dir['path']) . $filename;

		if (file_put_contents($file_path, $image_data) === false) {
			return false; // Failed to save file
		}

		$wp_filetype = wp_check_filetype($filename, null);
		if (!$wp_filetype['type']) {
			return false; // Invalid file type
		}

		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name($filename),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert attachment into the media library
		$attach_id = wp_insert_attachment($attachment, $file_path, $post_id);
		if (is_wp_error($attach_id) || !$attach_id) {
			return false; // Attachment insertion failed
		}

		// Include image.php for metadata functions
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Generate attachment metadata
		$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
		wp_update_attachment_metadata($attach_id, $attach_data);

		// Set the image as the featured image (post thumbnail)
		if (!set_post_thumbnail($post_id, $attach_id)) {
			return false; // Failed to set featured image
		}

		return true;
	}
}
