<?php include_once __DIR__ . '/../layout/header.php' ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Welcome, <?= htmlspecialchars($_SESSION['fullname']) ?></h1>

    <?php if ($isAdmin): ?>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Books</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Manage your library inventory here.</div>
                    </div>
                </div>
            </div>
            </div>

    <?php else: ?>
        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Available Books to Borrow</h6>
                        <a href="<?= APP_URL ?>/student/books" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Book Name</th>
                                        <th>Author</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['availableBooks'])): ?>
                                        <?php foreach ($data['availableBooks'] as $book): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($book['book_name'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($book['author_name'] ?? 'Unknown') ?></td>
                                                <td>
                                                    <?php if (isset($book['book_id'])): ?>
                                                        <form action="<?= APP_URL ?>/student/borrow" method="POST">
                                                            <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                                            <button type="submit" class="btn btn-success btn-sm">Borrow</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-danger">ID Missing</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-center">No books available.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">My Borrowing History</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['myHistory'])): ?>
                                        <?php foreach (array_slice($data['myHistory'], 0, 5) as $history): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($history['book_name']) ?></td>
                                                <td>
                                                    <?php 
                                                        $badge = 'badge-secondary';
                                                        if($history['status'] == 'Approved') $badge = 'badge-success';
                                                        if($history['status'] == 'Pending') $badge = 'badge-warning';
                                                    ?>
                                                    <span class="badge <?= $badge ?>"><?= $history['status'] ?></span>
                                                </td>
                                                <td><small><?= $history['due_date'] ? date('d M', strtotime($history['due_date'])) : '-' ?></small></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-center">No history found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>