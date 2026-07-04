<?php include 'includes/header.php'; ?>

<style>
    .price-section {
        padding: 60px 20px;
        background-color: #ffffff;
        flex: 1;
    }

    .section-titles {
        text-align: center;
        margin-bottom: 50px;
        color: #333;
        font-size: 1.2rem;
    }

    .price-table-wrapper {
        overflow-x: auto;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin: 0 auto;
        max-width: 1000px;
    }

    .price-table {
        width: 100%;
        min-width: 700px;
        border-collapse: collapse;
    }

    .price-table thead {
        background-color: rgba(85, 189, 212, 0.9);
        color: white;
    }

    .price-table th,
    .price-table td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #e5e7eb;
    }

    .price-table tbody tr:hover {
        background-color: #f9fafb;
    }
</style>

<div class="page" style="display: flex; flex-direction: column; min-height: 100vh; margin: 0; padding: 0;">
    <div class="price-section">
        <div class="container">
            <div class="section-titles">
                <h2>Harga Layanan Laundry</h2>
                <p>
                    Kami menyediakan harga yang terjangkau dengan kualitas pelayanan
                    yang tetap terjaga.
                </p>
            </div>

            <div class="price-table-wrapper">
                <table class="price-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Layanan</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Estimasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $host = "db";
                        $user = "db";
                        $pass = "db";
                        $db = "db";
                        $conn = mysqli_connect($host, $user, $pass, $db);

                        if (!$conn) {
                            echo "<tr><td colspan='5'>Gagal terhubung ke database</td></tr>";
                        } else {
                            $query = "SELECT * FROM tbl_services";
                            $result = mysqli_query($conn, $query);
                            $no = 1;

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . $row['service_name'] . "</td>";
                                    echo "<td>Per " . $row['unit'] . "</td>";
                                    echo "<td>Rp " . number_format($row['price'], 0, ',', '.') . "</td>";
                                    echo "<td>" . $row['estimate'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada data harga tersedia.</td></tr>";
                            }
                            mysqli_close($conn);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>