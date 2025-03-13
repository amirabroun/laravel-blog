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
            direction: 'rtl',
            firstDay: 6,
            initialView: 'dayGridMonth',
            locale: 'fa',
            selectable: true,
            selectHelper: true,
            editable: true,
            events: [{
                    title: 'نوروز',
                    start: '2025-03-21',
                    allDay: true,
                    color: 'green'
                },
                {
                    title: 'مناسبت تمام روز',
                    start: '2025-03-01'
                },
                {
                    title: 'مناسبت طولانی',
                    start: '2025-03-10',
                    end: '2025-03-10'
                },
                {
                    id: 999,
                    title: 'تکرار مناسبت',
                    start: '2025-03-09T16:00:00'
                },
                {
                    id: 999,
                    title: 'تکرار مناسبت',
                    start: '2025-03-16T16:00:00'
                },
                {
                    title: 'کنفرانس',
                    start: '2025-03-11',
                    end: '2025-03-13'
                },
                {
                    title: 'جلسه',
                    start: '2025-03-12T10:30:00',
                    end: '2025-03-12T12:30:00'
                },
                {
                    title: 'ناهار',
                    start: '2025-03-12T12:00:00'
                },
                {
                    title: 'جلسه',
                    start: '2025-03-12T14:30:00'
                },
                {
                    title: 'استراحت',
                    start: '2025-03-12T17:30:00'
                },
                {
                    title: 'شام',
                    start: '2025-03-12T20:00:00'
                },
                {
                    title: 'جشن تولد',
                    start: '2025-03-13T07:00:00'
                },
                {
                    title: 'کلیک برای باز کردن گوگل',
                    url: 'http://google.com/',
                    start: '2025-03-28'
                }
            ]
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
        max-height: 950px;
        margin: 0 auto;
    }
</style>