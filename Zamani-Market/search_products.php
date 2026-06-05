<?php
include("config.php");

$query = $_GET['query'] ?? '';
$query = trim($query);

$results = [];

if ($query !== '') {

    $sql = "SELECT product_id, name, price, description, image 
            FROM products 
            WHERE is_active = 1 
            AND (name LIKE ? OR description LIKE ?)";

    $stmt = $conn->prepare($sql);

    $search = "%$query%";
    $stmt->bind_param("ss", $search, $search);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
}
?>

<h2>Search Results for "<?= htmlspecialchars($query) ?>"</h2>

<?php if (count($results) > 0): ?>

    <div style="display:flex; flex-wrap:wrap; gap:15px;">

        <?php foreach ($results as $product): ?>
            <div style="border:1px solid #ccc; padding:10px; width:200px;">
                
                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" 
                     style="width:100%; height:150px; object-fit:cover;">

                <h4><?= htmlspecialchars($product['name']) ?></h4>

                <p>R<?= htmlspecialchars($product['price']) ?></p>

                <p><?= htmlspecialchars($product['description']) ?></p>

            </div>
        <?php endforeach; ?>

    </div>

<?php else: ?>
    <p>No products found.</p>
<?php endif; ?>