<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Books Management</h1>
    <a href="<?= APP_URL ?>/admin/books/create" class="btn btn-sm btn-success shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Create New Book
    </a>
</div>

<?php include_once __DIR__ . '/../../layout/messages.php' ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Book List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Book Name</th>
                        <th>Author & Category</th>
                        <th>Condition</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['books'])): ?>
                        <?php $i = 1; foreach ($data['books'] as $book): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($book['book_name']) ?></strong><br>
                                    <small class="text-muted">Published: <?= date('d M Y', strtotime($book['publish_date'])) ?></small>
                                </td>
                                <td>
                                    <i class="fas fa-user fa-sm"></i> <?= htmlspecialchars($book['author_name']) ?><br>
                                    <i class="fas fa-tag fa-sm"></i> <?= htmlspecialchars($book['category_name']) ?>
                                </td>
                                
                                <td>
                                    <?php 
                                        $statusClass = 'badge-secondary'; // Default
                                        if ($book['status'] == 'New') $statusClass = 'badge-success';
                                        if ($book['status'] == 'Old') $statusClass = 'badge-warning';
                                        if ($book['status'] == 'Damaged') $statusClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $book['status'] ?></span>
                                </td>

                                <td>
                                    <?php if ($book['availability_status'] == 'Available'): ?>
                                        <span class="badge badge-info shadow-sm">
                                            <i class="fas fa-check-circle"></i> Available
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-hand-holding"></i> Borrowed
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td style="width: 150px;">
                                    <a href="<?= APP_URL ?>/admin/books/edit/<?= $book['book_id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>/admin/books/delete/<?= $book['book_id'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this book?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No books found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>