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


<style>
   #program-schedule {
    max-width: 800px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
}

.week-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 18px;
}

button {
    padding: 8px 12px;
    background: #0073aa;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #005a87;
}

.calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    text-align: center;
}

.day-column {
    background: #f8f8f8;
    padding: 10px;
    border-radius: 8px;
}

h3 {
    font-size: 16px;
    margin-bottom: 8px;
    background: #0073aa;
    color: #fff;
    padding: 5px;
    border-radius: 5px;
}

ul {
    list-style: none;
    padding: 0;
}

.program-entry {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 10px;
}

.program-entry img {
    width: 50px;
    height: 50px;
    border-radius: 5px;
    margin-bottom: 5px;
}

.no-programs {
    text-align: center;
    font-size: 18px;
    color: #555;
}

.loading {
    text-align: center;
    font-size: 18px;
    color: #0073aa;
}

.error {
    text-align: center;
    font-size: 18px;
    color: red;
}


</style>