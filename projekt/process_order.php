<?php
// Połączenie z bazą danych
$host = 'localhost';
$db = 'restauracja';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Krok 1: Wyświetlanie menu i dodawanie produktów do koszyka
if (!isset($_POST['checkout']) && !isset($_POST['confirm_order'])) {
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
    <?php
}

// Krok 2: Podsumowanie koszyka i formularz z danymi kontaktowymi
if (isset($_POST['checkout'])) {
    ?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FastBite - Potwierdzenie zamówienia</title>
        <link rel="stylesheet" href="stylmain.css">
    </head>
    <body>
        <header>
            <div class="logo">
                <h1>RESTAURONT</h1>
            </div>
        </header>

        <main>
            <section class="order-summary">
                <h2>Podsumowanie zamówienia</h2>
                <form method="post" action="process_order.php">
                    <ul>
                    <?php
                    $total_amount = 0;

                    // Check if 'items' exists and is an array
                    if (isset($_POST['items']) && is_array($_POST['items'])) {
                        foreach ($_POST['items'] as $item_id => $quantity) {
                            if ($quantity > 0) {
                                $sql_item = "SELECT item_name, price FROM menu_items WHERE item_id = $item_id";
                                $result = $conn->query($sql_item);
                                if ($result) {
                                    $row = $result->fetch_assoc();

                                    $item_name = $row['item_name'];
                                    $price = $row['price'];
                                    $total_price = $price * $quantity;

                                    $total_amount += $total_price;

                                    echo "<li>$item_name x $quantity - $total_price PLN</li>";

                                    // Ukryte pole do przekazania danych do kolejnego kroku
                                    echo "<input type='hidden' name='items[$item_id]' value='$quantity'>";
                                }
                            }
                        }
                    } else {
                        echo "<li>Brak wybranych pozycji.</li>";
                    }
                    ?>
                    </ul>
                    <p>Łączna kwota: <?php echo $total_amount; ?> PLN</p>

                    <!-- Dane kontaktowe -->
                    <h3>Podaj dane kontaktowe</h3>
                    <label>Imię i nazwisko: <input type="text" name="customer_name" required></label><br>
                    <label>Adres: <textarea name="customer_address" required></textarea></label><br>
                    <label>Telefon: <input type="text" name="customer_phone" required></label><br>
                    
                    <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">

                    <button type="submit" name="confirm_order">Potwierdź zamówienie</button>
                </form>
            </section>
        </main>

        <footer>
            <p>&copy; 2024 RESTAURONT. Wszelkie prawa zastrzeżone.</p>
        </footer>

    </body>
    </html>
    <?php
}


// Krok 3: Płatności i potwierdzenie zamówienia
// Krok 3: Płatności i potwierdzenie zamówienia
if (isset($_POST['confirm_order'])) {
    $customer_name = $_POST['customer_name'];
    $customer_address = $_POST['customer_address'];
    $customer_phone = $_POST['customer_phone'];
    $total_amount = $_POST['total_amount'];

    // Sprawdzenie, czy tablica 'items' istnieje
    if (isset($_POST['items']) && is_array($_POST['items'])) {
        // Zapis zamówienia do bazy danych
        $sql_order = "INSERT INTO orders (customer_name, customer_address, total_amount) VALUES ('$customer_name', '$customer_address', $total_amount)";
        if ($conn->query($sql_order) === TRUE) {
            $order_id = $conn->insert_id;

            // Zapis pozycji zamówienia
            foreach ($_POST['items'] as $item_id => $quantity) {
                if ($quantity > 0) {
                    $sql_item = "SELECT price FROM menu_items WHERE item_id = $item_id";
                    $result = $conn->query($sql_item);
                    $row = $result->fetch_assoc();
                    $price = $row['price'];

                    $sql_order_item = "INSERT INTO order_items (order_id, item_id, quantity, price) VALUES ($order_id, $item_id, $quantity, $price)";
                    $conn->query($sql_order_item);
                }
            }

// Przekazywanie danych do Tpay
$tpay_id = '401101'; // Twój identyfikator Tpay
$tpay_key = '95a22cd881b77e550c546d8cd737c9af'; // Twój klucz MD5
$tpay_amount = number_format($total_amount, 2, '.', ''); // Kwota do zapłaty
$tpay_description = 'Zamówienie #' . $order_id; // Opis zamówienia

// Obliczenie MD5
$md5sum = md5($tpay_id . $tpay_amount . $tpay_description . $tpay_key);

// Wyświetlenie formularza płatności
echo '
<form action="https://secure.sandbox.tpay.com/" method="post" name="payment">
    <input name="kwota" value="' . $tpay_amount . '" type="hidden">
    <input name="opis" value="' . htmlspecialchars($tpay_description) . '" type="hidden">
    <input name="kanal" value="68" type="hidden">
    <input name="jezyk" value="PL" type="hidden">
    <input name="id" value="' . $tpay_id . '" type="hidden">
    <input name="md5sum" value="' . $md5sum . '" type="hidden"> <!-- Użycie obliczonej sumy kontrolnej -->
    <input type="submit" value="Zapłać">
</form>
';

            // Informacja dla użytkownika
            echo "<p>Zamówienie zostało złożone! Łączna kwota: $total_amount PLN</p>";
            echo "<p>Dziękujemy za zamówienie, $customer_name!</p>";
        } else {
            echo "<p>Błąd przy składaniu zamówienia: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>Błąd: Nie wybrano żadnych produktów.</p>";
    }
}


$conn->close();
