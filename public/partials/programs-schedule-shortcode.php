<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://dev-appstation.pantheonsite.io/
 * @since      1.0.0
 *
 * @package    Radio_Program_Manager
 * @subpackage Radio_Program_Manager/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->



<div id="program-schedule">
    <div class="week-navigation">
        <button @click="changeWeek(-1)">← Previous Week</button>
        <span>{{ formattedWeek }}</span>
        <button @click="changeWeek(1)">Next Week →</button>
    </div>

    <div v-if="loading" class="loading">Loading schedule...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="schedule && Object.keys(schedule).length" class="calendar">
        <div v-for="(day, index) in weekDays" :key="day" class="day-column">
            <h3>{{ day }}</h3>
            <p>{{ weekDates[index].date }}</p>
            <ul>
                <li v-for="program in schedule[day] || []" :key="program.name" class="program-entry">
                    <img v-if="program.thumbnail" :src="program.thumbnail" alt="Program Thumbnail" />
                    <strong>{{ program.name }}</strong> - {{ program.time }}
                </li>
            </ul>
        </div>
    </div>

    <div v-else class="no-programs">No programs scheduled for this week.</div>
</div>