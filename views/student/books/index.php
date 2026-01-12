<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Library Book Collection</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= APP_URL ?>/student/books" method="GET">
                <div class="row">
                    <div class="col-md-5 mb-2">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search title or author..." 
                               value="<?= htmlspecialchars($data['search'] ?? '') ?>">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="category_id" class="form-control">
                            <option value="">-- All Categories --</option>
                            <?php foreach ($data['categories'] as $cat): ?>
                                <option value="<?= $cat['category_id'] ?>" 
                                    <?= ($data['catId'] == $cat['category_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-primary btn-block" type="submit">
                            <i class="fas fa-filter fa-sm"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                                        <div class="btn-group w-100">
                                            <button type="button" 
                                                class="btn btn-info btn-sm view-book-btn" 
                                                data-toggle="modal" 
                                                data-target="#bookDetailsModal"
                                                data-id="<?= $book['book_id'] ?>"
                                                data-name="<?= htmlspecialchars($book['book_name'] ?? 'N/A') ?>"
                                                data-author="<?= htmlspecialchars($book['author_name'] ?? 'Unknown') ?>"
                                                data-cat="<?= htmlspecialchars($book['category_name'] ?? 'General') ?>"
                                                data-desc="<?= htmlspecialchars($book['book_description'] ?? 'No description available.') ?>"
                                                data-status="<?= $book['status'] ?? 'Good' ?>" 
                                                data-avail="<?= $book['availability_status'] ?? 'Unavailable' ?>"
                                                data-date="<?= isset($book['publish_date']) ? date('d M Y', strtotime($book['publish_date'])) : 'N/A' ?>">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No books match your filters.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bookDetailsModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel text-white">Book Information</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="fas fa-book fa-9x text-gray-200"></i>
                        <div id="modal-badge-container" class="mt-3"></div>
                    </div>
                    <div class="col-md-8">
                        <h3 id="modal-book-name" class="font-weight-bold"></h3>
                        <p class="text-muted">By <span id="modal-author-name"></span></p>
                        <hr>
                        <p><strong>Category:</strong> <span id="modal-category"></span></p>
                        <p><strong>Condition:</strong> <span id="modal-condition" class="badge badge-info"></span></p>
                        <p><strong>Publish Date:</strong> <span id="modal-publish-date"></span></p>
                        <hr>
                        <h5>Description</h5>
                        <p id="modal-description" class="text-justify"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <div id="modal-footer-action"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.view-book-btn').on('click', function() {
        const book = $(this).data();

        // Populate text fields
        $('#modal-book-name').text(book.name);
        $('#modal-author-name').text(book.author);
        $('#modal-category').text(book.cat);
        $('#modal-condition').text(book.status);
        $('#modal-publish-date').text(book.date);
        $('#modal-description').text(book.desc);

        // Handle Availability Badge
        let badgeClass = book.avail === 'Available' ? 'badge-success' : 'badge-secondary';
        $('#modal-badge-container').html(`<span class="badge ${badgeClass} badge-pill px-3">${book.avail}</span>`);

        // Handle Footer Borrow Button
        let footerHtml = '';
        if (book.avail === 'Available') {
            footerHtml = `
                <form action="<?= APP_URL ?>/student/borrow" method="POST">
                    <input type="hidden" name="book_id" value="${book.id}">
                    <button type="submit" class="btn btn-primary">Borrow This Book</button>
                </form>`;
        } else {
            footerHtml = `<button class="btn btn-light" disabled>Currently Unavailable</button>`;
        }
        $('#modal-footer-action').html(footerHtml);
    });
});
</script>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>