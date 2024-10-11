<?php
session_start();
if (empty($_SESSION['email'])) {
?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: "กรุณาล็อกอินก่อนใช้งาน!!",
      timer: 3000,
      showConfirmButton: false
    }).then(() => {
      document.location.href = 'index.php';
    });
  </script>
<?php
}
?>