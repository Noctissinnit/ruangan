let isBookingPost = false;

$(document).ready(() => {
    generateCalendar();
    initTimepickers();
    updateDateTime();
    updateBookings();
    clearForms();
    tryGoogleCallback();

    $("#select-room").select2({
        dropdownParent: $("#bookingModal"),
        width: "resolve",
    });
    $("#select-users").select2({
        dropdownParent: $("#bookingModal"),
        width: "resolve",
        multiple: true,
    });

    $('#select-users-banyak').select2({
        dropdownParent: $('#bookingMultiModal'), // penting agar dropdown muncul di modal
        width: '100%',
        placeholder: 'Pilih peserta',
    });
    $(document).ready(function () {
            flatpickr("#multi-dates", {
            mode: "multiple",
            dateFormat: "Y-m-d",
        });
   
    const bookingMultiModal = document.getElementById('bookingMultiModal');
    bookingMultiModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const roomId = button.getAttribute('data-room-id');
        const input = bookingMultiModal.querySelector('#multi-room-id');
        if (roomId && input) {
            input.value = roomId;
        }
    });


    $('#btn-history-add-booking').click(function () {
        $('#loginModal').modal('show');
        $('#bookingHistoryModal').modal('hide');
    });

    $("#form-login").submit(checkLogin);
    $('#btn-booking-form-close').click(resetSession);
    $('#form-booking').submit(e => {
    if (isBookingPost) return;

    const formData = new FormData(e.currentTarget);

    // Validasi manual
    if (!validateEmptyForm(formData, {
        'start_time': 'Jam',
        'end_time': 'Jam',
        'description': 'Deskripsi',
        'members': 'Peserta',
    })) {
        e.preventDefault();
        return;
    }

    if (isTimeLessOrEqual(formData.get("end_time"), formData.get("start_time"))) {
        alert("Jam Selesai tidak bisa kurang atau sama dengan dari Jam Mulai.");
        e.preventDefault();
        return;
    }

    // Kurangi 1 menit dari end_time
    let endTime = new Date(`1970-01-01T${formData.get("end_time")}`);
    endTime.setMinutes(endTime.getMinutes() - 1);
    let formattedEndTime = endTime.toTimeString().slice(0, 5);
    $('input[name="end_time"]').val(formattedEndTime);

    $('#loading').css('display', 'flex');
    isBookingPost = true;
    // Biarkan form lanjut submit secara normal
    });
});
//     $('#bookingMultiModal').on('shown.bs.modal', function () {
    
// });



    $('button[data-bs-dismiss="modal"]').click(clearForms);

    $(document).on('keydown', function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'h') {
            toggleOfficeMode();
        }
    });
});

async function showBookingHistory(date, dateStr) {
    $('#bookingHistoryDate').html(dateStr);

    const url = new URL(listUrl);
    url.searchParams.set('date', dateStr);
    url.searchParams.set('room_id', roomId);

    const bookingsData = await $.get(url.toString());
    const tableBody = $('#bookingHistoryTable>tbody');

    tableBody.html('');
    if (bookingsData.length > 0) {
        bookingsData.forEach((data, i) => {

            let originalEndTime = data.end_time;  // Misalnya end_time dalam format HH:MM
            
            // Logika untuk menambahkan 1 menit ke waktu selesai (end_time)
            let endTimeWithOneMinuteAdded = new Date(`1970-01-01T${originalEndTime}`);
            endTimeWithOneMinuteAdded.setMinutes(endTimeWithOneMinuteAdded.getMinutes() + 1);
            let formattedEndTimeWithAddedMinute = endTimeWithOneMinuteAdded.toTimeString().slice(0, 5);
            
            tableBody.append(`
                <tr>
                    <td>${data.department ? data.department.name : ""}</td>
                    <td>${formatTime(data.start_time)}</td>
                    <td>${formattedEndTimeWithAddedMinute}</td>
                    <td>${data.description}</td>
                </tr>
            `)
        });
    } else {
        tableBody.html(`<tr><td colspan="7">Tidak ada data peminjaman...</td></tr>`)
    }

    $('#form-booking>input[name="date"]').val(dateStr);
    $('#form-booking-date').val(dateStr);
    if (!isOfficeMode) $('#btn-history-add-booking').css('display', isAtLeastOneDayLess(date, new Date()) ? 'none' : '');

    $('#bookingHistoryModal').modal('show');
}

