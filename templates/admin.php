<div class="wrap">
    <h1>Konfiguracja</h1>
    <?php settings_errors(); ?>

    <form method="POST" action="options.php">
        <?php
            settings_fields('terms_privacy_policy_modal_option_group');
            do_settings_sections('terms_privacy_policy_modal');
            submit_button();
        ?>
    </form>
</div>
