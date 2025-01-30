=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: https://dev-appstation.pantheonsite.io//
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


[Demo](https://dev-appstation.pantheonsite.io/weekly-programs/)
[Sample CSV](https://raw.githubusercontent.com/phpsmashcode/Radio-Program-Manager/refs/heads/main/sample-csv.csv)


## Objective
Develop a custom WordPress plugin to manage and display radio program’s schedule, including an import feature for program details via CSV. The schedule should allow unique broadcast times for each day of the week. 
## Task Description
You are tasked with creating a custom WordPress plugin. The plugin will allow admins to manage radio programs, set flexible broadcast schedules, import program details from a CSV file, and display the program schedule in a week-view format on the front end with AJAX-based navigation. 

## Requirements 

### Admin Functionality
    1. Custom Post Type 
        a. Create a custom post type named Program with the following attributes: 
            i. Program Name (Text, required) 
            ii. Program Description (Text Area) 
            iii. Program Start Date (Date Picker, required) 
            iv. Program End Date (Date Picker, required) 
            v. Program Thumbnail (Media Upload) 
    2. Broadcast Schedule 
        a. Add a meta box to manage the broadcast schedule for each program: 
            i. Days of the week with corresponding broadcast times (e.g., Monday: 08:00, Tuesday: 09:00, etc.). 
            ii. Ensure multiple days with different times can be added for a single program. 
    3. CSV Import 
        a. Add a feature to upload and import program details via a CSV file. 
        b. The CSV file should include the following columns: 
            i. Program Name
            ii. Program Description 
            iii. Program Start Date (Format: YYYY-MM-DD, e.g., 2025-01-01) 
            iv. Program End Date (Format: YYYY-MM-DD, e.g., 2025-01-31) 
            v. Program Thumbnail (URL to the image) 
            vi. Broadcast Schedule (A JSON-like string defining days and times, e.g., {"Mon": "08:00", "Tue": "09:00"}) 
        c. Validate the data during import: 
            i. Ensure required fields are present. 
            ii. Verify the "Broadcast Schedule" field is valid JSON and matches the correct format. 
            iii. Show meaningful error messages for invalid rows. 
        d. Ensure imported programs are created or updated in the database. 

### Frontend Functionality
    1. Shortcode [program_schedule] 
        a. Display the program schedule in a week-view calendar format. 
        b. Each day should list programs sorted by their broadcast time, displaying: 
            i. Program Name 
            ii. Program Thumbnail 
            iii. Broadcast Time 
        c. Only include programs whose broadcast dates (start and end) include the current week. 
    2. Week Navigation 
        a. Add buttons for Previous Week and Next Week. 
        b. Fetch and display data dynamically via AJAX without reloading the page. 
        Additional Requirements 
            1. Sanitization and Validation 
                a. Ensure all data is sanitized before saving it to the database. 
                b. Validate CSV uploads for correct formatting and required fields. 
            2. Performance 
                a. Optimize database queries for efficient data retrieval.

### CSV File Example
    Columns 
        • Program Name, Program Description, Program Start Date, Program End Date, Program Thumbnail, Broadcast Schedule 
    Sample Data 
        Program Name,Program Description,Program Start Date,Program End Date,Program Thumbnail,Broadcast Schedule
        Morning News,Daily news updates,2025-01-01,2025-01-31,https://example.com/image1.jpg,"{""Mon"": ""08:00"", ""Tue"": ""09:00"", ""Wed"": ""10:00""}"