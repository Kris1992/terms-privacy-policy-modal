<div class="tppm wrap">
    <h1>Zarządzanie dokumentami</h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>"><a href="#tab-1">Moje dokumenty</a></li>
        <?php if (isset($allowDocumentsActions) && $allowDocumentsActions) : ?>
            <li class="<?php echo isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>">
                <a href="#tab-2">
                    <?php echo isset($_POST["edit_taxonomy"]) ? 'Edytuj' : 'Dodaj' ?> dokument
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane <?php echo !isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>">

            <h3>Lista dokumentów</h3>

            <?php 
                $options = get_option('terms_privacy_policy_modal_documents') ? get_option('terms_privacy_policy_modal_documents') : [];

                echo '<table class="custom-table"><tr><th>Typ dokumentu</th><th>Nazwa</th><th class="text-center">Wyświetlane</th><th class="text-center">Akcje</th></tr>';

                foreach ($options as $option) {
                    $hierarchical = isset($option['hierarchical']) ? 'TAK' : 'NIE';

                    echo "<tr><td>{$option['taxonomy']}</td><td>{$option['singular_name']}</td><td class=\"text-center\">{$hierarchical}</td><td class=\"text-center\">";

                    if (isset($allowDocumentsActions) && $allowDocumentsActions) {
                        echo '<form method="POST" action="" class="inline-block">';
                        echo '<input type="hidden" name="edit_taxonomy" value="' . $option['taxonomy'] . '">';
                        submit_button( 'Edytuj', 'primary small', 'submit', false);
                        echo '</form> ';

                        echo '<form method="POST" action="options.php" class="inline-block">';
                        settings_fields('terms_privacy_policy_modal_documents_option_group');
                        echo '<input type="hidden" name="remove" value="' . $option['taxonomy'] . '">';
                        submit_button( 'Usuń', 'delete small', 'submit', false, [
                            'onclick' => 'return confirm("Jesteś pewny, że chcesz usunąć dokument? Dane powiązane z nim zostaną utracone");'
                        ]);
                        echo '</form>';
                    }
                    echo '</td></tr>';
                }

                echo '</table>';
            ?>
            
        </div>

        <div id="tab-2" class="tab-pane <?php echo isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>">
            <form method="POST" action="options.php">
                <?php 
                    settings_fields('terms_privacy_policy_modal_documents_option_group');
                    do_settings_sections('terms_privacy_policy_modal_documents');
                    submit_button();
                ?>
            </form>
        </div>
    </div>
</div>
