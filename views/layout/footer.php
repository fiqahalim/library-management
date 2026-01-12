<?php $isLoggedIn = !empty($_SESSION['is_logged_in']); ?>

    </div> <?php if ($isLoggedIn): ?>
            </div> <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Library Management System 2026</span>
                    </div>
                </div>
            </footer>
        </div> </div> <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <?php endif; ?>

    <script src="<?= APP_URL ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= APP_URL ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= APP_URL ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/sb-admin-2.min.js"></script>
    <!-- Datatables Page level plugins -->
    <script src="<?= APP_URL ?>/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= APP_URL ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/demo/datatables-demo.js"></script>
</body>
</html>