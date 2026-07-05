let cart = JSON.parse(localStorage.getItem("cleanwash_cart")) || [];

function updateCartUI() {
  const cartItemsContainer = document.getElementById("cartList");
  if (!cartItemsContainer) return;

  cartItemsContainer.innerHTML = "";

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = `<div class="empty-cart" style="text-align:center; padding: 20px; color: #888;">Keranjang Anda kosong.</div>`;
  } else {
    cart.forEach((item, index) => {
      const itemDiv = document.createElement("div");
      itemDiv.className = "cart-item";

      itemDiv.innerHTML = `
        <div class="item-info">
          <span class="item-name" style="display:block; font-weight:600;">${item.name}</span>
          <span class="item-qty" style="font-size:0.8rem; color:#666;">Jumlah: ${item.qty} ${item.unit}</span>
        </div>
        <div class="item-price" style="display:flex; align-items:center; gap:10px;">
          <span style="font-weight:600;">Rp ${(item.price * item.qty).toLocaleString("id-ID")}</span>
          <button class="btn-del" onclick="removeFromCart(${index})">✕</button>
        </div>
      `;
      cartItemsContainer.appendChild(itemDiv);
    });
  }
  calculateTotal();
  validateOrderForm();
}

function addToCart(name, price, unit, estimate, qty) {
  const existingItem = cart.find((item) => item.name === name);
  if (existingItem) {
    existingItem.qty += qty;
  } else {
    cart.push({ name, price, qty, unit, estimate });
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
  const totalElement = document.getElementById("totalAmount");
  const subtotalElement = document.getElementById("subtotalAmount");
  const deliveryElement = document.getElementById("deliveryFeeAmount");
  const estimateElement = document.getElementById("totalEstimate");

  const hiddenTotal = document.getElementById("hiddenTotalAmount");
  const hiddenDelivery = document.getElementById("hiddenDeliveryFee");

  const areaSelect = document.getElementById("locationArea");
  const methodRadios = document.getElementsByName("deliveryMethod");

  if (!totalElement) return;

  let subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  let deliveryFee = 0;
  let maxEstimate = 0;

  if (methodRadios && methodRadios.length > 0) {
    const selectedMethod = Array.from(methodRadios).find(
      (r) => r.checked,
    )?.value;
    if (selectedMethod === "Kurir Jemput") {
      if (areaSelect) {
        const area = areaSelect.value;
        if (area === "kota") deliveryFee = 5000;
        else if (area === "kabupaten") deliveryFee = 10000;
        else if (area === "luar") deliveryFee = 20000;
      }
    }
  }

  cart.forEach((item) => {
    const est = parseInt(item.estimate) || 0;
    if (est > maxEstimate) maxEstimate = est;
  });

  const total = subtotal + deliveryFee;

  totalElement.innerText = `Rp ${total.toLocaleString("id-ID")}`;
  if (subtotalElement)
    subtotalElement.innerText = `Rp ${subtotal.toLocaleString("id-ID")}`;
  if (deliveryElement)
    deliveryElement.innerText = `Rp ${deliveryFee.toLocaleString("id-ID")}`;
  if (estimateElement) estimateElement.innerText = `${maxEstimate} Hari`;
  if (hiddenTotal) hiddenTotal.value = total;
  if (hiddenDelivery) hiddenDelivery.value = deliveryFee;
}

function validateOrderForm() {
  const btn = document.getElementById("btnWhatsApp");
  if (!btn) return;

  const name = document.getElementById("custName")?.value.trim();
  const phone = document.getElementById("custPhone")?.value.trim();
  const address = document.getElementById("custAddress")?.value.trim();

  if (name && phone && address && cart.length > 0) {
    btn.disabled = false;
  } else {
    btn.disabled = true;
  }
}

const orderForm = document.getElementById("orderForm");
if (orderForm) {
  orderForm.onsubmit = async function (e) {
    e.preventDefault();
    const btn = document.getElementById("btnWhatsApp");
    const originalText = btn.innerText;

    btn.innerText = "Mengirim...";
    btn.style.backgroundColor = "#ccc";
    btn.disabled = true;

    const formData = new FormData(orderForm);
    formData.append(
      "totalAmount",
      document.getElementById("hiddenTotalAmount").value,
    );
    formData.append(
      "deliveryFee",
      document.getElementById("hiddenDeliveryFee").value,
    );
    formData.append("cartData", JSON.stringify(cart));

    try {
      const response = await fetch("keranjang.php", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();

      if (result.status === "success") {
        btn.innerText = "Berhasil!";
        btn.style.backgroundColor = "#28a745";
        localStorage.removeItem("cleanwash_cart");
        cart = [];
        setTimeout(() => {
          window.location.href = "riwayat-order.php";
        }, 1500);
      } else {
        alert(result.message || "Terjadi kesalahan.");
        btn.disabled = false;
        btn.innerText = originalText;
      }
    } catch (error) {
      alert("Koneksi gagal.");
      btn.disabled = false;
      btn.innerText = originalText;
    }
  };
}

document.addEventListener("input", () => {
  calculateTotal();
  validateOrderForm();
});

updateCartUI();
validateOrderForm();
