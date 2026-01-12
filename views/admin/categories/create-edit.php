<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $data['title'] ?></h1>
    <a href="<?= APP_URL ?>/admin/categories" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="category_name" class="form-control" 
                       value="<?= $data['category']['category_name'] ?? '' ?>" required placeholder="e.g. Science Fiction">
            </div>

            <div class="form-group">
                <label>Category Type</label>
                <input type="text" name="category_type" class="form-control" 
                       value="<?= $data['category']['category_type'] ?? '' ?>" placeholder="e.g. Fiction, Reference, Periodical">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Active" <?= (isset($data['category']) && $data['category']['status'] == 'Active') ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= (isset($data['category']) && $data['category']['status'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <hr>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?= isset($data['category']) ? 'Update Category' : 'Save Category' ?>
            </button>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>