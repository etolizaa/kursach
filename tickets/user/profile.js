$(document).ready(function() {
    loadUserBookings();
    setInterval(loadUserBookings, 5000);

    function loadUserBookings() {
        $.ajax({
            url: 'profile_controller.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateBookings(response.data);
                } else {
                    alert(response.error);
                }
            },
            error: function() {
                alert('Ошибка при загрузке бронирований.');
            }
        });
    }

    function updateBookings(bookings) {
        var container = $('#bookings-container');
        container.empty();

        if (bookings.length === 0) {
            container.append('<div class="alert alert-danger">У вас ни одного бронирования.</div>');
        } else {
            var row = $('<div class="row"></div>');
            $.each(bookings, function(index, booking) {
                var card = $(`
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <img src="${booking.image}" class="card-img-top" style="height: 350px; object-fit: fill;">
                                    <div class="card-body">
                                        <h5 class="card-title">${booking.title}</h5>
                                        <p class="card-text">Длительность: ${booking.duration} минут</p>
                                        <p class="card-text">Время показа: ${booking.showtime}</p>
                                        <p class="card-text">Ряд: ${booking.row_number}, Место: ${booking.seat_number}</p>
                                    </div>
                                </div>
                            </div>
                        `);
                row.append(card);
            });
            container.append(row);
        }
    }
});
