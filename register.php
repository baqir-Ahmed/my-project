<?php include 'db.php' ;?>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"], $_POST["email"], $_POST["tour_id"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $tour_id = $_POST["tour_id"];

    $stmt_tour_check = $conn->prepare("SELECT name FROM tournament WHERE tour_id = ?");
    $stmt_tour_check->bind_param("i", $tour_id);
    $stmt_tour_check->execute();
    $stmt_tour_check->store_result();

    if ($stmt_tour_check->num_rows === 0) {
        echo "<h3>That tournament doesn't exist, Maybe in another universe(;</h3>";
    } else {
        $stmt_user = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $stmt_user->store_result();

        if ($stmt_user->num_rows === 0) {
            $stmt_user->close();
            $stmt_insert = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            $stmt_insert->bind_param("ss", $name, $email);
            $stmt_insert->execute();
            $user_id = $stmt_insert->insert_id;
            $stmt_insert->close();
        } else {
            $stmt_user->bind_result($user_id);
            $stmt_user->fetch();
            $stmt_user->close();
        }

        $stmt_check = $conn->prepare("SELECT id FROM user_tournament WHERE user_id = ? AND tour_id = ?");
        $stmt_check->bind_param("ii", $user_id, $tour_id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows === 0) {
            $stmt_register = $conn->prepare("INSERT INTO user_tournament (user_id, tour_id) VALUES (?, ?)");
            $stmt_register->bind_param("ii", $user_id, $tour_id);
            $stmt_register->execute();
            echo "<div class='message'><h2>You have successfully registered for the tournament. Thank you :)</h2></div>";
        } else {
            echo "<div class='message1'><h2>Nice try, gamer! But you have already joined this tournament. This isn't a respawn point!</h2></div>";
        }
        $stmt_check->close();
    }
    $stmt_tour_check->close();
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<h2>Register</h2>
<form method="post">
    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="number" name="tour_id" placeholder="Tourn#" required><br><br>
    <button type="submit">SEND</button>
</form>
<?php } ?>
</body>
</html>