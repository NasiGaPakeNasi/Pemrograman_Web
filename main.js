// Handle testimonial form submission
document.getElementById("testimonial-form").addEventListener("submit", function (event) {
  event.preventDefault();

  // ngmbil data dari form
  const name = document.getElementById("nama").value;
  const testimonial = document.getElementById("pesan").value;
  const username = document.getElementById("username").value;

  // update testimonial text secara dinamis
  const testimonialText = document.getElementById("testimonial-text");
  const testimonialName = document.getElementById("testimonial-name");

  testimonialText.innerText = `"${testimonial}"`;
  testimonialName.innerText = `- ${name} (@${username})`;

  // clear form setelah submit
  document.getElementById("testimonial-form").reset();
});

// Sticky Navbar
window.addEventListener("scroll", function() {
  const navbar = document.querySelector(".navbar");
  if (window.scrollY > 50) {
    navbar.classList.add("sticky");
  } else {
    navbar.classList.remove("sticky");
  }
});

// Modal for View More buttons
const viewMoreButtons = document.querySelectorAll("button");
viewMoreButtons.forEach((button) => {
  button.addEventListener("click", function() {
    alert("This feature is coming soon!");
  });
});