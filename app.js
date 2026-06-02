// ambil nama file aktif
let currentPage = window.location.pathname.split("/").pop();

const protectedPages = ["keranjang.html", "riwayat-order.html"]; // Daftar halaman yang harus login

if (protectedPages.includes(currentPage)) {
  const isLoggedIn = localStorage.getItem("isLoggedIn");
  if (isLoggedIn !== "true") {
    alert("Silakan login terlebih dahulu untuk mengakses halaman ini.");
    window.location.href = "login.html";
  }
}

// fungsi untuk menambahkan class active pada link yang sesuai dengan halaman saat ini
const links = document.querySelectorAll(".nav-links a");

// kalau index (kadang kosong)
if (currentPage === "") {
  currentPage = "index.html";
}

links.forEach((link) => {
  let linkPage = link.getAttribute("href");

  if (linkPage === currentPage) {
    link.classList.add("active");
  }
});

// fungsi scroll ke layanan
function scrollLayanan() {
  document.getElementById("layanan").scrollIntoView({
    behavior: "smooth",
  });
}

// fungsi scroll ke layanan dengan animasi munculnya card
const cards = document.querySelectorAll(".service-card");

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        // animasi delay biar muncul satu-satu
        setTimeout(() => {
          entry.target.classList.add("show");
        }, index * 300);
      }
    });
  },
  {
    threshold: 0.3,
  },
);

cards.forEach((card) => {
  observer.observe(card);
});

const galleryCards = document.querySelectorAll(".gallery-card");

const galleryObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("show");
      }
    });
  },
  {
    threshold: 0.2,
  },
);

galleryCards.forEach((card, index) => {
  card.style.transitionDelay = `${index * 0.5}s`;
  galleryObserver.observe(card);
});

// fungsi untuk menampilkan loader saat pindah halaman
// window.onload = () => {
//   document.querySelector(".page").classList.add("show");
// };

// document.querySelectorAll("a").forEach((link) => {
//   link.addEventListener("click", function (e) {
//     const href = this.getAttribute("href");

//     if (href && !href.startsWith("#")) {
//       e.preventDefault();

//       document.getElementById("loader").classList.add("show");
//       document.querySelector(".page").classList.add("fade-out");

//       setTimeout(() => {
//         window.location.href = href;
//       }, 650);
//     }
//   });
// });

// FITUR KERANJANG PEMESANAN

let cart = JSON.parse(localStorage.getItem("cleanwash_cart")) || [];

window.updateCartUI = function () {
  const cartList = document.getElementById("cartList");
  const totalElement = document.getElementById("totalAmount");
  const estimateElement = document.getElementById("totalEstimate");

  if (!cartList) return;

  cartList.innerHTML = "";
  let total = 0;
  let maxDays = 0;

  if (cart.length === 0) {
    cartList.innerHTML = `<p style="text-align:center; color:#666; padding:20px;">Keranjang masih kosong.</p>`;
  } else {
    cart.forEach((item, index) => {
      const subtotal = item.price * item.qty;
      total += subtotal;

      const dayMatch = item.estimate.match(/(\d+)/);
      const days = dayMatch ? parseInt(dayMatch[1]) : 0;
      if (days > maxDays) maxDays = days;

      const itemRow = document.createElement("div");
      itemRow.className = "cart-item";
      itemRow.innerHTML = `
        <div class="item-info">
          <span class="item-name">${item.name}</span>
          <span class="item-qty">${item.qty} ${item.unit} x Rp ${item.price.toLocaleString("id-ID")} (${item.estimate})</span>
        </div>
        <div class="item-right">
          <span class="item-price">Rp ${subtotal.toLocaleString("id-ID")}</span>
          <button class="btn-del" onclick="removeFromCart(${index})">&times;</button>
        </div>
      `;
      cartList.appendChild(itemRow);
    });
  }

  calculateTotal();
  if (estimateElement) {
    const estText = maxDays === 0 ? "0 Hari" : `${maxDays} Hari`;
    estimateElement.innerText = estText;
  }

  validateForm();
};

window.addToCart = function (name, price, unit, estimate, qty) {
  if (qty <= 0 || isNaN(qty)) {
    alert("Mohon masukkan jumlah pesanan yang valid.");
    return;
  }

  const existingIndex = cart.findIndex((item) => item.name === name);
  if (existingIndex !== -1) {
    cart[existingIndex].qty += qty;
  } else {
    cart.push({ name, price, unit, estimate, qty });
  }

  localStorage.setItem("cleanwash_cart", JSON.stringify(cart));
  updateCartUI();
};

