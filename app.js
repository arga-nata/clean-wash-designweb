// fungsi untuk menambahkan class active pada link yang sesuai dengan halaman saat ini
const links = document.querySelectorAll(".nav-links a");

// ambil nama file aktif
let currentPage = window.location.pathname.split("/").pop();

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
window.onload = () => {
  document.querySelector(".page").classList.add("show");
};

document.querySelectorAll("a").forEach((link) => {
  link.addEventListener("click", function (e) {
    const href = this.getAttribute("href");

    if (href && !href.startsWith("#")) {
      e.preventDefault();

      document.getElementById("loader").classList.add("show");
      document.querySelector(".page").classList.add("fade-out");

      setTimeout(() => {
        window.location.href = href;
      }, 650);
    }
  });
});

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

  if (totalElement)
    totalElement.innerText = `Rp ${total.toLocaleString("id-ID")}`;
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

window.kirimWhatsApp = function () {
  const name = document.getElementById("custName").value;
  const phone = document.getElementById("custPhone").value;
  const address = document.getElementById("custAddress").value;
  const total = document.getElementById("totalAmount").innerText;
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
  window.open(`https://wa.me/6281234567890?text=${encodedMsg}`, "_blank");
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
