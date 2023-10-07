<x-border-app-layout>
    @php
    $totalMeals = 0;
    $events = [];
    $groupedEvents = [];

    foreach ($bordermeals as $row) {
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

    $totalMeals += ($row->morning + $row->lunch + $row->dinner);
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
            <a href="{{route('border.dashboard') }}" class="btn btn-warning"><svg xmlns="http://www.w3.org/2000/svg"
                    width="30" height="30" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z" />
                </svg></a>
        </div>
        <div style="text-align: center;">
            <p><a href="{{ route('border.allmonthmeal')}}" class="btn btn-success">অন্যমাসের মিল দেখুন</a></p>
            <b>আপনার মোট মিল :- {{$totalMeals}}</b>
        </div>
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">মিল যোগ করুন</h4>
                    <button type="button" class="close" id="closeModalButton">&times;</button>
                </div>
                <form action="{{ route('border.addmeals') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="morning">সকাল</label>
                            <input type="number" name="morning" placeholder="দশমিক সংখ্যা অগ্রহণযোগ্য"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group">
                            <label for="lunch">দুপুর</label>
                            <input type="number" name="lunch" placeholder="দশমিক সংখ্যা অগ্রহণযোগ্য"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="form-group">
                            <label for="dinner">রাত</label>
                            <input type="number" name="dinner" placeholder="দশমিক সংখ্যা অগ্রহণযোগ্য"
                                class="form-control form-control-sm" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">যোগ করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-border-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap',
        initialView: 'dayGridMonth',
        eventDisplay: 'block',
        events: <?php echo $eventsJSON; ?>,
        eventContent: function(info) {
            return {
                html: info.event.extendedProps.content
            };
        },
        dateClick: function(info) {
            var currentDate = new Date();
            var clickedDate = new Date(info.dateStr);

            var tomorrow = new Date(currentDate);
            tomorrow.setDate(currentDate.getDate() + 1);
            if (
                clickedDate.getFullYear() === tomorrow.getFullYear() &&
                clickedDate.getMonth() === tomorrow.getMonth() &&
                clickedDate.getDate() === tomorrow.getDate()
            ) {
                var selectedDate = info.dateStr;
                $('#myModal').modal('show');
            } else {
                alert('আপনি শুধুমাত্র আগামীকালকের মিল যোগ করতে পারবেন');
            }
        },
        headerToolbar: {
            start: '',
            center: 'title',
            end: ''
        }
    });
    calendar.render();
});
document.getElementById('closeModalButton').addEventListener('click', function() {
    $('#myModal').modal('hide');
});
</script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.9/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js'></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>
/* Customize calendar header */
.fc-header-toolbar {
    background-color: #3498db;
    color: #fff;
}

/* Style calendar events */
.fc-event {
    background-color: #2ecc71;
    border-color: #27ae60;
    color: #fff;
}

/* Style calendar event title */
.fc-title {
    font-weight: bold;
}

/* Style modal dialog */
.modal-content {
    background-color: #f2f2f2;
    border-radius: 10px;
}

/* Style modal dialog title */
.modal-title {
    color: #3498db;
}

/* Style modal buttons */
.modal-footer .btn-success {
    background-color: #3498db;
    color: #fff;
}

/* Customize the "Add Event" button */
.btn-success {
    background-color: #3498db;
    color: #fff;
}

/* Style calendar navigation buttons */
.fc-button {
    background-color: #3498db;
    color: #fff;
}

/* Style today's date */
.fc-today {
    background-color: #f39c12;
    color: #fff;
}
</style>