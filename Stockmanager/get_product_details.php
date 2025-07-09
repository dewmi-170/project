<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product):
        ?>
        <ul class="list-group">
            <li class="list-group-item"><strong>Name:</strong> <?= $product['product_name'] ?></li>
            <li class="list-group-item"><strong>Category:</strong> <?= $product['category'] ?></li>
            <li class="list-group-item"><strong>Price:</strong> Rs. <?= number_format($product['price'], 2) ?></li>
            <li class="list-group-item"><strong>Stock Qty:</strong> <?= $product['stock_qty'] ?></li>
            <li class="list-group-item"><strong>Status:</strong> <?= $product['status'] ?></li>
        </ul>
        <?php
    else:
        echo "Product not found.";
    endif;
}
?>
