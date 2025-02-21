document.addEventListener("DOMContentLoaded", function () {
  loadComponent("../../theme/header.html", "header-placeholder");
  loadComponent("../../theme/footer.html", "footer-placeholder");
});

function loadComponent(url, placeholderId) {
  fetch(url)
    .then((response) => response.text())
    .then((data) => {
      document.getElementById(placeholderId).innerHTML = data;
    })
    .catch((error) => console.error(`Error loading ${url}:`, error));
}

// Function to load the head-links.html file into the <head>
async function loadHeadLinks() {
  try {
    const response = await fetch("../../theme/head-link.html"); // Load the file
    const text = await response.text(); // Get the text content
    document.head.insertAdjacentHTML("beforeend", text); // Insert into <head>
  } catch (error) {
    console.error("Error loading head links:", error);
  }
}

// Call the function when the page loads
loadHeadLinks();
