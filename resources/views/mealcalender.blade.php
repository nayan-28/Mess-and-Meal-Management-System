<x-app-layout>
    @php
    $events = [];
    $groupedEvents = [];

    foreach ($mealdetails as $row) {
    $date = $row->date;

    if (!isset($groupedEvents[$date])) {
    $groupedEvents[$date] = [
    'date' => $date,
    'title' => 'মিল',
    'morning' => 0,
    'lunch' => 0,
    'dinner' => 0,
    ];
    }

    $groupedEvents[$date]['morning'] += $row->morning;
    $groupedEvents[$date]['lunch'] += $row->lunch;
    $groupedEvents[$date]['dinner'] += $row->dinner;
    }

    foreach ($groupedEvents as $event) {
    $content = "সকাল-{$event['morning']}<br>দুপুর-{$event['lunch']}<br>রাত-{$event['dinner']}<br>";
    $events[] = [
    'date' => $event['date'],
    'title' => $event['title'],
    'content' => $content,
    ];
    }

    $eventsJSON = json_encode($events);
    @endphp

    <div class="container mt-5">
        <div>
            <a href="{{route('dashboard') }}" class="btn btn-warning"><svg xmlns="http://www.w3.org/2000/svg" width="30"
                    height="30" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z" />
                </svg></a>
        </div>
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: <?php echo $eventsJSON; ?>,
        eventContent: function(info) {
            return {
                html: info.event.extendedProps.content
            };
        },
    });

    calendar.render();
});
</script>


<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.9/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js'></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>