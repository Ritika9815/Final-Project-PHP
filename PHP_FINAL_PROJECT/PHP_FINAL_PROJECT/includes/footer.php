    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <!-- Footer Logo -->
                        <svg width="24" height="24" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                            <rect width="32" height="32" rx="6" fill="#0d6efd"/>
                            <path d="M8 12h16v2H8v-2zm0 4h16v2H8v-2zm0 4h12v2H8v-2z" fill="white"/>
                        </svg>
                        <h5 class="mb-0">PHP CRUD App</h5>
                    </div>
                    <p class="text-light-50">
                        A comprehensive web application demonstrating CRUD operations, user authentication, 
                        and content management using PHP and MySQL.
                    </p>
                </div>
                <div class="col-md-3">
                    <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-light-50 text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-light-50 text-decoration-none">About</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="mb-2"><a href="dashboard.php" class="text-light-50 text-decoration-none">Dashboard</a></li>
                        <li class="mb-2"><a href="users.php" class="text-light-50 text-decoration-none">Users</a></li>
                        <?php else: ?>
                        <li class="mb-2"><a href="register.php" class="text-light-50 text-decoration-none">Register</a></li>
                        <li class="mb-2"><a href="login.php" class="text-light-50 text-decoration-none">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-uppercase fw-bold mb-3">Features</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2 text-light-50">
                            <i data-feather="check" style="width: 14px; height: 14px;"></i>
                            User Management
                        </li>
                        <li class="mb-2 text-light-50">
                            <i data-feather="check" style="width: 14px; height: 14px;"></i>
                            Content Management
                        </li>
                        <li class="mb-2 text-light-50">
                            <i data-feather="check" style="width: 14px; height: 14px;"></i>
                            Secure Authentication
                        </li>
                        <li class="mb-2 text-light-50">
                            <i data-feather="check" style="width: 14px; height: 14px;"></i>
                            Image Upload
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 border-light-25">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-light-50">
                        &copy; <?php echo date('Y'); ?> PHP CRUD App. Built with PHP, MySQL, and Bootstrap.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end justify-content-start">
                        <span class="text-light-50 me-3">
                            <i data-feather="code" style="width: 16px; height: 16px;"></i>
                            PHP 7.4+
                        </span>
                        <span class="text-light-50 me-3">
                            <i data-feather="database" style="width: 16px; height: 16px;"></i>
                            MySQL
                        </span>
                        <span class="text-light-50">
                            <i data-feather="layout" style="width: 16px; height: 16px;"></i>
                            Bootstrap 5
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="js/main.js"></script>
    
    <!-- Initialize Feather Icons -->
    <script>
        feather.replace();
    </script>
</body>
</html>
