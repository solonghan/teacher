/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Calendar init js
*/


var start_date = document.getElementById("event-start-date");
var timepicker1 = document.getElementById("timepicker1");
var timepicker2 = document.getElementById("timepicker2");
var date_range = null;
var T_check = null;
document.addEventListener("DOMContentLoaded", function () {
    flatPickrInit();
    var addEvent = new bootstrap.Modal(document.getElementById('event-modal'), {
        keyboard: false
    });
    document.getElementById('event-modal');
    var modalTitle = document.getElementById('modal-title');
    var formEvent = document.getElementById('form-event');
    var selectedEvent = null;
    var newEventData = null;
    var forms = document.getElementsByClassName('needs-validation');
    var eventObject = null;
    /* initialize the calendar */

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var Draggable = FullCalendar.Draggable;
    var externalEventContainerEl = document.getElementById('external-events');
    var defaultEvents = [];
    // {
    //     id: 0,
    //     title: '已預約人數：20',
    //     start: new Date(y, m, d + 3, 2, 0),
    //     end: new Date(y, m, d + 3, 3, 0),
    //     allDay: false,
    //     className: 'bg-soft-danger',
    //     location: 'Taiwan',
    //     description: 'Tell how to boost website traffic'
    // },

    // init dragable
    // new Draggable(externalEventContainerEl, {
    //     itemSelector: '.external-event',
    //     eventData: function (eventEl) {
    //         return {
    //             title: eventEl.innerText,
    //             start: new Date(),
    //             className: eventEl.getAttribute('data-class')
    //         };
    //     }
    // });

    // var draggableEl = document.getElementById('external-events');
    var calendarEl = document.getElementById('calendar');

    // function addNewEvent(info) {
    //     document.getElementById('form-event').reset();
    //     document.getElementById('btn-delete-event').setAttribute('hidden', true);
    //     addEvent.show();
    //     formEvent.classList.remove("was-validated");
    //     formEvent.reset();
    //     selectedEvent = null;
    //     modalTitle.innerText = 'Add Event';
    //     newEventData = info;
    //     document.getElementById("edit-event-btn").setAttribute("data-id", "new-event");
    //     document.getElementById('edit-event-btn').click();
    //     document.getElementById("edit-event-btn").setAttribute("hidden", true);
    // }

    function getInitialView() {
        return 'dayGridMonth';
        // if (window.innerWidth >= 768 && window.innerWidth < 1200) {
        //     return 'timeGridWeek';
        // } else if (window.innerWidth <= 768) {
        //     return 'listMonth';
        // } else {
        //     return 'dayGridMonth';
        // }
    }

    var count = true;

    // var eventCategoryChoice = new Choices("#event-category", {
    //     searchEnabled: false
    // });

    var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'Asia/Taipei',
        editable: false,
        droppable: false,
        selectable: false,
        navLinks: true,
        eventLimit: true,
        initialView: getInitialView(),
        themeSystem: 'bootstrap',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''//'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        windowResize: function (view) {
            var newView = getInitialView();
            calendar.changeView(newView);
        },
        eventClick: function (info) {
            
        },
        dateClick: function (info) {
            alert(info.dateStr)
        },
        eventSources: [
            {
                url: '/api/quota/data',
                method: 'POST',
                extraParams: {
                    'clinic_id': document.getElementsByClassName('clinic_id')[0].value
                },
                success: function (data) {
                    var events_list = data.data;
                    defaultEvents = [];
                    // if (count) {
                        events_list.forEach(function (element) {
                            var d_event = {
                                id: element.id,
                                title: element.title,
                                start: new Date(element.start),
                                end: new Date(element.end),
                                allDay: element.allDay,
                                className: element.className,
                                location: element.location,
                                description: element.description,
                                url: element.url
                            };
                            defaultEvents.push(d_event);
                        });
                        // console.log(JSON.stringify(defaultEvents))
                        console.log(defaultEvents)
                        // count = false;
                        return defaultEvents
                    // }
                }
            }
        ],
        events: defaultEvents,
        eventReceive: function(info) {
            console.log('receive')
            var newEvent = {
                id: Math.floor(Math.random() * 11000),
                title: info.event.title,
                start: info.event.start,
                allDay: info.event.allDay,
                className: info.event.classNames[0]
            };
            defaultEvents.push(newEvent);
            // upcomingEvent(defaultEvents);
        },
        eventDrop: function(info) {
            // var indexOfSelectedEvent = defaultEvents.findIndex(function(x) { return x.id == info.event.id});
            //     if(defaultEvents[indexOfSelectedEvent]) {
            //         defaultEvents[indexOfSelectedEvent].title = info.event.title;
            //         defaultEvents[indexOfSelectedEvent].start = info.event.start;
            //         defaultEvents[indexOfSelectedEvent].end = (info.event.end) ? info.event.end : null;
            //         defaultEvents[indexOfSelectedEvent].allDay = info.event.allDay;
            //         defaultEvents[indexOfSelectedEvent].className = info.event.classNames[0];
            //         defaultEvents[indexOfSelectedEvent].description = (info.event._def.extendedProps.description) ? info.event._def.extendedProps.description : '';
            //         defaultEvents[indexOfSelectedEvent].location = (info.event._def.extendedProps.location) ? info.event._def.extendedProps.location : '';
            //     }
            // var editEvent    = {
            //     id: Math.floor(Math.random() * 11000),
            //     title: info.event.title,
            //     start: info.event.start,
            //     allDay: info.event.allDay,
            //     className: info.event.classNames[0]
            // };
            // upcomingEvent(defaultEvents);
        }


    });

    calendar.render();

    /*Add new event*/
    // Form to add new event

    // formEvent.addEventListener('submit', function (ev) {
    //     ev.preventDefault();
    //     var updatedTitle = document.getElementById("event-title").value;
    //     var updatedCategory = document.getElementById('event-category').value;
    //     var start_date = (document.getElementById("event-start-date").value).split("to");
    //     var updateStartDate = new Date(start_date[0].trim());
    //     var updateEndDate = (start_date[1]) ? new Date(start_date[1].trim()) : '';
    //     var end_date = null;
    //     var event_location = document.getElementById("event-location").value;
    //     var eventDescription = document.getElementById("event-description").value;
    //     var eventid = document.getElementById("eventid").value;
    //     var all_day = false;
    //     if (start_date.length > 1) {
    //         var date = new Date(start_date[1]);
    //         date = date.setTime(date.getTime() + (23 * 60 * 60 * 1000));
    //         // updateEndDate;
    //         start_date = new Date(start_date[0]);
    //     } else {
    //         var e_date = start_date;
    //         var start_time = (document.getElementById("timepicker1").value).trim();
    //         var end_time = (document.getElementById("timepicker2").value).trim();
    //         start_date = new Date(start_date + "T" + start_time);
    //         end_date = new Date(e_date + "T" + end_time);
    //         all_day = true;
    //     }
    //     var e_id = defaultEvents.length + 1;

    //     // validation
    //     if (forms[0].checkValidity() === false) {
    //         forms[0].classList.add('was-validated');
    //     } else {
    //         if (selectedEvent) {
    //             selectedEvent.setProp("id", eventid);
    //             selectedEvent.setProp("title", updatedTitle);
    //             selectedEvent.setProp("classNames", [updatedCategory]);
    //             selectedEvent.setStart(updateStartDate);
    //             selectedEvent.setEnd(updateEndDate);
    //             // selectedEvent.setStart();
    //             // selectedEvent.setDates(updateStartDate,updateEndDate);
    //             selectedEvent.setAllDay(all_day);
    //             selectedEvent.setExtendedProp("description", eventDescription);
    //             selectedEvent.setExtendedProp("location", event_location);

    //             var indexOfSelectedEvent = defaultEvents.findIndex(function(x) { return x.id == selectedEvent.id});
    //             if(defaultEvents[indexOfSelectedEvent]) {
    //                 defaultEvents[indexOfSelectedEvent].title = updatedTitle;
    //                 defaultEvents[indexOfSelectedEvent].start = updateStartDate;
    //                 defaultEvents[indexOfSelectedEvent].end = updateEndDate;
    //                 defaultEvents[indexOfSelectedEvent].allDay = all_day;
    //                 defaultEvents[indexOfSelectedEvent].className = updatedCategory;
    //                 defaultEvents[indexOfSelectedEvent].description = eventDescription;
    //                 defaultEvents[indexOfSelectedEvent].location = event_location;
    //             }
    //             calendar.render();
    //             // default
    //         } else {
    //             var newEvent = {
    //                 id: e_id,
    //                 title: updatedTitle,
    //                 start: start_date,
    //                 end: end_date,
    //                 allDay: all_day,
    //                 className: updatedCategory,
    //                 description: eventDescription,
    //                 location: event_location
    //             };
    //             calendar.addEvent(newEvent);
    //             defaultEvents.push(newEvent);
    //         }
    //         addEvent.hide();
    //         upcomingEvent(defaultEvents);
    //     }
    // });

    // document.getElementById("btn-delete-event").addEventListener("click", function (e) {
    //     if (selectedEvent) {
    //         for (var i = 0; i < defaultEvents.length; i++) {
    //             if (defaultEvents[i].id == selectedEvent.id) {
    //                 defaultEvents.splice(i, 1);
    //                 i--;
    //             }
    //         }
    //         upcomingEvent(defaultEvents);
    //         selectedEvent.remove();
    //         selectedEvent = null;
    //         addEvent.hide();
    //     }
    // });
    // document.getElementById("btn-new-event").addEventListener("click", function (e) {
    //     flatpicekrValueClear();
    //     flatPickrInit();
    //     addNewEvent();
    //     document.getElementById("edit-event-btn").setAttribute("data-id", "new-event");
    //     document.getElementById('edit-event-btn').click();
    //     document.getElementById("edit-event-btn").setAttribute("hidden", true);
    // });
});


