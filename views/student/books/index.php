<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Library Book Collection</h1>

    <?php include_once __DIR__ . '/../../layout/messages.php' ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= APP_URL ?>/student/books" method="GET" class="form-inline">
                <div class="input-group w-100">
                    <input type="text" name="search" class="form-control bg-light border-0 small" 
                           placeholder="Search by book title or author..." 
                           value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i> Search
                        </button>
                        <?php if(!empty($data['search'])): ?>
                            <a href="<?= APP_URL ?>/student/books" class="btn btn-secondary ml-2">Clear</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['books'])): ?>
                            <?php foreach ($data['books'] as $book): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($book['book_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($book['author_name']) ?></td>
                                    <td><?= htmlspecialchars($book['category_name']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= ($book['availability_status'] == 'Available') ? 'success' : 'secondary' ?>">
                                            <?= $book['availability_status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($book['availability_status'] == 'Available'): ?>
                                            <form action="<?= APP_URL ?>/student/borrow" method="POST">
                                                <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
                                                <button type="submit" class="btn btn-primary btn-sm">Borrow</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light" disabled>In Use</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No books found matching your search.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>