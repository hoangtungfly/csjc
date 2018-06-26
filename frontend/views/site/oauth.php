<p>This view will now self-destruct <?= $code ?></p>
<script>
   try {
      window.opener.$windowScope.handlePopupAuthentication('<?= $code ?>');
   } catch(err) {}
   window.close();
</script>