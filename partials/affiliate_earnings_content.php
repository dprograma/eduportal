<div class="row">
    <!-- Earnings Overview Card -->
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h6 class="text-muted mb-1">Total Earnings</h6>
                        <h3 class="mb-0">₦<?=number_format($totalCommissions, 2) ?></h3>
                    </div>
                    <div class="col-md-4 text-center">
                        <h6 class="text-muted mb-1">Available Balance</h6>
                        <h3 class="mb-0">₦<?=number_format($availableBalance, 2) ?></h3>
                    </div>
                    <div class="col-md-4 text-center">
                        <h6 class="text-muted mb-1">Total Referrals</h6>
                        <h3 class="mb-0"><?=$totalReferrals ?></h3>
                    </div>
                    <div class="mt-5 col-md-4 text-center">
                        <h6 class="text-muted mb-1">Product Commissions</h6>
                        <h3 class="mb-0">₦<?=number_format($productCommissions, 2)?></h3>
                    </div>
                    <div class="mt-5 col-md-4 text-center">
                        <h6 class="text-muted mb-1">Referral Commissions</h6>
                        <h3 class="mb-0">₦<?=number_format($referralCommissions, 2)?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Section -->
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Request Withdrawal</h5>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-<?php echo $_GET['type'] ?>" role="alert">
                        <?php echo htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>
                <!-- Use Bootstrap's data attributes to trigger the modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#withdrawalModal">
                    Request Withdrawal
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Earnings Table -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Recent Earnings</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Referral</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Commission</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentCommissions as $commission): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($commission->created_at)) ?></td>
                                <td><?php echo htmlspecialchars($commission->referred_name) ?></td>
                                <td><?php echo htmlspecialchars($commission->product_name) ?></td>
                                <td>₦<?php echo number_format($commission->amount, 2) ?></td>
                                <td>₦<?php echo number_format($commission->commission_amount, 2) ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $commission->commission_type === 'product' ? 'primary' : 'info'?>">
                                        <?php echo ucfirst($commission->commission_type)?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Withdrawal Modal -->
<div class="modal fade" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawalModalLabel">Request Withdrawal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $banks = fetchBankCodes(); ?>
                <form id="withdrawalForm" action="request-withdrawal" method="post">
                    <div class="mb-3">
                        <label for="modal_amount" class="form-label">Amount (₦)</label>
                        <input type="number" class="form-control" id="modal_amount" name="amount"
                               min="1000" max="<?php echo $availableBalance ?>" step="100" required>
                        <small class="text-muted">Minimum withdrawal: ₦1,000 | Available Balance: ₦<?php echo number_format($availableBalance, 2) ?></small>
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
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? '' ?>">
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
function validateWithdrawalForm() {
    const amount = document.getElementById('modal_amount').value;
    const accountNumber = document.getElementById('modal_account_number').value;
    const availableBalance =                             <?php echo $availableBalance ?>;

    if (amount < 1000) {
        alert('Minimum withdrawal amount is ₦1,000');
        return false;
    }

    if (amount > availableBalance) {
        alert('Withdrawal amount cannot exceed your available balance');
        return false;
    }

    if (accountNumber.length !== 10) {
        alert('Please enter a valid 10-digit account number');
        return false;
    }

    return confirm('Are you sure you want to proceed with this withdrawal request?');
}

// Clear form when modal is closed
document.getElementById('withdrawalModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('withdrawalForm').reset();
});
</script>