<?php
    foreach ($sql as $var) {
        $mensagem = $var->message;
        $user = $var->first_name;
    }
?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script>
    <?php if ($var != null) { ?> // Se a array retornar alguma coisa que bata com as condições exibe a notificação.

        document.addEventListener('DOMContentLoaded', function() {
            if (Notification.permission !== 'granted')
                Notification.requestPermission();
        });

        function notifyMe(icon, mensagem, link) {
            if (!Notification) {
                alert('O navegador que você está utilizando não possui o Notifications. Tente o Chrome!');
                return;
            }

            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            } else {
                var notification = new Notification(title, {
                    body: mensagem,
                    icon: icon                
                });

                notification.onclick = function() {
                    window.open(link);
                };
            }
        }

        // Variavéis
        var icon = '../assets/images/notifica.png';
        var title = '<?php echo $user ?>';
        var mensagem = '<?php echo $mensagem ?>';
        var link = "<?php echo get_uri('messages/index'); ?>";

        notifyMe(icon, mensagem, link);

        // Depois de notificar dar o update
        setTimeout(function() {
            $.ajax({
                async: false,
                method: "POST",
                url: "<?php echo get_uri('Messages/update_windows_notification'); ?>"
            });
        }, 100);
</script>

<?php }; ?>