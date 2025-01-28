document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".sidebar ul li a");
    const tabContents = document.querySelectorAll(".tab-content");
  
    // Function to activate a specific tab
    function activateTab(tabId) {
      // Remove active class from all tabs
      tabs.forEach((tab) => tab.classList.remove("active"));
  
      // Hide all tab contents
      tabContents.forEach((content) => content.classList.remove("active"));
  
      // Add active class to the selected tab link
      const activeTab = [...tabs].find((tab) => tab.getAttribute("href") === `#${tabId}`);
      if (activeTab) {
        activeTab.classList.add("active");
      }
  
      // Show the corresponding tab content
      const activeContent = document.getElementById(tabId);
      if (activeContent) {
        activeContent.classList.add("active");
      }
    }
  
    // Event listener for tab clicks
    tabs.forEach((tab) => {
      tab.addEventListener("click", (event) => {
        event.preventDefault();
        const tabId = tab.getAttribute("href").substring(1); // Remove the '#' from the hash
        history.pushState(null, "", `#${tabId}`); // Update the URL without reloading the page
        activateTab(tabId);
      });
    });
  
    // Check the URL hash on page load
    const initialTab = window.location.hash.substring(1) || "profile"; // Default to "profile" if no hash
    activateTab(initialTab);
  
    // Handle back/forward navigation
    window.addEventListener("popstate", () => {
      const currentTab = window.location.hash.substring(1) || "profile";
      activateTab(currentTab);
    });
  });
  