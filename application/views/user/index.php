<!doctype html>
<html lang="ar" dir="tl">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.rtl.min.css" integrity="sha384-T5m5WERuXcjgzF8DAb7tRkByEZQGcpraRTinjpywg37AO96WoYN9+hrhDVoM6CaT" crossorigin="anonymous">

    <title>Aplikasi Sistem Login</title>
  </head>
  <body>
      <div class="container">
      <?= $this->session->flashdata('pesan'); ?>
  <div class="card mb-3 mt-4 " style="max-width: 540px;">
  
  <div class="row g-0">
    <div class="col-md-4">
      <img src="<?= base_url('assets/img/') . $user['image'];?>" class="img-fluid rounded-start" >
      

    
    
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title">My Profile</h5>
        <p class="card-text">Nama : <?= $user['nama'];?></p>
        <p class="card-text" style="margin-top: -10px;">Email : <?= $user['email'];?></p>
        
        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
        <a  href="<?= base_url('auth/logout');?>" style="margin-top:60px; float: right;" class="btn btn-danger">Logout</a>
        
      </div>
    </div>
  </div>
</div>
</div>


<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="modal-foto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-foto">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                <div class="ibox-content">
                    <?= form_open_multipart('user/tambah'); ?>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Foto</label>
                        <input id="image" name="image" type="file" class="form-control">
                    </div>

                    <button class="btn btn-primary " type="submit" name="tambah"><i class="fa fa-check"></i>&nbsp;Simpan</button>
                </div>
                
                <!-- /.card-body -->
                <?= form_close(); ?>
                
            </div>
    </div>
  </div>
  
</div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="<?= base_url() ?>assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    -->
  </body>
</html>