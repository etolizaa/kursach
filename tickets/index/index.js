$(document).ready(function() {
    loadMovies();
    loadCinemas();
    setInterval(loadMovies, 5000);

    function loadMovies() {
        $.ajax({
            url: 'index_controller.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateMoviesRow(response.data);
                } else {
                    alert(response.error);
                }
            },
            error: function() {
                alert('Ошибка при загрузке фильмов.');
            }
        });
    }

    function updateMoviesRow(movies) {
        var moviesRow = $('#movies-row');
        moviesRow.empty();
        $.each(movies, function(index, movie) {
            var movieCard = $(`
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <img src="${movie.image}" class="card-img-top" alt="${movie.title}" style="height: 350px; object-fit: fill;">
                                <div class="card-body">
                                    <h5 class="card-title">${movie.title}</h5>
                                    <p class="card-text">Длительность: ${movie.duration} минут</p>
                                    <p class="card-text">Время показа</p>
                                    <p class="card-text">${movie.showtime}</p>
                                    <a href="movie_details.php?movie_id=${movie.id}&cinema_id=1" class="btn btn-primary">Подробнее</a>
                                </div>
                            </div>
                        </div>
                    `);
            moviesRow.append(movieCard);
        });
    }

    ymaps.ready(initMap);

    function initMap() {
        var map = new ymaps.Map("map", {
            center: [59.9342802, 30.3350986],
            zoom: 14,
            controls: []
        });
        map.setBounds([
            [59.8500, 30.1500],
            [60.0000, 30.4500]
        ]);

        var locations = [{
            coords: [59.941367, 30.467746],
            name: 'Синема'
        }
        ];

        locations.forEach(function(loc) {
            var placemark = new ymaps.Placemark(loc.coords, {
                hintContent: loc.name
            });
            map.geoObjects.add(placemark);
        });
    }

    function loadCinemas() {
        $.ajax({
            url: 'cinema_controller.php', // Путь к вашему PHP-обработчику
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateCinemasRow(response.data);
                } else {
                    alert(response.error);
                }
            },
            error: function() {
                alert('Ошибка при загрузке кинотеатров.');
            }
        });
    }

    function updateCinemasRow(cinemas) {
        var row = $('#cinemas-row');
        row.empty();

        $.each(cinemas, function(index, cinema) {
            var cinemaCard = $(`
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${cinema.name}</h5>
                            <p class="card-text">${cinema.address}</p>
                            <p class="card-text">круглосуточно</p>
                        </div>
                    </div>
                </div>
            `);
            row.append(cinemaCard);
        });
    }
});
