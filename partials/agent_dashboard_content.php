<div class="col-lg-9 col-md-8">
    <div class="mb-4">
        <h1 class="mb-0 h3">Welcome to Your Dashboard,                                                       <?php echo explode(' ', $currentUser->fullname)[0] ?>!</h1>
    </div>
    <div class="mb-5">
        <h4 class="mb-1">Agent Dashboard Overview</h4>
        <p class="mb-0 fs-6">Manage your past questions, publications and ebooks.</p>
    </div>
    <div class="row mb-5 g-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Documents Uploaded</span>
                    <h3 class="mb-0 mt-4">
                        <?php echo $pastQuestionsUploaded ? formatNumber($pastQuestionsUploaded->total, 0) : 0 ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Documents Approved</span>
                    <h3 class="mb-0 mt-4">
                        <?php echo $pastQuestionsApproved ? formatNumber($pastQuestionsApproved->approved, 0) : 0 ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Documents Pending</span>
                    <h3 class="mb-0 mt-4">
                        <?php echo $pastQuestionsPending ? formatNumber($pastQuestionsPending->pending, 0) : 0 ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Revenue</span>
                    <h3 class="mb-0 mt-3 mb-1">₦
                        <?php echo $userDeposit ? formatNumber($userDeposit->payable, 2) : formatNumber(0, 2); ?>
                    </h3>
                    <span class="text-primary"
                        style="font-size: 12px; background:rgba(108, 117, 125, 0.2); padding: 3px 5px; border-radius: 5px; cursor: pointer;"><a
                            data-bs-toggle="modal" data-bs-target="#requestWithdrawalModal">Request
                            Withdrawal</a></span>
                </div>
            </div>
        </div>
    </div>
    <!-- User Details Modal -->
    <div class="modal fade" id="requestWithdrawalModal" tabindex="-1" aria-labelledby="requestWithdrawalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestWithdrawalModalLabel">Agent Withdrawal Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php $banks = fetchBankCodes(); ?>
                    <form id="withdrawalForm" action="request-withdrawal" method="post">
                        <div class="mb-3">
                            <label for="modal_amount" class="form-label">Amount (₦)</label>
                            <input type="number" class="form-control" id="modal_amount" name="amount"
                                   min="1000" max="<?php echo $userDeposit ? $userDeposit->payable : 0 ?>" step="100" required>
                            <small class="text-muted">Minimum withdrawal: ₦1,000 | Available Balance: ₦<?php echo $userDeposit ? formatNumber($userDeposit->payable, 2) : formatNumber(0, 2); ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="modal_bank_name" class="form-label">Bank Name</label>
                            <select class="form-select" id="modal_bank_name" name="bank_code" required>
                                <option value="">Select Bank</option>
                                <?php foreach ($banks as $bank): ?>
                                    <option value="<?php echo htmlspecialchars($bank['code']) ?>"><?php echo htmlspecialchars($bank['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal_account_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="modal_account_number" name="account_number"
                                   pattern="[0-9]{10}" maxlength="10" required
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <small class="text-muted">Enter 10-digit account number</small>
                        </div>
                        <div class="mb-3">
                            <label for="modal_account_name" class="form-label">Account Name</label>
                            <input type="text" class="form-control" id="modal_account_name" name="account_name" required>
                            </div>
                        <input type="hidden" name="user_type" value="agent">
                        </form>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="withdrawalForm" class="btn btn-primary">Submit Request</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const amount = document.getElementById('modal_amount').value;
    const accountNumber = document.getElementById('modal_account_number').value;
    const availableBalance =                             <?php echo $userDeposit ? $userDeposit->payable : 0 ?>;

    if (amount < 1000) {
        alert('Minimum withdrawal amount is ₦1,000');
        return;
    }

    if (amount > availableBalance) {
        alert('Withdrawal amount cannot exceed your available balance');
        return;
    }

    if (accountNumber.length !== 10) {
        alert('Please enter a valid 10-digit account number');
        return;
    }

    if (confirm('Are you sure you want to proceed with this withdrawal request?')) {
        this.submit();
    }
});

// Clear form when modal is closed
document.getElementById('requestWithdrawalModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('withdrawalForm').reset();
});
</script>