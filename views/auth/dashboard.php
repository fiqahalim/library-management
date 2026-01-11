<?php include_once __DIR__ . '/../layout/header.php' ?>

<section class="breadcrumb-section set-bg" data-setbg="<?= APP_URL ?>/assets/img/breadcrumb-bg.jpg" style="background-image: url('<?= APP_URL ?>/assets/img/breadcrumb-bg.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2><?= ($role_id == 1) ? 'Admin Control Center' : 'Member Dashboard' ?></h2>
                    <div class="bt-option">
                        <a href="<?= APP_URL ?>/">Home</a>
                        <span><?= ($role_id == 1) ? 'Management' : 'My Account' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-left">
                    <h2 class="text-white">Welcome, <?= htmlspecialchars($full_name) ?>!</h2>
                    <p class="text-primary-color"><?= ($role_id == 1) ? 'Administrator Access' : 'Gym Member' ?></p>
                </div>
            </div>
        </div>

        <?php if ($role_id == 1): ?>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="ps-item mb-4 text-center" style="background: #151515; border: 1px solid #333; padding: 30px;">
                        <h3 class="text-white">Total Members</h3>
                        <h2 class="mt-3" style="color: #f36103; font-size: 45px;"><?= $totalMembers ?></h2>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="ps-item mb-4 text-center" style="background: #151515; border: 1px solid #333; padding: 30px;">
                        <h3 class="text-white">Active Subs</h3>
                        <h2 class="mt-3" style="color: #f36103; font-size: 45px;"><?= $activeSubs ?></h2>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="ps-item mb-4 text-center" style="background: #151515; border: 1px solid #333; padding: 30px;">
                        <h3 class="text-white">Total Revenue</h3>
                        <h2 class="mt-3" style="color: #f36103; font-size: 40px;">RM <?= number_format($totalRevenue, 2) ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="leave-comment p-4 bg-dark rounded">
                        <h4 class="text-white mb-4">Revenue Overview (This Year)</h4>
                        <div class="chart-container" style="position: relative; height:350px; width:100%;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="leave-comment p-4 bg-dark rounded shadow-sm">
                        <h4 class="text-white mb-4">Recent Payments</h4>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentPayments)): ?>
                                        <tr><td colspan="5" class="text-center">No recent payments.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($recentPayments as $pay): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($pay['full_name']) ?></td>
                                            <td><?= htmlspecialchars($pay['plan_name']) ?></td>
                                            <td style="color: #f36103;">RM <?= number_format($pay['amount'], 2) ?></td>
                                            <td><?= date('d M Y, h:i A', strtotime($pay['payment_date'])) ?></td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- MEMBER DASHBOARD -->
            <div class="row">
                <div class="col-lg-12">
                    <?php Flash::display(); ?>
                </div>
                <div class="col-lg-4">
                    <div class="ps-item mb-4 member-plan-card" style="background: #343a40; border: 1px solid #333; padding: 30px;">
                        <h3 class="text-white">Active Plan</h3>
                        <?php if ($activeSubscription): ?>
                            <div class="pi-price">
                                <h2 style="color: #f36103;"><?= $activeSubscription['plan_name'] ?></h2>
                            </div>
                            <ul class="text-white">
                                <li>Expires: <strong><?= date('M d, Y', strtotime($activeSubscription['end_date'])) ?></strong></li>
                                <li>Duration: <strong><?= $activeSubscription['duration_months'] ?> months</strong></li>
                                <li>Status: <span class="badge bg-success">Active</span></li>
                            </ul>
                            
                            <form action="<?= APP_URL ?>/auth/cancel-plan" method="POST" onsubmit="return confirm('Are you sure you want to cancel your current plan? You will lose access immediately.');">
                                <input type="hidden" name="sub_id" value="<?= $activeSubscription['sub_id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-block mt-3" style="border-radius: 0; font-weight: bold; text-transform: uppercase;">
                                    Cancel Subscription
                                </button>
                            </form>
                            
                        <?php else: ?>
                            <div class="pi-price"><h2>None</h2></div>
                            <p class="text-muted">You don't have an active plan.</p>
                            <a href="<?= APP_URL ?>/plans" class="primary-btn mt-3">Buy Plan</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="leave-comment p-4 bg-dark rounded">
                        <h4 class="text-white mb-4">Subscription History</h4>
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Plan</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pastHistory)): ?>
                                    <tr><td colspan="4" class="text-center">No history found.</td></tr>
                                <?php else: ?>
                                    <?php  $i = 1; foreach ($pastHistory as $history): ?> <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($history['plan_name']) ?></td>
                                        <td><?= date('d M Y', strtotime($history['start_date'])) ?></td>
                                        <td><?= date('d M Y', strtotime($history['end_date'])) ?></td>
                                        <td>
                                            <?php if ($history['status'] === 'Active' && $history['end_date'] >= date('Y-m-d')): ?>
                                                <span class="badge bg-success">Current</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= htmlspecialchars($history['status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#detailsModal<?= $history['sub_id'] ?>" style="background: #f36103; border: none; font-size: 12px;">
                                                VIEW
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- POPUP MODAL -->
                                    <div class="modal fade" id="detailsModal<?= $history['sub_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content bg-dark text-white" style="border: 1px solid #f36103;">
                                                <div class="modal-header border-secondary">
                                                    <h5 class="modal-title">Subscription & Payment Details</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6 class="text-primary-color mb-3"><i class="fa fa-dumbbell"></i> Plan Information</h6>
                                                    <p><strong>Plan Name:</strong> <?= htmlspecialchars($history['plan_name']) ?></p>
                                                    <p><strong>Monthly Rate:</strong> RM <?= number_format($history['monthly_fee'], 2) ?></p>
                                                    <p><strong>Duration:</strong> <?= $history['duration_months'] ?> Months</p>
                                                    
                                                    <hr class="border-secondary">
                                                    
                                                    <h6 class="text-primary-color mb-3"><i class="fa fa-calendar"></i> Subscription Period</h6>
                                                    <p><strong>Start Date:</strong> <?= date('d M Y', strtotime($history['start_date'])) ?></p>
                                                    <p><strong>End Date:</strong> <?= date('d M Y', strtotime($history['end_date'])) ?></p>
                                                    <p><strong>Status:</strong> 
                                                        <span class="badge <?= ($history['status'] == 'Active') ? 'bg-success' : 'bg-secondary' ?>">
                                                            <?= $history['status'] ?>
                                                        </span>
                                                    </p>
                                                    
                                                    <hr class="border-secondary">
                                                    
                                                    <h6 class="text-primary-color mb-3"><i class="fa fa-credit-card"></i> Payment Information</h6>
                                                    <?php if ($history['payment_date']): ?>
                                                        <p><strong>Transaction Date:</strong> <?= date('d M Y, h:i A', strtotime($history['payment_date'])) ?></p>
                                                        <p><strong>Payment Method:</strong> <?= $history['payment_method'] ?></p>
                                                        <p><strong>Payment Status:</strong> <span class="text-success"><?= $history['payment_status'] ?></span></p>
                                                        <div class="d-flex justify-content-between mt-3 p-2" style="background: #222; border-left: 3px solid #f36103;">
                                                            <span>Total Paid:</span>
                                                            <strong style="color: #f36103; font-size: 18px;">RM <?= number_format($history['paid_amount'], 2) ?></strong>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">No payment record found for this subscription.</p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer border-secondary">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>