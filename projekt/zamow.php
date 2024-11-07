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
if (!isset($_POST['checkout']) && !isset($_POST['confirm_order']) && !isset($_POST['payment'])) {
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

// Krok 2: Podsumowanie koszyka i formularz z danymi kontaktowymi oraz płatnością
elseif (isset($_POST['checkout'])) {
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
                <form method="post" action="" style="width: auto">
                    <ul>
                    <?php
                    $total_amount = 0;
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

                    <!-- Metoda płatności -->
                    <h3>Wybierz metodę płatności</h3>
                    <label><input type="radio" name="payment_method" value="gotowka" required> Gotówka przy odbiorze</label><br>
                    <label><input type="radio" name="payment_method" value="karta" required> Karta kredytowa</label><br>
                    <label><input type="radio" name="payment_method" value="blik" required> BLIK</label><br>
                    <h3>Wprowadź kod Blik</h3>
<label>Kod BLIK: <input type="text" name="blik_code" id="blik_code" pattern="\d{6}" maxlength="6" placeholder="123456" required></label><br>
<p id="blik-validation-message"></p>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const blikCodeInput = document.getElementById('blik_code');
    const blikValidationMessage = document.getElementById('blik-validation-message');

    blikCodeInput.addEventListener('input', function () {
        const blikCode = blikCodeInput.value;

        // Sprawdzanie, czy kod ma dokładnie 6 cyfr
        if (blikCode.length === 6 && /^\d{6}$/.test(blikCode)) {
            blikValidationMessage.textContent = "Kod BLIK jest prawidłowy";
            blikValidationMessage.style.color = "green";
        } else {
            blikValidationMessage.textContent = "Kod BLIK musi zawierać 6 cyfr";
            blikValidationMessage.style.color = "red";
        }
    });
});
</script>

                    <!-- Dane karty kredytowej (opcjonalnie wyświetlane, jeśli wybrano "karta") -->
                    <div id="card-info" style="display: none;">
                        <label>Numer karty: <input type="text" name="card_number" pattern="\d{16}" maxlength="16" placeholder="0000 0000 0000 0000"></label><br>
                        <label>Data ważności: <input type="text" name="expiry_date" placeholder="MM/RR" pattern="\d{2}/\d{2}"></label><br>
                        <label>CVV: <input type="text" name="cvv" pattern="\d{3}" maxlength="3" placeholder="123"></label><br>
                    </div>

                    <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                    <button type="submit" name="confirm_order">Potwierdź zamówienie</button>
                </form>
            </section>
        </main>
        <footer>
            <p>&copy; 2024 RESTAURONT. Wszelkie prawa zastrzeżone.</p>
        </footer>

        <script>
        // Pokazanie danych karty, jeśli wybrano płatność kartą
        document.querySelectorAll('input[name="payment_method"]').forEach((element) => {
            element.addEventListener('change', function() {
                document.getElementById('card-info').style.display = this.value === 'karta' ? 'block' : 'none';
            });
        });
        </script>
    </body>
    </html>
    <?php
}
// Krok 3: Strona z podziękowaniem za zamówienie
elseif (isset($_POST['confirm_order'])) {
    ?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FastBite - Dziękujemy za zamówienie</title>
        <link rel="stylesheet" href="stylmain.css">
        <meta http-equiv="refresh" content="5;url=index.html">
    </head>
    <body>
        <header>
            <div class="logo">
                <h1>RESTAURONT</h1>
            </div>
        </header>
        <main>
            <section class="thank-you">
                <h2>Dziękujemy za złożenie zamówienia!</h2>
                <p>Twoje zamówienie zostało pomyślnie złożone i jest w trakcie realizacji.</p>
                <p>Szacowany czas oczekiwania na zamówienie: <span id="time-remaining">20:00</span> minut.</p>
                <p>Za chwilę nastąpi przekierowanie na stronę główną...</p>
            </section>
        </main>
        <footer>
            <p>&copy; 2024 RESTAURONT. Wszelkie prawa zastrzeżone.</p>
        </footer>

        <script>
            // Licznik czasu oczekiwania (20 minut)
            let minutes = 20;
            let seconds = 0;

            function updateTimer() {
                const timerElement = document.getElementById('time-remaining');
                
                if (seconds === 0 && minutes > 0) {
                    minutes--;
                    seconds = 59;
                } else if (seconds > 0) {
                    seconds--;
                }

                // Wyświetlanie czasu w formacie MM:SS
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Ustawienie zakończenia odliczania na zero
                if (minutes === 0 && seconds === 0) {
                    clearInterval(countdownInterval);
                }
            }

            // Aktualizacja licznika co sekundę
            const countdownInterval = setInterval(updateTimer, 1000);
        </script>
    </body>
    </html>
    <?php
}
?>
