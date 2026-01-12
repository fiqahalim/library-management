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
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary">
                        <h6 class="m-0 font-weight-bold text-white">Available Books to Borrow</h6>
                        <a href="<?= APP_URL ?>/student/books" class="btn btn-sm btn-light">Search All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Book Name</th>
                                        <th>Author</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['availableBooks'])): ?>
                                        <?php foreach (array_slice($data['availableBooks'], 0, 8) as $book): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($book['book_name']) ?></td>
                                                <td><small class="text-muted"><?= htmlspecialchars($book['author_name']) ?></small></td>
                                                <td>
                                                    <form action="<?= APP_URL ?>/student/borrow" method="POST">
                                                        <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                                        <button type="submit" class="btn btn-success btn-sm">Borrow</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow mb-4 border-left-success">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-star"></i> New Arrivals</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($data['newArrivals'] as $new): ?>
                            <div class="mb-2 border-bottom pb-1">
                                <div class="small font-weight-bold text-dark"><?= htmlspecialchars($new['book_name']) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($new['author_name']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-fire"></i> Popular Books</h6>
                    </div>
                    <div class="card-body">
                        <?php foreach ($data['mostBorrowed'] as $popular): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small">
                                    <strong><?= htmlspecialchars($popular['book_name']) ?></strong>
                                </div>
                                <span class="badge badge-warning badge-pill"><?= $popular['borrow_count'] ?> loans</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">My Current Loans</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm small">
                            <tbody>
                                <?php if (!empty($data['myHistory'])): ?>
                                    <?php foreach (array_slice($data['myHistory'], 0, 3) as $history): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($history['book_name']) ?></td>
                                            <td><span class="badge badge-info"><?= $history['status'] ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2">No active loans.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <a href="<?= APP_URL ?>/student/history" class="btn btn-block btn-sm btn-outline-info">View Full History</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>