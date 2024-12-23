<?php
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id FROM businesses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$business = $stmt->get_result()->fetch_assoc();

if (!$business) {
    die("No business found for this user");
}

$business_id = $business['id'];


$upload_dir = "../product_image";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {

        $stmt = $conn->prepare("INSERT INTO products (product_name, price, business_id, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", 
            $_POST['product_name'],
            $_POST['price'],
            $business_id,
            $_POST['description']
        );
        $stmt->execute();
        $product_id = $conn->insert_id;


        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = $product_id . '.' . $file_extension;
            $target_path = $upload_dir . '/' . $filename;
            $db_image_path = "../product_image/" . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
  
                $stmt = $conn->prepare("UPDATE products SET image=? WHERE id=?");
                $stmt->bind_param("si", $db_image_path, $product_id);
                $stmt->execute();
            }
        }
    }

    if (isset($_POST['edit_product'])) {
        $image_path = $_POST['current_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = $_POST['product_id'] . '.' . $file_extension;
            $target_path = $upload_dir . '/' . $filename;
            $db_image_path = "../product_image/" . $filename;
            
      
            if (file_exists($_POST['current_image']) && $_POST['current_image'] != $db_image_path) {
                unlink($_POST['current_image']);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = $db_image_path;
            }
        }

        $stmt = $conn->prepare("UPDATE products SET product_name=?, price=?, image=?, description=? WHERE id=? AND business_id=?");
        $stmt->bind_param("sdssii", 
            $_POST['product_name'],
            $_POST['price'],
            $image_path,
            $_POST['description'],
            $_POST['product_id'],
            $business_id
        );
        $stmt->execute();
    }

    if (isset($_POST['delete_product'])) {

        $stmt = $conn->prepare("SELECT image FROM products WHERE id=? AND business_id=?");
        $stmt->bind_param("ii", $_POST['product_id'], $business_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result && file_exists($result['image'])) {
            unlink($result['image']);
        }

     
        $stmt = $conn->prepare("DELETE FROM products WHERE id=? AND business_id=?");
        $stmt->bind_param("ii", $_POST['product_id'], $business_id);
        $stmt->execute();
    }
    
    header("Location: product.php");
    exit();
}


$stmt = $conn->prepare("SELECT * FROM products WHERE business_id = ?");
$stmt->bind_param("i", $business_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


function displayStarRating($rating) {
    $output = '';
    $rating = floatval($rating);
    

    for ($i = 1; $i <= floor($rating); $i++) {
        $output .= '<i class="fas fa-star text-warning"></i>';
    }
    

    if ($rating - floor($rating) >= 0.5) {
        $output .= '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    
    for ($i = ceil($rating); $i < 5;$i++) {
        $output .= '<i class="far fa-star text-warning"></i>';
    }
    
    return $output;
}

?>

