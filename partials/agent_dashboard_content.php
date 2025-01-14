<div class="col-lg-9 col-md-8">
    <div class="mb-4">
        <h1 class="mb-0 h3">Welcome to Your Dashboard, <?= explode(' ', $currentUser->fullname)[0] ?>!</h1>
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
                        <?= $pastQuestionsUploaded ? formatNumber($pastQuestionsUploaded->total, 0) : 0 ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Documents Approved</span>
                    <h3 class="mb-0 mt-4">
                        <?= $pastQuestionsApproved ? formatNumber($pastQuestionsApproved->approved, 0) : 0 ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Documents Pending</span>
                    <h3 class="mb-0 mt-4">
                        <?= $pastQuestionsPending ? formatNumber($pastQuestionsPending->pending, 0) : 0 ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <span>Total Revenue</span>
                    <h3 class="mb-0 mt-3 mb-1">₦
                        <?= $userDeposit ? formatNumber($userDeposit->payable, 2) : formatNumber(0, 2); ?>
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
    <div class="modal fade" id="requestWithdrawalModal" tabindex="-1" aria-labelledby="requestWithdrawalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestWithdrawalModalLabel">
                        <?= $currentUser->is_agent ? "Agent" : "Affiliate" ?> Withdrawal Request
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <p>Payable Balance: ₦ <span
                                id="payableBalance"><?= $userDeposit ? formatNumber($userDeposit->payable, 2) : formatNumber(0, 2); ?></span>
                        </p>

                        <form id="withdrawForm">
                            <div class="form-group">
                                <label for="amount" class="form-label mb-3">Enter Withdrawal Amount:</label>
                                <input type="number" class="form-control" id="amount" name="amount" required>
                            </div>
                            <button type="submit" class="mt-4 btn btn-primary">Request Withdrawal</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("withdrawForm").addEventListener("submit", (e) => {
    e.preventDefault();
    
    const requestWithdrawal = async () => {
        const amount = document.getElementById("amount").value;
        try {
            const response = await fetch("request-withdrawal", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ amount }),
            });

            console.log("response data returned from request: ", response);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            console.log("Response data:", data);

            if (data.success) {
                Swal.fire({
                    title: 'Withdrawal Request',
                    text: 'Your withdrawal request has been submitted successfully. Please check your email for further details.',
                    icon:'success',
                });
                // Update balance on the page
                document.getElementById("payableBalance").innerText = data.payable;
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while processing your request. Please try again.");
        }
    };

    requestWithdrawal();
});

</script>