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

window.onload = () => {
  document.querySelector(".page").classList.add("show");
};

document.querySelectorAll("a").forEach(link => {
  link.addEventListener("click", function(e) {
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
