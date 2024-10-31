// Krok 2: Podsumowanie koszyka i formularz z danymi kontaktowymi
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
                // Debugowanie: sprawdzenie, co jest w $_POST
                echo '<pre>';
                print_r($_POST);
                echo '</pre>';

                // Check if 'items' exists and is an array
                if (isset($_POST['items']) && is_array($_POST['items']) && !empty($_POST['items'])) {
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
