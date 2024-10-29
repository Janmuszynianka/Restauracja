<?php

$host = 'localhost';
$db = 'restauracja';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Krok 1: Wyświetlanie menu i dodawanie produktów do koszyka
if (!isset($_POST['checkout']) && !isset($_POST['confirm_order'])) {}
    ?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FastBite - Zamówienie</title>
        <link rel="stylesheet" href="stylmain.css">
    </head>
    <body>
        <header>
            <div class="logo">
                <h1>RESTAURONT</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="location.html">Lokalizacja</a></li>
                    <li><a href="kontakt.html">Kontakt</a></li>
                    <li><a href="zamow.php">Zamów</a></li>
                    <li><a href="onas.html">O nas</a></li>
                    <li><a href="platnosci.html">Płatności</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="menu">
                <h2>Zamów swoje ulubione danie</h2>
                <form method="post" action="">
                    <!-- Przystawki -->
                    <h3>Przystawki</h3>
                    <div class="menu-items">
                        <?php
                        $sql = "SELECT item_id, item_name, price, description FROM menu_items WHERE category_id = 1";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="menu-item">';
                                echo '<h4>' . htmlspecialchars($row["item_name"]) . '</h4>';
                                echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                                echo '<p>Cena: ' . htmlspecialchars($row["price"]) . ' PLN</p>';
                                echo '<label>Ilość: <input type="number" name="items[' . $row["item_id"] . ']" value="0" min="0"></label>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>

                    <!-- Napoje -->
                    <h3>Napoje</h3>
                    <div class="menu-items">
                        <?php
                        $sql = "SELECT item_id, item_name, price, description FROM menu_items WHERE category_id = 6";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="menu-item">';
                                echo '<h4>' . htmlspecialchars($row["item_name"]) . '</h4>';
                                echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                                echo '<p>Cena: ' . htmlspecialchars($row["price"]) . ' PLN</p>';
                                echo '<label>Ilość: <input type="number" name="items[' . $row["item_id"] . ']" value="0" min="0"></label>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>

                    <!-- Desery -->
                    <h3>Desery</h3>
                    <div class="menu-items">
                        <?php
                        $sql = "SELECT item_id, item_name, price, description FROM menu_items WHERE category_id = 5";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="menu-item">';
                                echo '<h4>' . htmlspecialchars($row["item_name"]) . '</h4>';
                                echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                                echo '<p>Cena: ' . htmlspecialchars($row["price"]) . ' PLN</p>';
                                echo '<label>Ilość: <input type="number" name="items[' . $row["item_id"] . ']" value="0" min="0"></label>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>

                    <button type="submit" name="checkout">Złóż zamówienie</button>
                </form>
            </section>
        </main>

        <footer>
            <p>&copy; 2024 RESTAURONT. Wszelkie prawa zastrzeżone.</p>
        </footer>
    </body>
    </html>