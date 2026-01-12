<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Categories Management</h1>
    <a href="<?= APP_URL ?>/admin/categories/create" class="btn btn-sm btn-success shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Create New Category
    </a>
</div>

<?php include_once __DIR__ . '/../../layout/messages.php' ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Category List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Category Name</th>
                        <th>Category Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['categories'])): ?>
                        <?php $i = 1; foreach ($data['categories'] as $category): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($category['category_name']) ?></td>
                                <td><?= htmlspecialchars($category['category_type']) ?></td>
                                
                                <td>
                                    <?php if ($category['status'] == 'Active'): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= APP_URL ?>/admin/categories/edit/<?= $category['category_id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?= APP_URL ?>/admin/categories/delete/<?= $category['category_id'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this category?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No categories found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>