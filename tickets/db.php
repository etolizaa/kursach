<?php
function dbConnect()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ticketmov";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function userExists($login)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}

function registerUser($login, $password)
{
    $conn = dbConnect();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $login, $hashed_password);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function loginUser($login, $password)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $stmt->close();
            $conn->close();
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["login"] = $user["login"];
            return true;
        }
    }
    $stmt->close();
    $conn->close();
    return false;
}

function isAdmin()
{
    $userId = $_SESSION["user_id"];
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $isAdmin = $result->fetch_assoc()['admin'];
    $stmt->close();
    $conn->close();
    return $isAdmin;
}

function checkAuth()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function checkNotAuth()
{
    if (isset($_SESSION["user_id"])) {
        header("Location: ../index/index.php");
        exit;
    }
}

function checkAdmin()
{
    if (!isAdmin()) {
        header("Location: ../index/index.php");
        exit;
    }
}

function getActiveMovies()
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM movies WHERE showtime > NOW() ORDER BY showtime ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $movies = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $movies;
}

function createMovie($title, $description, $image, $duration, $showtime)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("INSERT INTO movies (title, description, image, duration, showtime) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $description, $image, $duration, $showtime);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function getMovie($movieId)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM movies WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        $conn->close();
        return $row;
    }
    $stmt->close();
    $conn->close();
    return null;
}

function getSeatsByMovie($movieId)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM seats WHERE movie_id = ?");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    $seats = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $seats;
}

function deleteMovie($movieId)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $success = $stmt->affected_rows > 0;
    $stmt->close();
    $conn->close();
    return $success;
}

function isUserHasSeat($userId, $movieId)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT id FROM seats WHERE user_id = ? AND movie_id = ?");
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    $hasSeat = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $hasSeat;
}

function isSeatReserved($movieId, $rowNumber, $seatNumber)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT id FROM seats WHERE movie_id = ? AND row_number = ? AND seat_number = ? AND user_id IS NOT NULL LIMIT 1");
    $stmt->bind_param("iii", $movieId, $rowNumber, $seatNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $isReserved = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $isReserved;
}

function reserveSeat($movieId, $userId, $rowNumber, $seatNumber)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("INSERT INTO seats (movie_id, row_number, seat_number, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $movieId, $rowNumber, $seatNumber, $userId);
    $stmt->execute();
    $success = $stmt->affected_rows > 0;
    $stmt->close();
    $conn->close();
    return $success;
}

function getUserSeats($userId)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("
        SELECT s.row_number, s.seat_number, m.title, m.duration, m.showtime, m.image
        FROM seats s
        JOIN movies m ON s.movie_id = m.id
        WHERE s.user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $seats = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $seats;
}
function createCinema($name, $address, $imagePath)
{
    $conn = dbConnect();
    $query = "INSERT INTO cinemas (name, address, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("sss", $name, $address, $imagePath);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
        exit;
    }

    $stmt->close();
}
function getCinemas() {
    $conn = dbConnect();
    $result = $conn->query("SELECT * FROM cinemas");
    $cinemas = [];
    while ($row = $result->fetch_assoc()) {
        $cinemas[] = $row;
    }
    $conn->close();
    return $cinemas;
}
