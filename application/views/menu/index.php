<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?= base_url('assets/gambar/resto.png') ?>" type="image/x-icon">

    <title> <?= $judul_halaman ?> </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('vendor2/') ?>css/bootstrap.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font awesome style -->
    <link href="<?= base_url('vendor2/') ?>css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="<?= base_url('vendor2/') ?>css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="<?= base_url('vendor2/') ?>css/responsive.css" rel="stylesheet" />
    <!-- box icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- css to up -->
    <link rel="stylesheet" href="<?= base_url('vendor2/') ?>css/new.css">
    <link rel="stylesheet" href="<?= base_url('vendor2/') ?>css/ads.css">

    <!-- style dropdown cart -->
    <style>
        .toast-title {
            font-family: Arial, Helvetica, sans-serif;

        }

        .dropdown-menu.dropdown-menu-start.dropdown-menu-lg {
            width: 300px;
            /* Atur lebar dropdown */
            max-height: auto-fit;
            /* Atur tinggi maksimum dropdown */
            overflow-x: hidden;
            /* Sembunyikan overflow horizontal */
            overflow-y: auto;
            padding: 5px;
            padding-left: 5px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .dropdown-menu.dropdown-menu-start.dropdown-menu-lg .dropdown-item:hover {
            background-color: #ffbe33;
            /* Ubah warna latar belakang saat hover menjadi kuning */
        }

        .dropdown-toggle::after {
            display: none !important;
        }
    </style>

</head>

<body class="sub_page">
    <div class="hero_area">
        <div class="bg-box">
            <img src="<?= base_url('vendor2/') ?>images/bghero.jpg" alt="">
        </div>
        <!-- header section strats -->
        <header class="header_section">
            <div class="container">
                <nav class="navbar navbar-expand-lg custom_nav-container ">
                    <a class="navbar-brand " href="#">
                        <span>
                            Cafe Spontan
                        </span>
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class=""> </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto "></ul>

                        <div class="user_option">
                            <?php

                            if ($this->session->userdata('pelanggan')) {
                                $nama_pelanggan = $this->session->userdata('pelanggan')['nama'];
                                // Jika session 'pelanggan' sudah ada
                            ?>
                                <a href="#" style="cursor: not-allowed;" class="user_link" data-toggle="modal" data-target="">
                                    <span class="badge rounded-pill text-dark bg-warning text-capitalize"><?= $nama_pelanggan ?></span>
                                </a>
                            <?php
                            } else {
                                // Jika session 'pelanggan' belum ada
                            ?>
                                <a href="#" class="user_link" data-toggle="modal" data-target="#userModal">
                                    <i style="font-size: 30px; color: white; " class="fa fa-user" aria-hidden="true"></i>
                                </a>
                            <?php
                            }
                            ?>


                            <!-- cart -->
                            <?php
                            $keranjang = $this->cart->contents();
                            $jml_item = 0;
                            foreach ($keranjang as $k) {
                                $jml_item = $jml_item + $k['qty'];
                            }

                            ?>
                            <div class="dropdown">
                                <a class="cart_link dropdown-toggle" href="#" role="button" id="cartDropdown" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                    <i style="font-size: 30px; color: white; " class='bx bxs-cart'></i><span class="badge badge-warning text-dark"><?= $jml_item ?></span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-start dropdown-menu-lg" aria-labelledby="cartDropdown">
                                    <?php if (empty($keranjang)) { ?>
                                        <p class="dropdown-item">Keranjang Belanja Kosong</p>

                                        <?php } else {
                                        foreach ($keranjang as $k) {
                                            $barang = $this->db->select('gambar')->from('produk')->where('id_produk', $k['id'])->get()->row_array();

                                        ?>
                                            <li><?php
                                                echo form_open('pesanan_produk/simpan');
                                                echo form_hidden('id_produk[]', $k['id']);
                                                echo form_hidden('nama_produk[]', $k['name']);
                                                echo form_hidden('jumlah[]', $k['qty']);
                                                echo form_hidden('harga[]', $k['price']);
                                                echo form_hidden('sub_total[]', $k['subtotal']);
                                                echo form_hidden('total[]', $this->cart->total());
                                                echo form_hidden('nama_pelanggan', $this->session->userdata('pelanggan')['nama']);
                                                ?>
                                                <div class="media">
                                                    <img style="width: 100px; border-radius: 50%; " class="img-size-50 mr-3 img-circle" src="<?= base_url('assets/gambar/' . $barang['gambar']) ?>" alt="">
                                                    <div class="media-body">
                                                        <p style="margin-bottom: 0;" class="dropdown-item-title font-weight-bold"><?= $k['name'] ?></p>
                                                        <p style="margin-bottom: 0;" class="text-sm">Rp.<?= number_format($k['price']) ?></p>
                                                        <p style="margin-bottom: 0;" class="text-sm"><?= $k['qty'] ?> x Rp.<?= number_format($k['price']) ?></p>
                                                        <p style="margin-bottom: 0;" class="text-sm">Rp.<?= number_format($k['subtotal']); ?></p>
                                                    </div>
                                                    <a href="<?= base_url('keranjang/delete/' . $k['rowid']) ?>" class="btn btn-danger"><i class='bx bx-trash'></i></a>
                                                </div>
                                            </li>
                                            <div class="dropdown-divider"></div>
                                        <?php } ?>
                                        <div class="grand-total">
                                            <tr>
                                                <td colspan="2"> </td>
                                                <td><strong class="h2">Total:</strong></td>
                                                <td><strong class="h2">Rp. <?php echo number_format($this->cart->total());  ?></strong></td>
                                            </tr>

                                        </div>
                                        <div class="dropdown-divider"></div>
                                        <!-- <a style="margin-left: 0;  width: 100%; " href="" class=" text-center btn btn-primary mb-3">Lihat dan update pesanan</a> -->
                                        <button onclick="return cekPesanan()" style="margin-left: 0; width: 100%;" href="" type="submit" class=" text-center btn btn-success">Pesan</button>
                                        <?php echo form_close(); ?>
                                    <?php } ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->
    </div>


    <!-- food section -->
    <?= $this->session->flashdata('pesan') ?>
    <?php
    // Memeriksa apakah session 'pelanggan' ada dan apakah session 'nama' di dalamnya telah ditetapkan
    if ($this->session->userdata('pelanggan') && $this->session->userdata('pelanggan')['nama']) {
        // Mengambil nama dari session 'pelanggan'
        $nama_pelanggan = $this->session->userdata('pelanggan')['nama'];
        // Menampilkan nama pelanggan
        // echo '<div class="alert alert-success text-center alert-dismissible fade show" role="alert">Selamat datang, ' . $nama_pelanggan . '!
        // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        // </div>';
        echo '<div class="shadow-lg d-flex justify-content-center bg-warning ">
        <p class="text-center fw-bold fs-5 pt-2">
          Selamat Datang,<span class="text-capitalize" style="color: white; text-shadow:1px 1px 10px #000, 1px 1px 10px #ccc; "> ' . $nama_pelanggan . '</span>
        </p>
      </div>';
    } else {
        // Jika session 'pelanggan' tidak ada atau session 'nama' tidak ditetapkan, menampilkan pesan default
        echo '<div class="shadow-lg d-flex justify-content-center bg-warning ">
        <p class="text-center fw-bold fs-5 pt-2">
          Selamat Datang!!, Silahkan
          <a href="#" style="color: white; text-shadow:1px 1px 10px #000, 1px 1px 10px #ccc; " data-toggle="modal" data-target="#userModal">Daftar</a>
          Terlebih dahulu
        </p>
      </div>
      ';
    }
    ?>

    <section class="food_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>
                    Menu Kami
                </h2>
            </div>

            <ul class="filters_menu">
                <li class="active" data-filter="*">All</li>
                <li data-filter=".makanan">Makanan</li>
                <li data-filter=".minuman">Minuman</li>

            </ul>

            <div class="filters-content">
                <div class="row grid">
                    <?php foreach ($produk as $row) { ?>
                        <div class="col-sm-6 col-lg-4 all <?php if ($row['jenis'] == 'makanan') {
                                                                echo 'makanan';
                                                            } else {
                                                                echo 'minuman';
                                                            } ?>">
                            <div class="box">
                                <?php
                                echo form_open('keranjang/add');
                                echo form_hidden('id', $row['id_produk']);
                                echo form_hidden('qty', 1);
                                echo form_hidden('price', $row['harga']);
                                echo form_hidden('name', $row['nama']);
                                echo form_hidden('redirect_page', str_replace('index.php/', '', current_url()));

                                ?>
                                <div>
                                    <div class="img-box">
                                        <img src="assets/gambar/<?= $row['gambar'] ?>" alt="">
                                    </div>
                                    <div class="detail-box">
                                        <h5>
                                            <?= $row['nama'] ?>
                                            <?php if ($row['new'] == 'baru') {
                                                echo '<span class="badge text-bg-success">Menu Baru</span>';
                                            } ?>

                                        </h5>
                                        <p>
                                            <?= $row['keterangan'] ?>
                                        </p>
                                        <div class="options">
                                            <h6>
                                                <?php if ($row['stok'] > 0) : ?>
                                                    Rp.<?= number_format($row['harga']) ?>
                                                <?php else : ?>
                                                    <div class="btn btn-sm btn-danger">Barang habis</div>
                                                <?php endif; ?>
                                            </h6>

                                            <?php if ($this->session->userdata('pelanggan')) : ?>
                                                <?php if ($row['stok'] > 0) : ?>
                                                    <button class="btn btn-sm btn-warning addToCartBtn">
                                                        <i style="font-size: 25px; color: white;" class="bx bxs-cart"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php else : ?>

                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>
    </section>

    <!-- end food section -->


    <!-- footer section -->
    <footer class="footer_section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-col">
                    <div class="footer_contact">
                        <h4>
                            Hubungi Kami
                        </h4>
                        <div class="contact_link_box">
                            <a href="">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span>
                                    Jl. Kemanggisan Utama Raya, RT.3/RW.2, Slipi, Kec. Palmerah, Jakarta Barat
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>
                                    +621234567890
                                </span>
                            </a>
                            <a href="">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>
                                    cafespontan@gmail.com
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 footer-col">
                    <div class="footer_detail">
                        <a href="" class="footer-logo">
                            Cafe Spontan
                        </a>
                        <p>
                            Cafe sederhana dengan modal ibu dan bapak.
                        </p>
                        <div class="footer_social">
                            <a href="">
                                <i class="fa fa-whatsapp" aria-hidden="true"></i>
                            </a>
                            <a href="">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>

                            <a href="">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="col-md-4 footer-col">
                    <h4>
                        Jam Buka
                    </h4>
                    <p>
                        Setiap Hari
                    </p>
                    <p>
                        08:00 - 00:00
                    </p>
                </div>
            </div>

        </div>
    </footer>
    <!-- footer section -->

    <!-- Modal daftar user -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title " id="userModalLabel">Daftar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span style="font-size: 35px;" class="text-dark" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Input field di sini -->
                    <form action="<?= base_url('menu/simpanpelanggan') ?>" method="post">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" name="nama" id="name" placeholder="Masukkan Nama kamu" pattern="[a-zA-Z\s]+" required>
                        </div>
                        <div class="form-group">
                            <label for="telp">No Telp</label>
                            <input type="number" class="form-control" id="telp" name="telp" placeholder="No Wa Kamu" required>
                            <input type="hidden" name="alamat" value="Jakarta">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-warning">Daftar</button>
                    <!-- Jika Anda ingin menambahkan tombol lain atau tindakan -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- btn to up -->
    <div>
        <a class="top-btn" href="#">
            <i class="bx bxs-chevrons-up"></i>
        </a>
    </div>

    <!-- ads -->
    <div class="overlay"></div>
    <div class="popup">
        <div class="contentbox">
            <div class="close-ads"></div>
            <div class="imgbx">
                <img src="<?= base_url('assets/gambar/new.png') ?>" alt="" />
            </div>
            <div class="content">
                <div class="content-text">
                    <h1>Produk Baru</h1>
                    <h2>Dimsum Ayam & Kopi Susu</h2>
                    <p style="text-align: justify;">Nikmati kelezatan baru! Dimsum ayam dan kopi susu, paduan sempurna untuk sensasi kuliner yang tak terlupakan. Segera coba!</p>
                    <a class="daftar-button" href="#" data-toggle="modal" data-target="#userModal">Daftar Untuk Pesan</a>
                </div>
            </div>
        </div>



        <!-- ads js -->
        <script>
            const popup = document.querySelector(".popup");
            const close = document.querySelector(".close-ads");
            const overlay = document.querySelector(".overlay");

            let loggedIn = <?php echo $this->session->userdata('pelanggan') ? 'true' : 'false'; ?>;

            window.onload = function() {
                setTimeout(function() {
                    if (!loggedIn) {
                        popup.classList.add("show");
                        overlay.classList.add("show");
                        document.body.style.overflow = 'hidden';
                    } else {
                        closePopup();
                    }
                }, 500);
            };

            close.addEventListener("click", () => {
                closePopup();
            });

            daftarButton.addEventListener("click", () => { // Tambahkan event listener untuk tombol "Daftar"
                closePopup();
            });

            document.addEventListener("click", (event) => {
                if (!popup.contains(event.target) && event.target !== overlay) {
                    closePopup();
                }
            });

            function closePopup() {
                popup.classList.remove("show");
                overlay.classList.remove("show");
                overlay.style.display = 'none';
                popup.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        </script>

        <!-- sweet alert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- jQery -->
        <script src="<?= base_url('vendor2/') ?>js/jquery-3.4.1.min.js"></script>
        <!-- popper js -->
        <script src="<?= base_url('vendor2/') ?>https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
        <!-- bootstrap js -->
        <script src="<?= base_url('vendor2/') ?>js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

        <!-- isotope js -->
        <script src="https://unpkg.com/isotope-layout@3.0.6/dist/isotope.pkgd.js"></script>

        <!-- isotope code-->
        <script>
            $(window).on('load', function() {
                $('.filters_menu li').click(function() {
                    $('.filters_menu li').removeClass('active');
                    $(this).addClass('active');

                    var data = $(this).attr('data-filter');
                    $grid.isotope({
                        filter: data
                    })
                });

                var $grid = $(".grid").isotope({
                    itemSelector: ".all",
                    percentPosition: false,
                    masonry: {
                        columnWidth: ".all"
                    }
                })
            });
        </script>

        <!-- custom js -->
        <script src="<?= base_url('vendor2/') ?>js/custom.js"></script>

        <!-- kode sweetalert2 -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Memilih semua tombol dengan kelas addToCartBtn
                var addToCartButtons = document.querySelectorAll('.addToCartBtn');

                // Menambahkan event listener ke setiap tombol
                addToCartButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        // Logika untuk menambahkan produk ke keranjang disini
                        // Misalnya, mungkin Anda ingin menampilkan pesan Toast
                        showToast();
                    });
                });
            });

            // Function to show toast message
            function showToast() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "keranjang berhasil di tambahkan",
                    customClass: {
                        title: 'toast-title' // CSS class for title
                    }
                });
            }
        </script>

        <!-- konfirmasi pesanan js -->
        <script>
            function cekPesanan() {
                var konfirmasi = confirm('Apakah pesanan sudah sesuai?');
                if (konfirmasi) {
                    // Jika pengguna menekan OK, izinkan eksekusi kode
                    return true;
                } else {
                    // Jika pengguna menekan Cancel, hentikan eksekusi kode
                    return false;
                }
            }
        </script>

        <!-- to up btn -->
        <script>
            const toTop = document.querySelector(".top-btn")
            window.addEventListener("scroll", () => {
                if (window.pageYOffset > 100) {
                    toTop.classList.add("active")
                } else {
                    toTop.classList.remove("active")
                }
            })
        </script>

        <!-- max no telp -->
        <script>
            document.getElementById("telp").addEventListener("input", function() {
                var maxLength = 12;
                var minLength = 10;

                // Memastikan panjang input tidak melebihi maxLength
                if (this.value.length > maxLength) {
                    this.value = this.value.slice(0, maxLength);
                }

                // Memastikan panjang input tidak kurang dari minLength
                if (this.value.length < minLength) {
                    this.setCustomValidity("Minimal 10 digit angka.");
                } else {
                    this.setCustomValidity("");
                }
            });
        </script>



</body>

</html>