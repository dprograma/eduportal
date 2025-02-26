// Initialize withdrawal modal
document.addEventListener('DOMContentLoaded', function() {
    // Get the modal element
    const withdrawalModal = new bootstrap.Modal(document.getElementById('withdrawalModal'));
    
    // Handle form submission
    const withdrawalForm = document.getElementById('withdrawalForm');
    if (withdrawalForm) {
        withdrawalForm.addEventListener('submit', function(e) {
            const amount = document.getElementById('modal_amount').value;
            const bankName = document.getElementById('modal_bank_name').value;
            const accountNumber = document.getElementById('modal_account_number').value;
            const accountName = document.getElementById('modal_account_name').value;

            if (!amount || !bankName || !accountNumber || !accountName) {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }

            if (amount < 1000) {
                e.preventDefault();
                alert('Minimum withdrawal amount is ₦1,000');
                return;
            }

            if (accountNumber.length !== 10 || !/^\d+$/.test(accountNumber)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit account number');
                return;
            }
        });
    }
}); 