function flatPickrInit() {
    var config = {
        enableTime: true,
        noCalendar: true,
        defaultDate: "09:00",
        dateFormat: "H:i",
    };
    var date_range = flatpickr(
        start_date, {
        enableTime: false,
        mode: "range",
        minDate: "today",
        dateFormat: "Y-m-d",
        onChange: function (selectedDates, dateStr, instance) {
            var date_range = dateStr;
            var dates = date_range.split("to");
            if (dates.length > 1) {
                document.getElementById('event-time').setAttribute("hidden", true);
            } else {
                document.getElementById("timepicker1").parentNode.classList.remove("d-none");
                document.getElementById("timepicker1").classList.replace("d-none", "d-block");
                document.getElementById("timepicker2").parentNode.classList.remove("d-none");
                document.getElementById("timepicker2").classList.replace("d-none", "d-block");
                document.getElementById('event-time').removeAttribute("hidden");
            }
        },
    });
    flatpickr(timepicker1, config);
    flatpickr(timepicker2, config);

}

function flatpicekrValueClear() {
    start_date.flatpickr().clear();
    timepicker1.flatpickr().clear();
    timepicker2.flatpickr().clear();
}


function eventClicked() {
    document.getElementById('form-event').classList.add("view-event");
    document.getElementById("event-title").classList.replace("d-block", "d-none");
    document.getElementById("event-category").classList.replace("d-block", "d-none");
    document.getElementById("event-start-date").parentNode.classList.add("d-none");
    document.getElementById("event-start-date").classList.replace("d-block", "d-none");
    document.getElementById('event-time').setAttribute("hidden", true);
    document.getElementById("timepicker1").parentNode.classList.add("d-none");
    document.getElementById("timepicker1").classList.replace("d-block", "d-none");
    document.getElementById("timepicker2").parentNode.classList.add("d-none");
    document.getElementById("timepicker2").classList.replace("d-block", "d-none");
    document.getElementById("event-location").classList.replace("d-block", "d-none");
    document.getElementById("event-description").classList.replace("d-block", "d-none");
    document.getElementById("event-start-date-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-timepicker1-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-timepicker2-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-location-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-description-tag").classList.replace("d-none", "d-block");
    document.getElementById('btn-save-event').setAttribute("hidden", true);
}

