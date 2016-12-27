   </section><!-- #content -->
    <footer class="black bg-kcms"><div class="container"><p class="h6 m1"><?php echo '&copy; '.date('Y').' Caramnesis.com'; ?></p></div></footer>
    </div><!-- #wrapper -->
    <script src="/js/jquery-1.12.1.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <?php if ($userlang = Session::get('user_lang')) { ?>
    <script src="/js/i18n/datepicker-<?= strtolower($userlang); ?>.js"></script>
    <?php }; ?>
    <script>var lang='<?= strtolower(Session::get('user_lang')); ?>';</script>
    <script src="/js/bespoke.js"></script>
  
     
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-15258101-4', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
