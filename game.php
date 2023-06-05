<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tikriname, ar buvo paspaustas mygtukas "pradėti"
    if (isset($_POST['start'])) {
        // Išsaugome žaidėjų vardus ir pradedame žaidimą
        $_SESSION['player1'] = $_POST['player1'];
        $_SESSION['player2'] = $_POST['player2'];
        $_SESSION['score1'] = 0;
        $_SESSION['score2'] = 0;
        $_SESSION['turn'] = 1; // Kieno eilė pradžioje

        // Nukreipiame į žaidimo puslapį
        header("Location: game.php");
        exit();
    }
}

// Žaidimo puslapio HTML kodas
?>

<!DOCTYPE html>
<html>
<head>
    <title>Žaidimas</title>
</head>
<body>
    <?php if (!isset($_SESSION['player1']) || !isset($_SESSION['player2'])) : ?>
        <!-- Žaidėjų vardų įvedimo forma -->
        <form method="POST" action="">
            <label>Žaidėjas 1:</label>
            <input type="text" name="player1" required>
            <br>
            <label>Žaidėjas 2:</label>
            <input type="text" name="player2" required>
            <br>
            <input type="submit" name="start" value="Pradėti">
        </form>
    <?php else : ?>
        <!-- Žaidimo vykdymo logika -->
        <h2><?php echo $_SESSION['player' . $_SESSION['turn']]; ?></h2>
        <h3>Rezultatas: <?php echo $_SESSION['score' . $_SESSION['turn']]; ?></h3>
        <h3>Viso taškų: <?php echo $_SESSION['score1'] + $_SESSION['score2']; ?></h3>

        <?php
        // Display $_SESSION array
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";

        // Tikriname, ar žaidimas nebaigėsi
        if ($_SESSION['score1'] < 30 && $_SESSION['score2'] < 30) :
        ?>
            <form method="POST" action="game.php">
                <input type="submit" name="rollDice" value="Mesti kauliuką">
            </form>

            <?php
            // Tikriname, ar buvo paspaustas mygtukas "mesti kauliuką"
            if (isset($_POST['rollDice'])) {
                // Sugeneruojame skaičių nuo 1 iki 6
                $diceValue = rand(1, 6);

                // Pridedame rezultatą prie žaidėjo taškų
                $_SESSION['score' . $_SESSION['turn']] += $diceValue;

                // Pakeičiame žaidėjo eilę
                $_SESSION['turn'] = $_SESSION['turn'] === 1 ? 2 : 1;

                // Nukreipiame į žaidimo puslapį
                header("Location: game.php");
                exit();
            }
            ?>
        <?php else : ?>
            <!-- Žaidimas baigėsi, rodomas laimėtojo pranešimas -->
            <h2>Žaidimas baigėsi!</h2>
            <h3>Laimėtojas: <?php echo $_SESSION['score1'] >= 30 ? $_SESSION['player1'] : $_SESSION['player2']; ?></h3>
            <h3>Viso taškų: <?php echo $_SESSION['score1'] + $_SESSION['score2']; ?></h3>
            <form method="POST" action="">
                <input type="submit" name="restart" value="Pradėti iš naujo">
            </form>

            <?php
            // Tikriname, ar buvo paspaustas mygtukas "Pradėti iš naujo"
            if (isset($_POST['restart'])) {
                session_unset();
                session_destroy();

                // Nukreipiame į pradinį puslapį
                header("Location: game.php");
                exit();
            }
            ?>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