function editEvent(data) {
    var data_id = data.getAttribute("data-id");
    if (data_id == 'new-event') {
        document.getElementById('modal-title').innerHTML = "";
        document.getElementById('modal-title').innerHTML = "Add Event";
        document.getElementById("btn-save-event").innerHTML = "Add Event";
        eventTyped();
    } else if (data_id == 'edit-event') {
        data.innerHTML = "Cancel";
        data.setAttribute("data-id", 'cancel-event');
        document.getElementById("btn-save-event").innerHTML = "Update Event";
        data.removeAttribute("hidden");
        eventTyped();
    } else {
        data.innerHTML = "Edit";
        data.setAttribute("data-id", 'edit-event');
        eventClicked();
    }
}

function eventTyped() {
    document.getElementById('form-event').classList.remove("view-event");
    document.getElementById("event-title").classList.replace("d-none", "d-block");
    document.getElementById("event-category").classList.replace("d-none", "d-block");
    document.getElementById("event-start-date").parentNode.classList.remove("d-none");
    document.getElementById("event-start-date").classList.replace("d-none", "d-block");
    document.getElementById("timepicker1").parentNode.classList.remove("d-none");
    document.getElementById("timepicker1").classList.replace("d-none", "d-block");
    document.getElementById("timepicker2").parentNode.classList.remove("d-none");
    document.getElementById("timepicker2").classList.replace("d-none", "d-block");
    document.getElementById("event-location").classList.replace("d-none", "d-block");
    document.getElementById("event-description").classList.replace("d-none", "d-block");
    document.getElementById("event-start-date-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-timepicker1-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-timepicker2-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-location-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-description-tag").classList.replace("d-block", "d-none");
    document.getElementById('btn-save-event').removeAttribute("hidden");
}

