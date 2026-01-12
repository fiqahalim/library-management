<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Borrowing History</h1>
        <a href="<?= APP_URL ?>/auth/dashboard" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Back to Dashboard
        </a>
    </div>

    <?php include_once __DIR__ . '/../../layout/messages.php' ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Your Book Requests & Loans</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Book Name</th>
                            <th>Borrow Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['history'])): ?>
                            <?php $i = 1; foreach ($data['history'] as $record): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><strong><?= htmlspecialchars($record['book_name']) ?></strong></td>
                                    <td><?= date('d M Y', strtotime($record['borrow_date'])) ?></td>
                                    <td class="text-danger">
                                        <?= $record['due_date'] ? date('d M Y', strtotime($record['due_date'])) : '-' ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $badgeClass = 'badge-secondary'; // Default
                                            if ($record['status'] == 'Pending') $badgeClass = 'badge-warning';
                                            if ($record['status'] == 'Approved') $badgeClass = 'badge-success';
                                            if ($record['status'] == 'Returned') $badgeClass = 'badge-info';
                                            if ($record['status'] == 'Cancelled') $badgeClass = 'badge-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> p-2">
                                            <?= $record['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($record['status'] == 'Pending'): ?>
                                            <form action="<?= APP_URL ?>/student/cancel-request" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                                <input type="hidden" name="borrow_id" value="<?= $record['history_id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">No actions available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">You haven't borrowed any books yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>