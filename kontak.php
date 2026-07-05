<?php
session_start();
$host = "db";
$user = "db";
$pass = "db";
$db = "db";
$conn = mysqli_connect($host, $user, $pass, $db);

$full_name = "";
if (isset($_SESSION['customer_id'])) {
    $cid = $_SESSION['customer_id'];
    $query = "SELECT customer_name FROM tbl_customers WHERE id = '$cid'";
    $res = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($res)) {
        $full_name = $row['customer_name'];
    }
}

include 'includes/header.php';
?>

<div class="contact-page-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="text-center mb-5">
                    <h2 class="fw-bold" style="color: #333; font-size: 2rem;">Hubungi Kami</h2>
                    <p class="text-muted">Silakan datang langsung ke lokasi kami atau kirim pesan melalui kontak
                        informasi di bawah.</p>
                </div>

                <div class="contact-wrapper">
                    <div class="contact-card">
                        <div class="card-body-custom">
                            <div class="contact-item">
                                <h4>Alamat</h4>
                                <p>Jl. Melati No. 12, Kota Blitar, Jawa Timur</p>
                            </div>

                            <div class="contact-item">
                                <h4>No. WhatsApp</h4>
                                <p>0812-3456-7890</p>
                            </div>

                            <div class="contact-item">
                                <h4>Email</h4>
                                <p>cleanwashlaundry@gmail.com</p>
                            </div>

                            <div class="contact-item">
                                <h4>Jam Operasional</h4>
                                <p>Senin - Sabtu : 08.00 - 20.00 WIB</p>
                                <p>Minggu : 09.00 - 17.00 WIB</p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="card-body-custom">
                            <form id="whatsapp-contact-form">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" id="contact-name" placeholder="Masukkan nama lengkap..."
                                        value="<?php echo $full_name; ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" id="contact-email" placeholder="nama@email.com" required />
                                </div>
                                <div class="form-group">
                                    <label>Pesan</label>
                                    <textarea id="contact-message" rows="4" placeholder="Apa yang bisa kami bantu?"
                                        class="capitalize-input" required></textarea>
                                </div>
                                <button type="submit" class="btn-send">Kirim via WhatsApp</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .contact-page-wrapper {
        background: linear-gradient(135deg, #e0f2f1 0%, #f1f8e9 100%);
        min-height: 100vh;
        padding-bottom: 50px;
    }

    .contact-wrapper {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .contact-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        flex: 1;
        min-width: 320px;
        max-width: 500px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .card-body-custom {
        padding: 30px;
    }

    .contact-item {
        margin-bottom: 0;
        padding: 20px 0;
        border-bottom: 1px solid #eee;
    }

    .contact-item:last-child {
        border-bottom: none;
    }

    .contact-item h4 {
        color: #666;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .contact-item p {
        color: #333;
        font-size: 1rem;
        margin: 0;
        font-weight: 500;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: #333;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
    }

    .btn-send {
        width: 100%;
        padding: 14px;
        background-color: #49b1c8;
        color: white;
        border: none;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
        font-size: 1rem;
    }

    .btn-send:hover {
        background-color: #3d92a4;
    }

    .btn-send:active {
        background-color: #2f7586;
    }

    .capitalize-input {
        text-transform: capitalize;
    }
</style>

<?php include 'includes/footer.php'; ?>