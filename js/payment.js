document.addEventListener("DOMContentLoaded", () => {
  const confirmPaymentButton = document.getElementById("confirm-payment");

  confirmPaymentButton.addEventListener("click", () => {
      const selectedBank = document.getElementById("bank").value;

      if (!selectedBank) {
          alert("Please select a bank to proceed.");
          return;
      }

      alert(`Redirecting to ${selectedBank.toUpperCase()} for payment confirmation.`);
  });
});
