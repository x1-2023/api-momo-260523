<script>
    $(function() {
    var url = window.location.pathname,
        urlRegExp = new RegExp(url.replace(/\/$/, '') + "$");
    $('ul li a').each(function() {
        if (urlRegExp.test(this.href.replace(/\/$/, ''))) {
            var href = $(this).parents().eq(0).attr('id');
            $(this).addClass('nav-link active');
            $('#' + href).addClass('nav-link active');
            Checkhref(href);
        }
    });

    function Checkhref(href) {
        $('ul li a').each(function() {
            if ($(this).attr('href') == "#" + href) {
                $(this).addClass('nav-link active');
            }
        });
    }
});
</script>
<footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
        Version <b style="color:red;">1.0</b>
    </div>
    <strong>Code được buôn bán bởi <a href="https://dailysieure.com" target="_blank">dailysieure.com</a></strong>
</footer>
</div>
<!-- jQuery UI 1.11.4 -->
<script src="<?=BASE_URL('public/AdminLTE3/');?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?=BASE_URL('public/AdminLTE3/');?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=BASE_URL('public/AdminLTE3/');?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=BASE_URL('public/AdminLTE3/');?>dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?=BASE_URL('public/AdminLTE3/');?>dist/js/pages/dashboard.js"></script>
<!-- ChartJS -->
<script src="<?=BASE_URL('public/AdminLTE3/');?>plugins/chart.js/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
<?=$body['footer'];?>
</body>

</html>