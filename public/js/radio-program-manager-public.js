(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(function () {
		const app = Vue.createApp({
			data() {
				return {
					schedule: {},
					currentWeek: 0,
					loading: false,
					error: null,
					weekDays: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
					weekDates: []
				};
			},
			computed: {
				formattedWeek() {
					const start = new Date();
					start.setDate(start.getDate() + this.currentWeek * 7);
					const monday = new Date(start.setDate(start.getDate() - start.getDay() + 1));
					const sunday = new Date(monday);
					sunday.setDate(monday.getDate() + 6);

					return `${monday.toLocaleDateString()} - ${sunday.toLocaleDateString()}`;
				}
			},
			methods: {
				getWeekDays() {
					const start = new Date();
					start.setDate(start.getDate() + this.currentWeek * 7);
					const monday = new Date(start.setDate(start.getDate() - start.getDay() + 1));
					
					return Array.from({ length: 7 }, (_, i) => {
						const date = new Date(monday);
						date.setDate(monday.getDate() + i);
						return {
							name: date.toLocaleDateString('en-US', { weekday: 'short' }),
							date: date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
						};
					});
				},
				fetchSchedule() {
					this.loading = true;
					this.error = null;

					axios
						.post(programScheduleData.ajaxurl, new URLSearchParams({
							action: "get_weekly_schedule",
							week_offset: this.currentWeek,
							nonce: programScheduleData.nonce
						}))
						.then((response) => {
							if (response.data.success) {
								this.schedule = this.sortSchedule(response.data.data);
							} else {
								this.error = response.data.message || "Failed to load schedule.";
							}
						})
						.catch((error) => {
							console.error("Error fetching schedule:", error);
							this.error = "An error occurred while fetching the schedule.";
						})
						.finally(() => {
							this.loading = false;
						});
				},
				sortSchedule(schedule) {
					// Sort programs by time for each day
					Object.keys(schedule).forEach(day => {
						schedule[day] = schedule[day].sort((a, b) => {
							return a.time.localeCompare(b.time); // Sort by time
						});
					});
					return schedule;
				},
				changeWeek(offset) {
					this.currentWeek += offset;
					this.weekDates = this.getWeekDays()
					this.fetchSchedule();
				}
			},
			mounted() {
				this.weekDates = this.getWeekDays()
				this.fetchSchedule();
			}
		});

		app.mount("#program-schedule");

	});


})(jQuery);