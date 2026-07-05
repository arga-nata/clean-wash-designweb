<?php
header('Content-Type: application/json');

include 'includes/connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

$name = $data['customerName'] ?? '';
$phone = $data['customerPhone'] ?? '';
$address = $data['customerAddress'] ?? '';
$items = $data['items'] ?? [];
$total = (float) ($data['total'] ?? 0);
$deliveryMethod = $data['deliveryMethod'] ?? '';
$locationArea = $data['locationArea'] ?? '';
$deliveryFee = (float) ($data['deliveryFee'] ?? 0);

if (empty($name) || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Nama dan No HP wajib diisi!']);
    exit;
}

if (empty($items) || count($items) === 0) {
    echo json_encode(['success' => false, 'message' => 'Keranjang masih kosong! Silakan pilih layanan.']);
    exit;
}

if ($total <= 0) {
    echo json_encode(['success' => false, 'message' => 'Total pesanan tidak boleh 0!']);
    exit;
}

mysqli_begin_transaction($conn);

try {
    $stmt = $conn->prepare("SELECT id FROM tbl_customers WHERE customer_phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $customer = $res->fetch_assoc();
        $customer_id = $customer['id'];
    } else {
        $stmt = $conn->prepare("INSERT INTO tbl_customers (username, password, customer_name, customer_phone, customer_address) VALUES (?, ?, ?, ?, ?)");
        $temp_user = strtolower(str_replace(' ', '', $name)) . rand(10, 99);
        $temp_pass = password_hash('123456', PASSWORD_DEFAULT);
        $stmt->bind_param("sssss", $temp_user, $temp_pass, $name, $phone, $address);
        $stmt->execute();
        $customer_id = $conn->insert_id;
    }

    $stmt = $conn->prepare("INSERT INTO tbl_orders (customer_id, delivery_method, location_area, delivery_fee, total_amount, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("issdd", $customer_id, $deliveryMethod, $locationArea, $deliveryFee, $total);
    $stmt->execute();
    $order_id = $conn->insert_id;

    foreach ($items as $item) {
        $stmt_svc = $conn->prepare("SELECT id FROM tbl_services WHERE service_name = ?");
        $stmt_svc->bind_param("s", $item['name']);
        $stmt_svc->execute();
        $res_svc = $stmt_svc->get_result();
        $svc = $res_svc->fetch_assoc();
        $service_id = $svc ? $svc['id'] : 1;

        $subtotal = (float) $item['price'] * (float) $item['qty'];
        $stmt_item = $conn->prepare("INSERT INTO tbl_order_items (order_id, service_id, qty, subtotal) VALUES (?, ?, ?, ?)");
        $stmt_item->bind_param("iidd", $order_id, $service_id, $item['qty'], $subtotal);
        $stmt_item->execute();
    }

    mysqli_commit($conn);
    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

mysqli_close($conn);
?>