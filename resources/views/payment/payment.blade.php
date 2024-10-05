<form action="" method="POST">
    @csrf
    <input type="text" name="card_number" placeholder="Card Number" required>
    <input type="text" name="expiry_date" placeholder="MM/YY" required>
    <input type="text" name="cvv" placeholder="CVV" required>
    <button type="submit">Pay Now</button>
</form>
