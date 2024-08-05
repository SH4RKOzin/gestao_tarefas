<?php
session_start();
session_unset();
session_destroy();
?>
<script>
    alert("Você terminou a sessão");
</script>
<?php
header("Location: login.php");
exit();
?>