window.removeFromCart = function (index) {
  cart.splice(index, 1);
  localStorage.setItem("cleanwash_cart", JSON.stringify(cart));
  updateCartUI();
};

window.validateForm = function () {
  const name = document.getElementById("custName");
  const phone = document.getElementById("custPhone");
  const address = document.getElementById("custAddress");
  const whatsappBtn = document.getElementById("btnWhatsApp");

  if (!name || !phone || !address || !whatsappBtn) return;

  const isFormValid =
    name.value.trim() !== "" &&
    phone.value.trim() !== "" &&
    address.value.trim() !== "" &&
    cart.length > 0;

  whatsappBtn.disabled = !isFormValid;
};

window.calculateTotal = function () {
  let subtotal = 0;
  cart.forEach((item) => {
    subtotal += item.price * item.qty;
  });

  const deliveryMethod =
    document.querySelector('input[name="deliveryMethod"]:checked')?.value ||
    "Ambil Sendiri";
  const locationArea = document.getElementById("locationArea")?.value;
  let deliveryFee = 0;

  if (deliveryMethod === "Kurir Jemput") {
    if (locationArea === "kota") {
      deliveryFee = 5000;
    } else if (locationArea === "kabupaten") {
      deliveryFee = 10000;
    } else {
      deliveryFee = 20000;
    }
  }

  const finalTotal = subtotal + deliveryFee;

  const subtotalEl = document.getElementById("subtotalAmount");
  const deliveryEl = document.getElementById("deliveryFeeAmount");
  const totalEl = document.getElementById("totalAmount");

  if (subtotalEl)
    subtotalEl.innerText = `Rp ${subtotal.toLocaleString("id-ID")}`;
  if (deliveryEl)
    deliveryEl.innerText = `Rp ${deliveryFee.toLocaleString("id-ID")}`;
  if (totalEl) totalEl.innerText = `Rp ${finalTotal.toLocaleString("id-ID")}`;

  return { finalTotal, deliveryFee, deliveryMethod };
};

document.addEventListener("input", (e) => {
  if (e.target.id === "custAddress") {
    calculateTotal();
  }
});

window.kirimWhatsApp = function () {
  const name = document.getElementById("custName").value;
  const phone = document.getElementById("custPhone").value;
  const address = document.getElementById("custAddress").value;
  let totalHargaKalkulator = 0;
  cart.forEach((item) => {
    totalHargaKalkulator += item.price * item.qty;
  });
  const { finalTotal, deliveryFee, deliveryMethod } = calculateTotal();
  const total = `Rp ${finalTotal.toLocaleString("id-ID")}`;
  const estimate = document.getElementById("totalEstimate").innerText;

  let orderDetails = "";
  cart.forEach((item, index) => {
    const subtotal = item.price * item.qty;
    orderDetails += `${index + 1}. ${item.name} (${item.qty} ${item.unit}) - Rp ${subtotal.toLocaleString("id-ID")}\n`;
  });

  const message =
    `Halo CleanWash Laundry, saya ingin melakukan pemesanan layanan laundry.\n\n` +
    `*Data Pelanggan:*\n` +
    `Nama: ${name}\n` +
    `No. HP: ${phone}\n` +
    `Alamat: ${address}\n\n` +
    `*Rincian Pesanan:*\n${orderDetails}\n` +
    `*Total Bayar:* ${total}\n` +
    `*Estimasi Selesai:* ${estimate}\n\n` +
    `Mohon konfirmasinya, terima kasih.`;

  const encodedMsg = encodeURIComponent(message);
  const semuaPesanan =
    JSON.parse(localStorage.getItem("cleanwash_orders")) || [];
  semuaPesanan.push({
    id: Math.floor(Math.random() * 1000),
    customerName: name,
    customerPhone: phone,
    customerAddress: address,
    items: cart,
    status: "Pending",
    total: total,
    date: new Date().toLocaleDateString(),
  });
  localStorage.setItem("cleanwash_orders", JSON.stringify(semuaPesanan));
  window.location.href = "riwayat-order.html";
};

document.addEventListener("input", (e) => {
  if (e.target.matches("input, select, textarea")) {
    validateForm();
  }
});

// Jalankan update UI saat pertama kali load
updateCartUI();

window.capitalizeInput = function (element) {
  let cursorPosition = element.selectionStart;
  let value = element.value;

  // Mengubah setiap awal kata menjadi huruf besar
  let capitalized = value
    .split(" ")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join(" ");

  element.value = capitalized;
  element.setSelectionRange(cursorPosition, cursorPosition);
};
