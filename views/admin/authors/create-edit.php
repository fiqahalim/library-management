<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $data['title'] ?></h1>
    <a href="<?= APP_URL ?>/admin/authors" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Author Details</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST">
            <div class="form-group">
                <label for="author_name">Author Name</label>
                <input type="text" name="author_name" id="author_name" class="form-control" 
                       value="<?= $data['author']['author_name'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="bio">Biography</label>
                <textarea name="bio" id="bio" class="form-control" rows="5"><?= $data['author']['bio'] ?? '' ?></textarea>
            </div>

            <hr>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?= isset($data['author']) ? 'Update Author' : 'Save Author' ?>
            </button>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>