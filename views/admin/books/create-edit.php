<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $data['title'] ?></h1>
    <a href="<?= APP_URL ?>/admin/books" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Book Information</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label>Book Title</label>
                    <input type="text" name="book_name" class="form-control" value="<?= $data['book']['book_name'] ?? '' ?>" required>
                </div>

                <div class="col-md-6 form-group">
                    <label>Author</label>
                    <select name="author_id" class="form-control" required>
                        <option value="">-- Select Author --</option>
                        <?php foreach($data['authors'] as $author): ?>
                            <option value="<?= $author['author_id'] ?>" <?= (isset($data['book']) && $data['book']['author_id'] == $author['author_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($author['author_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach($data['categories'] as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>" <?= (isset($data['book']) && $data['book']['category_id'] == $cat['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Publish Date</label>
                    <input type="date" name="publish_date" class="form-control" value="<?= $data['book']['publish_date'] ?? '' ?>">
                </div>
                <div class="col-md-4 form-group">
                    <label>Book Condition</label>
                    <select name="status" class="form-control">
                        <option value="New" <?= (isset($data['book']) && $data['book']['status'] == 'New') ? 'selected' : '' ?>>New</option>
                        <option value="Old" <?= (isset($data['book']) && $data['book']['status'] == 'Old') ? 'selected' : '' ?>>Old</option>
                        <option value="Damaged" <?= (isset($data['book']) && $data['book']['status'] == 'Damaged') ? 'selected' : '' ?>>Damaged</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label>Availability</label>
                    <select name="availability_status" class="form-control">
                        <option value="Available" <?= (isset($data['book']) && $data['book']['availability_status'] == 'Available') ? 'selected' : '' ?>>Available</option>
                        <option value="Borrowed" <?= (isset($data['book']) && $data['book']['availability_status'] == 'Borrowed') ? 'selected' : '' ?>>Borrowed</option>
                    </select>
                </div>

                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="book_description" class="form-control" rows="4"><?= $data['book']['book_description'] ?? '' ?></textarea>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?= isset($data['book']) ? 'Update Book' : 'Save Book' ?>
            </button>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>