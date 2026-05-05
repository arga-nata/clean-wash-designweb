const links = document.querySelectorAll(".nav-links a");

// ambil nama file aktif
let currentPage = window.location.pathname.split("/").pop();

// kalau index (kadang kosong)
if (currentPage === "") {
  currentPage = "index.html";
}

links.forEach(link => {
  let linkPage = link.getAttribute("href");

  if (linkPage === currentPage) {
    link.classList.add("active");
  }
});