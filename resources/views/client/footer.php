<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}?>
<?= $body['footer']; ?>
<!-- Required Js -->
<script src="<?= BASE_URL('') ?>public/assets/js/vendor-all.min.js"></script>
<script src="<?= BASE_URL('') ?>public/assets/js/plugins/bootstrap.min.js"></script>
<script src="<?= BASE_URL('') ?>public/assets/js/pcoded.min.js"></script>
<!-- Apex Chart -->
<script src="<?= BASE_URL('') ?>public/assets/js/plugins/apexcharts.min.js"></script>
<!-- custom-chart js -->
<script src="<?= BASE_URL('') ?>public/assets/js/pages/dashboard-main.js"></script>
</body>

</html>