<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('fonts.css') }}">
</head>

<body>
    <div id='calendar'></div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev today next listMonth,dayGridMonth',
                right: ''
            },
            buttonText: {
                today: 'امروز',
                month: 'ماه',
                week: 'هفته',
                day: 'روز',
                list: 'لیست'
            },
            businessHours: {
                daysOfWeek: [0, 1, 2, 3, 6, 7],
            },
            timeZone: 'Asia/Tehran',
            direction: 'rtl',
            firstDay: 6,
            initialView: 'dayGridMonth',
            locale: 'fa',
            selectable: true,
            editable: true,
            events: <?php echo $tasks != [] ? $tasks : '[]'; ?>,
        });
        calendar.render();
    });
</script>

<style>
    body {
        padding: 0;
        font-family: "Iran", sans-serif;
        font-size: 14px;
    }

    #calendar {
        max-width: 950px;
        max-height: 920px;
        margin: 0 auto;
    }
</style>