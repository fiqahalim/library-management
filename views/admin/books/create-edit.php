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
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-9">
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
                            <label>Total Quantity (Stock)</label>
                            <input type="number" name="total_stock" class="form-control" min="1" value="<?= $data['book']['total_stock'] ?? '1' ?>" required>
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Book Condition</label>
                            <select name="status" class="form-control">
                                <option value="New" <?= (isset($data['book']) && $data['book']['status'] == 'New') ? 'selected' : '' ?>>New</option>
                                <option value="Old" <?= (isset($data['book']) && $data['book']['status'] == 'Old') ? 'selected' : '' ?>>Old</option>
                                <option value="Damaged" <?= (isset($data['book']) && $data['book']['status'] == 'Damaged') ? 'selected' : '' ?>>Damaged</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group text-center">
                        <label>Book Cover</label>
                        <div class="mb-2">
                            <?php
                                $dbPath = $data['book']['book_image'] ?? '';
                                $imagePath = '';
                                
                                if (!empty($dbPath)) {
                                    // Clean up the APP_URL and dbPath
                                    $baseUrl = rtrim(APP_URL, '/');
                                    $cleanPath = ltrim($dbPath, '/');
                                    $imagePath = $baseUrl . '/' . $cleanPath;
                                }
                            ?>

                            <?php if ($imagePath): ?>
                                <img src="<?= $imagePath ?>" 
                                    class="img-thumbnail" 
                                    style="width: 100%; max-height: 250px; object-fit: contain;"
                                    onerror="this.src='https://placehold.co/200x300?text=Check+Path';"> 
                                <div class="mt-1">
                                    <small class="text-danger">Current Source: <?= htmlspecialchars($imagePath) ?></small>
                                </div>
                            <?php else: ?>
                                <div class="border p-5 bg-light text-center">No Image Found</div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="book_image" class="form-control-file" accept="image/*">
                    </div>
                </div>

                <div class="col-md-12 form-group">
                    <label>Description</label>
                    <textarea name="book_description" class="form-control" rows="4"><?= $data['book']['book_description'] ?? '' ?></textarea>
                </div>
            </div>

            <hr>
            <?php if(isset($data['book'])): ?>
                <input type="hidden" name="available_stock" value="<?= $data['book']['available_stock'] ?>">
            <?php endif; ?>
            
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> <?= isset($data['book']) ? 'Update Book' : 'Save Book' ?>
            </button>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>