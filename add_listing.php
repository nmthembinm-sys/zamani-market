<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get seller ID
$sql = "SELECT seller_id FROM sellers WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$seller = $result->fetch_assoc();

if (!$seller) {
    die("Seller account not found.");
}

$seller_id = $seller['seller_id'];

$message = "";

if (isset($_POST['add_listing'])) {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $stock_qty = $_POST['stock_qty'];
    $category_id = $_POST['category_id'];
    $province = trim($_POST['province']);

    // 1. INSERT LISTING FIRST
    $stmt = $conn->prepare("
        INSERT INTO listings 
        (seller_id, category_id, title, description, price, stock_qty, province, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");

    $stmt->bind_param(
        "iissdis",
        $seller_id,
        $category_id,
        $title,
        $description,
        $price,
        $stock_qty,
        $province
    );

    $stmt->execute();

    $listing_id = $stmt->insert_id;

    // 2. UPLOAD IMAGES + INSERT INTO listing_images
    if (!empty($_FILES['image']['name'][0])) {

        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        foreach ($_FILES['image']['name'] as $key => $name) {

            $filename = time() . "_" . basename($name);
            $target = "uploads/" . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'][$key], $target)) {

                $is_main = ($key == 0) ? 1 : 0;

                $img_stmt = $conn->prepare("
                    INSERT INTO listing_images (listing_id, image_path, is_main)
                    VALUES (?, ?, ?)
                ");

                $img_stmt->bind_param("isi", $listing_id, $target, $is_main);
                $img_stmt->execute();
            }
        }
    }

    // 3. REDIRECT
    header("Location: seller_dashboard.php?listing_added=1");
    exit();
}
$categories = mysqli_query(
    $conn,
    "SELECT category_id, name
     FROM categories
     WHERE is_active = 1
     ORDER BY name"
);
$listings = mysqli_query($conn, "
    SELECT l.*,
    (
        SELECT image_path 
        FROM listing_images 
        WHERE listing_id = l.listing_id 
        ORDER BY is_main DESC, image_id ASC 
        LIMIT 1
    ) AS main_image
    FROM listings l
    WHERE l.seller_id = '$seller_id'
    ORDER BY l.created_at DESC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Listing</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header">
            <h3>Add Listing</h3>
        </div>

        <div class="card-body">

            <?php if(!empty($message)): ?>
                <div class="alert alert-danger">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Title</label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        required></textarea>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                     <label class="form-label">
    Province
</label>

<select name="province" class="form-select" required>

    <option value="">Select Province</option>
    <option>Eastern Cape</option>
    <option>Free State</option>
    <option>Gauteng</option>
    <option>KwaZulu-Natal</option>
    <option>Limpopo</option>
    <option>Mpumalanga</option>
    <option>North West</option>
    <option>Northern Cape</option>
    <option>Western Cape</option>

</select>
                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
    Category
</label>

<select name="category_id" class="form-select" required>

    <option value="">Select Category</option>

    <?php while($category = mysqli_fetch_assoc($categories)) { ?>

        <option value="<?php echo $category['category_id']; ?>">
            <?php echo htmlspecialchars($category['name']); ?>
        </option>

    <?php } ?>

</select>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Price (ZAR - Rands)</label>
<input
    type="number"
    name="price"
    step="0.01"
    class="form-control"
    placeholder="e.g. 150.00"
    required>
                        </div>

                    <div class="col-md-6 mb-3">
<label class="form-label">Items in stock</label>
<input
    type="number"
    name="stock_qty"
    class="form-control"
    value="1"
    required>
                        </div>

                <div class="mb-3">
    <label class="form-label">Product Images</label>

    <input
        type="file"
        name="image[]"
        class="form-control"
        multiple
        required>
            </div>

               
<button
                    type="submit"
                    name="add_listing"
                    class="btn btn-success">

                    Add Listing

                </button>

                <a href="seller_dashboard.php" class="btn btn-outline-secondary">
    Cancel
</a>

            </form>

        </div>

    </div>

</div>

</body>
</html>