function initTimepickers() {
    $('input.timepicker').datetimepicker({
        datepicker: false,
        format: 'H:i',
    })
}

function generateCalendar() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: $(window).width() > 768 ? 'dayGridMonth' : 'dayGridWeek',
        },
        initialView: $(window).width() > 768 ? 'dayGridMonth' : 'dayGridWeek',
        dateClick: function (info) {
            showBookingHistory(info.date, info.dateStr);
        },
        events: async function (info, successCallback, failureCallback) {
            try {
                // Fetch bookings within the view range
                const url = new URL(listUrl);
                url.searchParams.set('start', info.startStr);
                url.searchParams.set('end', info.endStr);
                url.searchParams.set('room_id', roomId);

                const bookings = await $.get(url.toString());
                const events = bookings.map(booking => {
                    // Format waktu untuk kalender
                    const startTime = booking.start_time.slice(0, 5); // Format to HH:mm
                    let endTime = booking.end_time.slice(0, 5); // Format to HH:mm

                    // Tambahkan 1 menit ke end_time untuk ditampilkan di kalender
                    let endTimeDate = new Date(`1970-01-01T${endTime}`);
                    endTimeDate.setMinutes(endTimeDate.getMinutes() + 1);
                    endTime = endTimeDate.toTimeString().slice(0, 5);

                    const title = `${startTime} - ${endTime}: ${booking.description}`;
                    const color = getRandomColor(); // Assign a random color to each event
                    return {
                        title: title,
                        start: `${booking.date}T${booking.start_time}`,
                        end: `${booking.date}T${endTime}`, // Gunakan end_time yang sudah ditambah 1 menit
                        color: color, // Use random color for background
                        extendedProps: {
                            department: booking.department,
                            description: booking.description,
                            start_time: startTime,
                            end_time: endTime, // Tampilkan end_time yang sudah ditambah 1 menit
                            user_name: booking.user ? booking.user.name : '' ,
                        }
                    };
                });

                successCallback(events);
            } catch (error) {
                console.error('Error fetching bookings:', error);
                failureCallback(error);
            }
        },
        eventClick: function (info) {
            const booking = info.event.extendedProps;
            const bookingDetailsHtml = `
                <p><strong>Booked by:</strong> ${booking.user_name}</p>
                <p><strong>Department:</strong> ${booking.department}</p>
                <p><strong>Time:</strong> ${booking.start_time} - ${booking.end_time}</p>
                <p><strong>Description:</strong> ${booking.description}</p>
            `;
            $('#bookingDetailsModal .modal-body').html(bookingDetailsHtml);
            $('#bookingDetailsModal').modal('show');
        },
    });
    calendar.render();
}

// Function to generate a random color
function getRandomColor() {
    const colors = [
        '#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#A833FF', '#FFD133', '#33FFDB'
    ];
    return colors[Math.floor(Math.random() * colors.length)];
}

function isToday(dateString) {
    // Create a Date object from the input string
    const inputDate = new Date(dateString);

    // Get today's date
    const today = new Date();

    return isDateEqual(inputDate, today);
}

function isDateEqual(date1, date2) {
    // Check if the input date is today by comparing the year, month, and day
    return date1.getFullYear() === date2.getFullYear() &&
        date1.getMonth() === date2.getMonth() &&
        date1.getDate() === date2.getDate();
}

function isTimeRangeOverlap(start1, end1, start2, end2) {
    // Convert times to Date objects for comparison
    const formatTime = (time) => new Date(`1970-01-01T${time}:00`);

    const startTime1 = formatTime(start1);
    const endTime1 = formatTime(end1);
    const startTime2 = formatTime(start2);
    const endTime2 = formatTime(end2);

    // Check if the two time ranges overlap
    return startTime1 <= endTime2 && startTime2 <= endTime1;
}

function isAtLeastOneDayLess(date1, date2) {
    const differenceInTime = date2.getTime() - date1.getTime();
    const oneDayInMilliseconds = 24 * 60 * 60 * 1000;
    return differenceInTime > oneDayInMilliseconds;
}

