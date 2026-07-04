// ambil nama file aktif
let currentPage = window.location.pathname.split("/").pop();

const protectedPages = ["keranjang.html", "riwayat-order.html"]; // Daftar halaman yang harus login

if (protectedPages.includes(currentPage)) {
  const customerId = localStorage.getItem("customer_id");
  if (!customerId) {
    window.location.href = "login.html";
  }
}

// --- LOGIKA KERANJANG (CART) ---
let cart = JSON.parse(localStorage.getItem("cleanwash_cart")) || [];

function updateCartUI() {
  const cartItemsContainer = document.getElementById("cart-items");
  if (!cartItemsContainer) return;

  cartItemsContainer.innerHTML = "";

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = `<div class="empty-cart">Keranjang Anda kosong.</div>`;
  } else {
    cart.forEach((item, index) => {
      const itemDiv = document.createElement("div");
      itemDiv.className = "cart-item";
      itemDiv.innerHTML = `
        <div class="item-info">
          <span class="item-name">${item.name}</span>
          <span class="item-qty">Jumlah: ${item.qty} ${item.unit}</span>
        </div>
        <div class="item-price">
          <span>Rp ${(item.price * item.qty).toLocaleString("id-ID")}</span>
          <button class="remove-btn" onclick="removeFromCart(${index})">Hapus</button>
        </div>
      `;
      cartItemsContainer.appendChild(itemDiv);
    });
  }
  calculateTotal();
}

function addToCart(name, price, unit) {
  const existingItem = cart.find((item) => item.name === name);
  if (existingItem) {
    existingItem.qty += 1;
  } else {
    cart.push({ name, price, qty: 1, unit });
  }
  saveCart();
  updateCartUI();
}

function removeFromCart(index) {
  cart.splice(index, 1);
  saveCart();
  updateCartUI();
}

function saveCart() {
  localStorage.setItem("cleanwash_cart", JSON.stringify(cart));
}

function calculateTotal() {
  const totalElement = document.getElementById("total-amount");
  const deliveryElement = document.getElementById("delivery-fee");
  const hiddenTotal = document.getElementById("hiddenTotalAmount");
  const hiddenDelivery = document.getElementById("hiddenDeliveryFee");
  const areaSelect = document.getElementById("area-pickup");
  const methodSelect = document.getElementsByName("pickup-method");

  if (!totalElement) return;

  let subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  let deliveryFee = 0;

  if (methodSelect && methodSelect.length > 0) {
    const selectedMethod = Array.from(methodSelect).find(
      (r) => r.checked,
    )?.value;
    if (selectedMethod === "kurir") {
      if (areaSelect) {
        const area = areaSelect.value;
        if (area === "kota") deliveryFee = 5000;
        else if (area === "kabupaten") deliveryFee = 15000;
        else if (area === "luar_kota") deliveryFee = 25000;
      }
    }
  }

  const total = subtotal + deliveryFee;

  totalElement.innerText = `Rp ${total.toLocaleString("id-ID")}`;
  if (deliveryElement) {
    deliveryElement.innerText = `Rp ${deliveryFee.toLocaleString("id-ID")}`;
  }
  if (hiddenTotal) {
    hiddenTotal.value = total;
  }
  if (hiddenDelivery) {
    hiddenDelivery.value = deliveryFee;
  }
}

// --- LOGIKA FORM PESANAN (KERANJANG) ---
const orderForm = document.getElementById("order-form");
if (orderForm) {
  orderForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(orderForm);
    const submitBtn = orderForm.querySelector("button[type='submit']");
    const originalBtnText = submitBtn.innerText;

    submitBtn.disabled = true;
    submitBtn.innerText = "Memproses...";

    try {
      const response = await fetch("keranjang.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();

      if (result.status === "success") {
        submitBtn.style.backgroundColor = "#22c55e";
        submitBtn.innerText = "Berhasil!";
        localStorage.removeItem("cleanwash_cart");
        setTimeout(() => {
          window.location.href = "riwayat-order.php";
        }, 1500);
      } else {
        alert(result.message || "Terjadi kesalahan saat membuat pesanan.");
        submitBtn.disabled = false;
        submitBtn.innerText = originalBtnText;
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Terjadi kesalahan jaringan. Silakan coba lagi.");
      submitBtn.disabled = false;
      submitBtn.innerText = originalBtnText;
    }
  });
}

// --- LOGIKA FORM KONTAK (WHATSAPP) ---
const whatsappForm = document.getElementById("whatsapp-contact-form");
if (whatsappForm) {
  whatsappForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("contact-name").value;
    const email = document.getElementById("contact-email").value;
    const rawMessage = document.getElementById("contact-message").value;
    const message = rawMessage
      .toLowerCase()
      .split(" ")
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join(" ");

    // Format pesan untuk WhatsApp
    const waText = `Halo CleanWash Laundry, saya ingin bertanya:\n\n*Nama:* ${name}\n*Email:* ${email}\n*Pesan:* ${message}`;
    const encodedText = encodeURIComponent(waText);
    const phoneNumber = "6281234567890"; // Ganti dengan nomor WA admin

    window.open(`https://wa.me/${phoneNumber}?text=${encodedText}`, "_blank");
  });
}

// Listener untuk update total secara real-time
document.addEventListener("input", () => {
  calculateTotal();
});

// Inisialisasi awal
updateCartUI();
