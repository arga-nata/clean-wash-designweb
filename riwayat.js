const dummyOrder = [
  {
    id: "001",
    items: [
      { name: "Cuci Kering", qty: "5kg" },
      { name: "Cuci Karpet", qty: "1lm" },
    ],
    status: "Selesai",
    total: "Rp 100.000",
  },
  {
    id: "002",
    items: [{ name: "Cuci Setrika", qty: "3kg" }],
    status: "Proses",
    total: "Rp 30.000",
  },
  {
    id: "003",
    items: [
      { name: "Cuci Bedcover", qty: "1pcs" },
      { name: "Cuci Selimut", qty: "1pcs" },
      { name: "Cuci Sprei", qty: "2pcs" },
    ],
    status: "Pending",
    total: "Rp 150.000",
  },
  {
    id: "004",
    items: [
      { name: "Setrika Saja", qty: "4kg" },
      { name: "Cuci Kering", qty: "2kg" },
    ],
    status: "Selesai",
    total: "Rp 70.000",
  },
];

const orderList = document.getElementById("order-list");

function getStatusClass(status) {
  if (status === "Selesai") return "text-bg-success";
  if (status === "Proses") return "text-bg-warning";
  return "text-bg-danger";
}

function renderOrders() {
  orderList.innerHTML = "";

  dummyOrder.forEach((order) => {
    const displayItems = order.items
      .slice(0, 2)
      .map((item) => item.name)
      .join(", ");
    const sisaItem = order.items.length - 2;
    const semuaLayanan =
      sisaItem > 0
        ? `${displayItems}... (+${sisaItem} item lainnya)`
        : displayItems;
    const totalBerat = order.items.reduce((sum, item) => {
      return sum + parseInt(item.qty);
    }, 0);

    const cardHTML = `
        <div class="col">
          <div class="card h-100">
            <img src="waduh.jpg" class="card-img-top" alt="mesin-cuci">
            <div class="card-body text-start">
              <div class="d-flex justify-content-between mb-2">
                <h5 class="card-title">Pesanan #${order.id}</h5>
                <h1 class="badge p-2 w-50 ${getStatusClass(order.status)}">${order.status}</h1>
              </div>
                <p class="card-text"><strong>Layanan:</strong> ${semuaLayanan}</p>
                <p class="card-text"><strong>Total Berat:</strong> ${totalBerat} kg</p>
                <p class="card-text"><strong>Total:</strong> ${order.total}</p>
            </div>
            <div class="card-footer">
              <button class="btn btn-primary w-100 p-2">Detail Pesanan</button>
            </div>
          </div>
        </div>
      `;

    orderList.innerHTML += cardHTML;
  });
}

setTimeout(renderOrders, 2000);
