<?php include_once __DIR__ . '/../layout/header.php' ?>
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        
                        <form class="user" action="<?= APP_URL ?>/auth/register" method="POST">
                            
                            <div class="form-group">
                                <input type="text" name="fullname" class="form-control form-control-user" 
                                    placeholder="Full Name" required>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" name="username" class="form-control form-control-user" 
                                        placeholder="Username" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="student_no" class="form-control form-control-user" 
                                        placeholder="Student Number" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="email" name="email" class="form-control form-control-user" 
                                        placeholder="Email Address" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="phone" class="form-control form-control-user" 
                                        placeholder="Phone Number" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" name="password" class="form-control form-control-user"
                                        placeholder="Password" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" name="confirm_password" class="form-control form-control-user"
                                        placeholder="Repeat Password" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Register Account
                            </button>
                        </form>
                        
                        <hr>
                        <div class="text-center">
                            <a class="small" href="<?= APP_URL ?>/auth/forgot-password">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="<?= APP_URL ?>/auth/login">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../layout/footer.php'; ?>