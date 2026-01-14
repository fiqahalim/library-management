<?php include_once __DIR__ . '/../../layout/header.php' ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Authors Management</h1>
    <a href="<?= APP_URL ?>/admin/authors/create" class="btn btn-sm btn-success shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Create New Author
    </a>
</div>

<?php include_once __DIR__ . '/../../layout/messages.php' ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Author List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Author Name</th>
                        <th>Bio</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['authors'])): ?>
                        <?php $i = 1; foreach ($data['authors'] as $author): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($author['author_name']) ?></td>
                                <td><?= htmlspecialchars($author['bio']) ?></td>
                                <td><?= date('d M Y', strtotime($author['created_at'])) ?></td>
                                <td>
                                    <a href="<?= APP_URL ?>/admin/authors/edit/<?= $author['author_id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="<?= APP_URL ?>/admin/authors/delete/<?= $author['author_id'] ?>" 
                                        class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to delete this author? This action cannot be undone.');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No authors found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../layout/footer.php'; ?>