function isTimeLessOrEqual(time1, time2) {
    // Parse the times as hours and minutes (assuming "HH:mm" format)
    const [hours1, minutes1] = time1.split(':').map(Number);
    const [hours2, minutes2] = time2.split(':').map(Number);

    // Create Date objects for comparison
    const date1 = new Date();
    date1.setHours(hours1, minutes1);

    const date2 = new Date();
    date2.setHours(hours2, minutes2);

    // Compare the two times
    return date1 <= date2;
}

function updateDateTime() {
    const now = new Date();
    const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    const formattedDate = now.toLocaleDateString("id-ID", options);
    const formattedTime = now.toLocaleTimeString("id-ID");

    document.getElementById("current-date").innerText = formattedDate;
    document.getElementById("current-time").innerText = formattedTime;
}

async function updateBookings() {
    const url = new URL(listUrl);
    url.searchParams.set('date', new Date().toISOString().substring(0, 10));
    url.searchParams.set('room_id', roomId);

    const bookingsData = await $.get(url.toString());

    const currentBookings = $("#current-bookings>tbody");
    currentBookings.empty(); // Menghapus konten sebelumnya sebelum menambahkan yang baru

    if (bookingsData.length === 0) {
        currentBookings.append(`<tr><td colspan="3">Tidak ada peminjaman hari ini...</td></tr>`);
    } else {
        bookingsData.forEach((booking) => {
            let originalEndTime = booking.end_time; // Format HH:MM
            
            // Menambahkan 1 menit ke end_time
            let endTimeWithOneMinuteAdded = new Date(`1970-01-01T${originalEndTime}`);
            endTimeWithOneMinuteAdded.setMinutes(endTimeWithOneMinuteAdded.getMinutes() + 1);
            let formattedEndTimeWithAddedMinute = endTimeWithOneMinuteAdded.toTimeString().slice(0, 5);
            
            currentBookings.append(`
            <tr>
                <td>${formatTime(booking.start_time)}</td>
                <td>${formattedEndTimeWithAddedMinute}</td>
                <td>${booking.description}</td>
                ${isAdmin ? `<td><a href="${destroyUrl}?id=${booking.id}"><button class="btn btn-danger btn-sm ml-2">Hapus</button></a></td>` : ""}
            </tr>
            `);
        });
    }
}

function formatTime(time) {
    let parts = time.split(":");
    if (parts.length === 3) {
        return parts.slice(0, 2).join(":");
    }
    return time;
}

async function checkLogin(e) {
    e.preventDefault();

    const nis = document.getElementById("login-nis").value;
    const password = document.getElementById("login-password").value;

    const res = await $.post(loginUrl, { nis, password });
    if (res.success) {
        $('#form-booking>input[name="room_id"]').val(roomId);
        $('#form-booking>input[name="nis"]').val(nis);
        $('#form-booking>input[name="password"]').val(password);
        $('#form-booking>input[name="nama"]').val(res.data.name);
        $('#form-booking>input[name="email"]').val(res.data.email);
        $('#form-booking>input[name="department_id"]').val(res.data.department.id);
        $('#booking-user-department').val(res.data.department.name);
        $('#form-booking-user-name').html(res.data.name);

        $("#loginModal").modal("hide");
        $("#bookingModal").modal("show");
    } else {
        error(res.message);
    }
}

function clearForms() {
    $('#form-login')[0].reset();
    $('#form-booking')[0].reset();
}

function tryGoogleCallback(isLoggedIn = true) {
    if (!isGoogleCallback) return false;

    if (isLoggedIn) {
        $('#form-booking-date').val($('#form-booking>input[name="date"]').val());
    } else {
        $('#form-booking>input[name="date"]').val(bookingsDate);
        $('#form-booking-date').val(bookingsDate);
    }
    $('#bookingHistoryModal').modal('hide');
    $('#bookingModal').modal('show');

    return true;
}

function toggleOfficeMode() {
    isOfficeMode = !isOfficeMode;

    if (isOfficeMode) {
        $('#btn-history-add-booking').css('display', 'none');
    }
}

async function resetSession() {
    await $.get(resetSessionUrl);
}

setInterval(updateDateTime, 1000);
// setInterval(updateCurrentAvailable, 1000);
// setInterval(updateBookings, 1000);