// upcoming Event
// function upcomingEvent(a) {
//     a.sort(function (o1, o2) {
//         return (new Date(o1.start)) - (new Date(o2.start));
//     });
//     document.getElementById("upcoming-event-list").innerHTML = null;
//     a.forEach(function (element) {

//         var title = element.title;
//         var e_dt = element.end;
//         if (e_dt == "Invalid Date" || e_dt == undefined) {
//             e_dt = null;
//         } else {
//             var newDate = new Date(e_dt).toLocaleDateString()
//             e_dt = new Date(newDate).toLocaleDateString('en-GB', {
//                 day : 'numeric',
//                 month : 'short',
//                 year : 'numeric'
//             }).split(' ').join(' ');
//         }

//         var st_date = str_dt(element.start);
//         var ed_date = str_dt(element.end);
//         if (st_date === ed_date) {
//             e_dt = null;
//         }
//         var startDate = element.start;
//         if (startDate == "Invalid Date" || startDate == undefined) {
//             startDate = null;
//         } else {
//             var newDate = new Date(startDate).toLocaleDateString()
//             startDate = new Date(newDate).toLocaleDateString('en-GB', {
//                 day : 'numeric',
//                 month : 'short',
//                 year : 'numeric'
//             }).split(' ').join(' ');
//         }

//         var end_dt = (e_dt) ? " to " + e_dt : '';
//         var category = (element.className).split("-");
//         var description = (element.description) ? element.description : "";
//         var e_time_s = tConvert(getTime(element.start));
//         var e_time_e = tConvert(getTime(element.end));
//         if (e_time_s == e_time_e) {
//             var e_time_s = "Full day event";
//             var e_time_e = null;
//         }
//         var e_time_e = (e_time_e) ? " to " + e_time_e : "";

//         u_event = "<div class='card mb-3'>\
//                         <div class='card-body'>\
//                             <div class='d-flex mb-3'>\
//                                 <div class='flex-grow-1'><i class='mdi mdi-checkbox-blank-circle me-2 text-" + category[2] + "'></i><span class='fw-medium'>" + startDate + end_dt + "</span></div>\
//                                 <div class='flex-shrink-0'><small class='badge badge-soft-primary ms-auto'>" + e_time_s + e_time_e + "</small></div>\
//                             </div>\
//                             <h6 class='card-title fs-16'> " + title + "</h6>\
//                             <p class='text-muted text-truncate-two-lines mb-0'> "+ description + "</p>\
//                         </div>\
//                     </div>";
//         document.getElementById("upcoming-event-list").innerHTML += u_event;
//     });
// };

function getTime(params) {
    params = new Date(params);
    if (params.getHours() != null) {
        var hour = params.getHours();
        var minute = (params.getMinutes()) ? params.getMinutes() : '00';
        return hour + ":" + minute;
    }
}

function tConvert(time) {
    var t = time.split(":");
    var hours = t[0];
    var minutes = t[1];
    var newformat = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    return (hours + ':' + minutes + ' ' + newformat);
}

var str_dt = function formatDate(date) {
    var monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    var d = new Date(date),
        month = '' + monthNames[(d.getMonth())],
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;
    return [day + " " + month, year].join(',');
};
