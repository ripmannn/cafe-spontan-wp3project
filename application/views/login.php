<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title><?= $judul_halaman ?></title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= base_url('assets/gambar/resto.png') ?>" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="<?= base_url('vendor/template_backend') ?>/assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="<?= base_url('vendor/template_backend') ?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="<?= base_url('vendor/template_backend') ?>/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="<?= base_url('vendor/template_backend') ?>/assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="<?= base_url('vendor/template_backend') ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="<?= base_url('vendor/template_backend') ?>/assets/vendor/css/pages/page-auth.css" />
  <!-- Helpers -->
  <script src="<?= base_url('vendor/template_backend') ?>/assets/vendor/js/helpers.js"></script>

  <script src="<?= base_url('vendor/template_backend') ?>/assets/js/config.js"></script>
  <style>
    .typed-cursor {
      font-size: 30px;
    }
  </style>

</head>


<!-- Content -->

<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a class="app-brand-link ">
              <span class="app-brand-logo demo"><img src="assets/gambar/resto.png" alt=""></span>
              <span  class="app-brand-text demo text-body fw-bolder fs-2 m-2">Cafe Spontan</span>
            </a>
          </div>
          <!-- /Logo -->

          <p class="mb-4"><?= $this->session->flashdata('pesan') ?></p>

          <form id="formAuthentication" class="mb-3" action="<?= base_url('auth/login') ?>" method="POST">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" autofocus required />
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>

            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
            </div>
          </form>


        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>


<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="<?= base_url('vendor/template_backend') ?>/assets/vendor/libs/jquery/jquery.js"></script>
<script src="<?= base_url('vendor/template_backend') ?>/assets/vendor/libs/popper/popper.js"></script>
<script src="<?= base_url('vendor/template_backend') ?>/assets/vendor/js/bootstrap.js"></script>
<script src="<?= base_url('vendor/template_backend') ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="<?= base_url('vendor/template_backend') ?>/assets/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- typejs -->
<!-- Load library from the CDN -->
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

<!-- Setup and start animation! -->


<!-- Main JS -->
<script src="<?= base_url('vendor/template_backend') ?>/assets/js/main.js"></script>

<!-- Page JS -->

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>