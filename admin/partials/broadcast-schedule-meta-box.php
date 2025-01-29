<script type="text/javascript">
    let schedule = <?php echo ($schedule)?$schedule:'[]'; ?>; // Initialize the schedule
</script>
<div id="broadcast-schedule">
    <div v-for="(entry, index) in schedule" :key="index">
        <select v-model="entry.day" name="schedule_day[]" :required="true">
            <option v-for="day in daysOfWeek" :value="day">{{ day }}</option>
        </select>
        <input type="time" name="schedule_time[]" v-model="entry.time" :required="true">
        <button type="button" @click="removeSchedule(index)">Remove</button>
    </div>
    <button type="button" @click="addSchedule">Add Schedule</button>

    <!-- Hidden textarea that will hold the JSON data -->
    <textarea name="broadcast_schedule" hidden>{{ JSON.stringify(schedule) }}</textarea>
</div>