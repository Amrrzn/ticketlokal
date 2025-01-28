//Slideshow

let slideIndex = 0;
showSlides(slideIndex);

function changeSlide(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n - 1);
}

function showSlides(n) {
    const slides = document.querySelectorAll('.mySlides');
    const dots = document.querySelectorAll('.dot');

    if (n >= slides.length) { slideIndex = 0; }
    if (n < 0) { slideIndex = slides.length - 1; }

    slides.forEach(slide => slide.style.display = 'none');
    dots.forEach(dot => dot.classList.remove('active'));

    slides[slideIndex].style.display = 'block';
    dots[slideIndex].classList.add('active');
}

// Auto-slide functionality
setInterval(() => {
    changeSlide(1);
}, 5000); // Change slide every 5 seconds

function toggleDropdown() {
    const dropdown = document.getElementById("profile-dropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

// Close the dropdown if clicking outside of it
window.onclick = function (event) {
    if (!event.target.matches('.profile-icon')) {
        const dropdown = document.getElementById("profile-dropdown");
        if (dropdown) {
            dropdown.style.display = "none";
        }
    }
};



function showSignIn() {
    document.getElementById('signin-form').classList.remove('hidden');
    document.getElementById('customer-form').classList.add('hidden');
    document.getElementById('organizer-form').classList.add('hidden');
}

function showSignUpAsCustomer() {
    document.getElementById('signin-form').classList.add('hidden');
    document.getElementById('customer-form').classList.remove('hidden');
    document.getElementById('organizer-form').classList.add('hidden');
}

function showSignUpAsOrganizer() {
    document.getElementById('signin-form').classList.add('hidden');
    document.getElementById('customer-form').classList.add('hidden');
    document.getElementById('organizer-form').classList.remove('hidden');
}


let debounceTimeout;

// Search function
function fetchSuggestions(query, endpoint = "home.php") {
    const suggestionsBox = document.getElementById("suggestions-box");
    suggestionsBox.innerHTML = ""; // Clear previous suggestions

    if (query) {
        // Show loading indicator
        suggestionsBox.innerHTML = "<div>Loading...</div>";

        // Debounce to avoid frequent API calls
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            // Send AJAX request to fetch matching events
            fetch(`${endpoint}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Fetched data:", data); // Log the fetched data
                    console.log("Data structure:", JSON.stringify(data)); // Log the structure of the data

                    suggestionsBox.innerHTML = ""; // Clear loading message

                    if (data.length === 0) {
                        const noResult = document.createElement("div");
                        noResult.textContent = "No results found.";
                        suggestionsBox.appendChild(noResult);
                    } else {
                        data.forEach(event => {
                            const suggestionItem = document.createElement("div");
                            
                            // Log the entire event object for debugging
                            console.log("Event object:", event);

                            // Handle simple string or object
                            let title;
                            if (typeof event === "string") {
                                title = event;
                            } else if (event.Title) {
                                title = event.Title;
                            }

                            if (title) {
                                suggestionItem.innerHTML = title.replace(
                                    new RegExp(query, "gi"),
                                    match => `<strong>${match}</strong>`
                                );
                            } else {
                                suggestionItem.textContent = "Unknown Event"; // Fallback for undefined Title
                            }

                            suggestionItem.setAttribute("role", "option");
                            suggestionItem.onclick = () => {
                                const eventID = event.EventID || event.id; // Use EventID if available
                                console.log("Suggestion clicked:", title); // Log the title of the clicked suggestion
                                console.log("EventID:", eventID); // Log the EventID being used
                                if (eventID) {
                                    console.log("Navigating to event-detail.php with EventID:", eventID);
                                    window.location.href = `event-detail.php?EventID=${eventID}`;
                                } else {
                                    console.error("No EventID found for the selected event.");
                                }
                                suggestionsBox.innerHTML = ""; // Clear suggestions after selection
                            };
                            suggestionsBox.appendChild(suggestionItem);
                        });
                    }
                })
                .catch(error => {
                    console.error("Error fetching suggestions:", error);
                    suggestionsBox.innerHTML = "<div>Error fetching suggestions. Please try again later.</div>";
                });
        }, 200); // 200ms debounce delay
    }
}


/*Search Function*/
function fetchSuggestions(query) {
    const suggestionsBox = document.getElementById('suggestions-box');

    // Clear suggestions if query is empty
    if (query.trim() === '') {
        suggestionsBox.innerHTML = '';
        return;
    }

    // Make an AJAX request to fetch suggestions
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `fetch_events.php?query=${encodeURIComponent(query)}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const results = JSON.parse(xhr.responseText);

            // Display suggestions
            suggestionsBox.innerHTML = results.map(event => `
                <div onclick="selectEvent('${event.event_name}')">
                    ${event.event_name}
                </div>
            `).join('');
        }
    };
    xhr.send();
}

function selectEvent(eventName) {
    const searchBar = document.querySelector('.search-bar');
    searchBar.value = eventName;

    // Clear suggestions
    document.getElementById('suggestions-box').innerHTML = '';
}

// Use the globally defined variable (passed via inline script in HTML)
const quantityInput = document.getElementById('quantity');
const totalPriceDisplay = document.getElementById('total-price');

// Use the PHP-defined price
quantityInput.addEventListener('input', function () {
    const quantity = parseInt(quantityInput.value) || 0;
    const totalPrice = quantity * pricePerTicket; // Access pricePerTicket from PHP
    totalPriceDisplay.textContent = totalPrice.toFixed(2);
});


//Payment
/*&document.addEventListener("DOMContentLoaded", () => {
    const proceedPaymentButton = document.getElementById("proceed-payment");
    const confirmSigninButton = document.getElementById("confirm-signin");
    const confirmPaymentButton = document.getElementById("confirm-payment");

    const bankSelectionPanel = document.getElementById("bank-selection-panel");
    const signinPanel = document.getElementById("signin-panel");
    const confirmationPanel = document.getElementById("confirmation-panel");

    proceedPaymentButton.addEventListener("click", () => {
      const selectedBank = document.getElementById("bank").value;

      if (!selectedBank) {
        alert("Please select a bank to proceed.");
        return;
      }

      if (selectedBank === "maybank") {
        bankSelectionPanel.classList.add("hidden");
        signinPanel.classList.remove("hidden");
      } else {
        alert(`Redirecting to ${selectedBank.toUpperCase()} for payment.`);
      }
    });

    confirmSigninButton.addEventListener("click", () => {
      const username = document.getElementById("username").value;
      const password = document.getElementById("password").value;

      if (!username || !password) {
        alert("Please enter both username and password.");
        return;
      }

      signinPanel.classList.add("hidden");
      confirmationPanel.classList.remove("hidden");
    });

    confirmPaymentButton.addEventListener("click", () => {
      alert("Payment successful! Thank you for your transaction.");
      window.location.href = "home.php";
    });
  });*/


  function fetchSuggestions(query) {
    if (query.length === 0) {
        document.getElementById('suggestions-box').innerHTML = '';
        return;
    }
    fetch(`events.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            const suggestionsBox = document.getElementById('suggestions-box');
            suggestionsBox.innerHTML = data.map(item => `<div>${item}</div>`).join('');
        })
        .catch(error => console.error('Error fetching suggestions:', error));
}