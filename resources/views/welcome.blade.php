<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TOAST UI Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css" />
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css" />
    <script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.js"></script>
    <script src="https://uicdn.toast.com/tui.dom/v3.0.0/tui-dom.js"></script>
    <script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center align-items-center">
                <h1>Takvim</h1>
            </div>
            <div class="col-md-12 p-5">
            <a href="{{ route('ajax.pdf') }}" class="btn btn-primary">PDF İndir</a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        'use strict';

        var CalendarList = [];
        var schedulesArray = [];

        function exportScheduleDataToPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            schedulesArray.forEach((item, index) => {
                const yCoord = 10 + (index * 30); // Her bir öğeyi 30 milimetre aralıklarla yazdır
                doc.text(`Location: ${item.location}`, 10, yCoord);
                doc.text(`Title: ${item.title}`, 10, yCoord + 10);
                doc.line(10, yCoord + 15, 200, yCoord + 15); // Çizgi ekle
            });

            doc.save('output.pdf');
        }

        function CalendarInfo() {
            this.id = null;
            this.name = null;
            this.checked = true;
            this.color = null;
            this.bgColor = null;
            this.borderColor = null;
            this.dragBgColor = null;
        }

        function addCalendar(calendar) {
            CalendarList.push(calendar);
        }

        function findCalendar(id) {
            return CalendarList.find(calendar => calendar.id === id) || CalendarList[0];
        }

        function hexToRGBA(hex) {
            var radix = 16;
            var r = parseInt(hex.slice(1, 3), radix),
                g = parseInt(hex.slice(3, 5), radix),
                b = parseInt(hex.slice(5, 7), radix),
                a = parseInt(hex.slice(7, 9), radix) / 255 || 1;
            var rgba = 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';

            return rgba;
        }



        $(function() {
            var calendar;

            calendar = new CalendarInfo();
            calendar.id = Math.floor(Math.random() * 90000) + 10000;
            calendar.name = 'Post';
            calendar.color = '#624AC0';
            calendar.bgColor = '#F0EFF6';
            calendar.dragBgColor = '#F0EFF6';
            calendar.borderColor = '#F0EFF6';
            addCalendar(calendar);

            calendar = new CalendarInfo();
            calendar.id = Math.floor(Math.random() * 90000) + 10000;
            calendar.name = 'Events';
            calendar.color = '#FF8C1A';
            calendar.bgColor = '#FDF8F3';
            calendar.dragBgColor = '#FDF8F3';
            calendar.borderColor = '#FDF8F3';
            addCalendar(calendar);

            calendar = new CalendarInfo();
            calendar.id = Math.floor(Math.random() * 90000) + 10000;
            calendar.name = 'Offer';
            calendar.color = '#578E1C';
            calendar.bgColor = '#EEF8F0';
            calendar.dragBgColor = '#EEF8F0';
            calendar.borderColor = '#EEF8F0';
            addCalendar(calendar);
        });

        $(function() {
            var cal = new tui.Calendar('#calendar', {
                defaultView: 'month',
                useCreationPopup: true,
                useDetailPopup: true,
                calendars: CalendarList,
                template: {
                    milestone: function(model) {
                        return '<span class="calendar-font-icon ic-milestone-b"></span> <span style="background-color: ' +
                            model.bgColor + '">' + model.title + '</span>';
                    },
                    allday: function(schedule) {
                        return getTimeTemplate(schedule, true);
                    },
                    time: function(schedule) {
                        return getTimeTemplate(schedule, false);
                    }
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function saveNewScheduleAjax(e) {
                $.ajax({
                    url: "{{ route('ajax.add_or_update_event') }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        title: e.title,
                        location: e.location,
                        uniqueId: e.calendarId,
                        eventStart: e.start._date,
                        eventEnd: e.end._date,
                        allDay: e.isAllDay,
                    },
                    success: function(response) {
                        // JSON formatında dönen yanıtı işleyin
                        if (response.success) {
                            console.log('Event created successfully!');
                            console.log('Gelen veriler: ', response.data); // Gelen tüm verileri konsola yazdırın
                        } else {
                            console.error('Failed to create event:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.error('Response:', xhr.responseText); // Sunucudan dönen yanıtı detaylı inceleyin
                    }
                });
            }

            function saveNewScheduleAjaxUpdate(e) {
                $.ajax({
                    url: "{{ route('ajax.add_or_update_event') }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        title: e.title,
                        location: e.location,
                        uniqueId: e.uniqueId,
                        eventStart: e.eventStart,
                        eventEnd: e.eventEnd,
                        allDay: e.allDay,
                    },
                    success: function(response) {
                        // JSON formatında dönen yanıtı işleyin
                        if (response.success) {
                            console.log('Event created successfully!');
                            console.log('Gelen veriler: ', response.data); // Gelen tüm verileri konsola yazdırın
                        } else {
                            console.error('Failed to create event:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.error('Response:', xhr.responseText); // Sunucudan dönen yanıtı detaylı inceleyin
                    }
                });
            }

            function deleteEvent(e) {
                $.ajax({
                    url: "{{ route('ajax.delete_event') }}",
                    method: 'GET',
                    data: {
                        uniqueId: e.uniqueId
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Event deleted successfully!');
                        } else {
                            console.error('Failed to delete event:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }


            cal.on({

                'clickMore': function(e) {
                    console.log('clickMore', e);
                },
                'clickSchedule': function(e) {},
                'clickDayname': function(date) {
                    console.log('clickDayname', date);
                },
                'beforeCreateSchedule': function(e) {

                    saveNewSchedule(e);

                    var scheduleDataNew = {
                        title: e.title,
                        location: e.location,
                        uniqueId: e.uniqueId
                    };

                    schedulesArray.push(scheduleDataNew);

                    saveNewScheduleAjax(e);

                    console.log("Eklenen Event : ", e);
                },
                'beforeUpdateSchedule': function(e) {

                    var schedule = e.schedule;
                    var changes = e.changes;

                    console.log("Güncellenen Event : ", e);

                    cal.updateSchedule(schedule.id, schedule.calendarId, e);
                    refreshScheduleVisibility();

                    var updateEventData = {

                        title: e.changes.title,
                        location: e.changes.location,
                        uniqueId: e.schedule.id,
                        eventStart: e.start._date,
                        eventEnd: e.end._date,
                        allDay: e.schedule.isAllDay

                    };

                    saveNewScheduleAjaxUpdate(updateEventData);

                },
                'beforeDeleteSchedule': function(e) {
                    console.log("Event Sil");
                    console.log('beforeDeleteSchedule', e);
                    cal.deleteSchedule(e.schedule.id, e.schedule.calendarId);

                    var deleteEventData = {
                        uniqueId: e.schedule.calendarId
                    }

                    deleteEvent(deleteEventData);
                    console.log("Eklenen Silme : ", e.schedule.calendarId);
                },
                'afterRenderSchedule': function(e) {
                    var schedule = e.schedule;
                },
                'clickTimezonesCollapseBtn': function(timezonesCollapsed) {
                    console.log('timezonesCollapsed', timezonesCollapsed);

                    if (timezonesCollapsed) {
                        cal.setTheme({
                            'week.daygridLeft.width': '77px',
                            'week.timegridLeft.width': '77px'
                        });
                    } else {
                        cal.setTheme({
                            'week.daygridLeft.width': '60px',
                            'week.timegridLeft.width': '60px'
                        });
                    }

                    return true;
                }
            });

            function getTimeTemplate(schedule, isAllDay) {
                var html = [];
                var start = moment(schedule.start.toUTCString());
                if (!isAllDay) {
                    html.push('<strong>' + start.format('HH:mm') + '</strong> ');
                }
                if (schedule.isPrivate) {
                    html.push('<span class="calendar-font-icon ic-lock-b"></span>');
                    html.push(' Private');
                } else {
                    if (schedule.isReadOnly) {
                        html.push('<span class="calendar-font-icon ic-readonly-b"></span>');
                    } else if (schedule.recurrenceRule) {
                        html.push('<span class="calendar-font-icon ic-repeat-b"></span>');
                    } else if (schedule.attendees.length) {
                        html.push('<span class="calendar-font-icon ic-user-b"></span>');
                    } else if (schedule.location) {
                        html.push('<span class="calendar-font-icon ic-location-b"></span>');
                    }
                    html.push(' ' + schedule.title);
                }

                return html.join('');
            }

            function onClickNavi(e) {
                var action = getDataAction(e.target);

                switch (action) {
                    case 'move-prev':
                        cal.prev();
                        break;
                    case 'move-next':
                        cal.next();
                        break;
                    case 'move-today':
                        cal.today();
                        break;
                    default:
                        return;
                }

                setRenderRangeText();
                setSchedules();
            }

            function onNewSchedule() {
                var title = $('#new-schedule-title').val();
                var location = $('#new-schedule-location').val();
                var isAllDay = $('#new-schedule-allday').prop('checked');
                var start = datePicker.getStartDate();
                var end = datePicker.getEndDate();
                var calendar = selectedCalendar ? selectedCalendar : CalendarList[0];

                console.log("onNewSchedule :", calendar);

                if (!title) {
                    return;
                }

                cal.createSchedules([{
                    id: 45,
                    calendarId: 45,
                    title: title,
                    isAllDay: isAllDay,
                    start: start,
                    end: end,
                    category: isAllDay ? 'allday' : 'time',
                    dueDateClass: '',
                    color: calendar.color,
                    bgColor: calendar.bgColor,
                    dragBgColor: calendar.bgColor,
                    borderColor: calendar.borderColor,
                    raw: {
                        location: location
                    },
                    state: 'Busy'
                }]);

                $('#modal-new-schedule').modal('hide');
            }

            function onChangeNewScheduleCalendar(e) {
                var target = $(e.target).closest('a[role="menuitem"]');
                var calendarId = getDataAction(target);
                changeNewScheduleCalendar(calendarId);
            }

            function changeNewScheduleCalendar(calendarId) {
                var calendarNameElement = $('#calendarName');
                var calendar = findCalendar(calendarId);
                var html = [];

                html.push('<span class="calendar-bar" style="background-color: ' + calendar.bgColor +
                    '; border-color:' + calendar.borderColor + ';"></span>');
                html.push('<span class="calendar-name">' + calendar.name + '</span>');

                calendarNameElement.html(html.join(''));

                selectedCalendar = calendar;
            }

            function createNewSchedule(event) {
                var start = event.start ? new Date(event.start.getTime()) : new Date();
                var end = event.end ? new Date(event.end.getTime()) : moment().add(1, 'hours').toDate();

                if (useCreationPopup) {
                    cal.openCreationPopup({
                        start: start,
                        end: end
                    });
                }
            }

            function saveNewSchedule(scheduleData) {

                var calendar = scheduleData.calendar || findCalendar(scheduleData.calendarId);

                var schedule = {
                    id: calendar.id,
                    title: scheduleData.title,
                    start: scheduleData.start,
                    end: scheduleData.end,
                    category: 'allday',
                    color: calendar.color,
                    bgColor: calendar.bgColor,
                    dragBgColor: calendar.bgColor,
                    borderColor: calendar.borderColor,
                    location: scheduleData.location
                };

                if (calendar) {
                    schedule.calendarId = calendar.id;
                    schedule.color = calendar.color;
                    schedule.bgColor = calendar.bgColor;
                    schedule.borderColor = calendar.borderColor;
                    schedule.dragBgColor = calendar.dragBgColor;
                }

                cal.createSchedules([schedule]);
                refreshScheduleVisibility();
            }

            function refreshScheduleVisibility() {
                var calendarElements = Array.prototype.slice.call(document.querySelectorAll('#calendarList input'));

                CalendarList.forEach(function(calendar) {
                    cal.toggleSchedules(calendar.id, !calendar.checked, false);
                });

                cal.render(true);

                calendarElements.forEach(function(input) {
                    var span = input.nextElementSibling;
                    span.style.backgroundColor = input.checked ? span.style.borderColor : 'transparent';
                });
            }

            function setRenderRangeText() {
                var renderRange = $('#renderRange');
                var options = cal.getOptions();
                var viewName = cal.getViewName();
                var html = [];
                if (viewName === 'day') {
                    html.push(moment(cal.getDate().getTime()).format('YYYY.MM.DD'));
                } else if (viewName === 'month' &&
                    (!options.month || !options.month.visibleWeeksCount || options.month.visibleWeeksCount > 4)) {
                    html.push(moment(cal.getDate().getTime()).format('YYYY.MM'));
                } else {
                    html.push(moment(cal.getDateRangeStart().getTime()).format('YYYY.MM.DD'));
                    html.push(' ~ ');
                    html.push(moment(cal.getDateRangeEnd().getTime()).format('YYYY.MM.DD'));
                }
                renderRange.html(html.join(''));
            }

            function setSchedules() {
                cal.clear();
                cal.createSchedules(CalendarList, true);
                refreshScheduleVisibility();
            }

            function setEventListener() {
                $('#menu-navi').on('click', onClickNavi);
                $('#dropdownMenu-calendars-list').on('click', onChangeNewScheduleCalendar);
                $('#btn-save-schedule').on('click', onNewSchedule);
                $('#btn-new-schedule').on('click', createNewSchedule);
                $('#btn-save-schedule').on('click', saveNewSchedule);
                $('#btn-hide-schedule').on('click', function() {
                    $('#modal-new-schedule').modal('hide');
                });
                $('.sidebar-calendars').on('change', function(e) {
                    var calendarId = $(this).data('calendar-id');
                    var calendar = findCalendar(calendarId);
                    calendar.checked = !calendar.checked;
                    refreshScheduleVisibility();
                });
            }

            setRenderRangeText();
            setSchedules();
            setEventListener();
        });
    </script>
</body>

</html>