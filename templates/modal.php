<?php if (!empty($tppmModalToAccept) && !empty($tppmDocumentsToAccept)) : ?>
    <div class="tppm-modal-overlay" id="js-tppm-modal-overlay"></div>
    <div id="js-tppm-modal" data-tppm-modal-id="<?php echo esc_attr($tppmModalToAccept[0]['ID']); ?>" 
        aria-modal="true" role="dialog" aria-live="polite" aria-labelledby="tppm-modal-header-title" aria-describedby="tppm-modal-description" class="tppm-terms-modal" style="display: none;"
    >
        <div class="tppm-modal-header">
            <div class="tppm-modal-title" id="tppm-modal-header-title">
                <?php echo esc_attr($tppmModalToAccept[0]['post_title'])?>
            </div>
        </div>

        <form action="" method="POST" id="js-tppm-submit-form">
            <div class="tppm-modal-body">
                <div class="tppm-modal-message" id="tppm-modal-description">
                    <span class="tppm-modal-description-wrapper" id="js-tppm-modal-description"><?php echo $tppmModalToAccept[0]['post_content'] ?></span>
                </div>
                <div class="tppm-modal-checkboxes">
                    <?php 
                        foreach ($tppmDocumentsToAccept as $tppmDocument) {
                    ?>
                        <div class="tppm-modal-checkbox">
                            <input type="checkbox" id="tppm-checkbox-<?php echo esc_attr($tppmDocument->term_id) ?>" class="js-tppm-modal-checkbox" value="1" required="true">
                            <label class="js-tppm-checkbox-label" for="tppm-checkbox-<?php echo esc_attr($tppmDocument->term_id) ?>">
                                <span><?php echo $tppmDocument->description ?></span>
                            </label>
                        </div>
                    <?php
                        }
                    ?>
                </div>
                <div class="tppm-errors-wrapper" id="js-tppm-errors-wrapper">
                    
                </div>
            </div>
            <div class="tppm-modal-footer">
                <button type="submit" class="tppm-modal-btn">Akceptuję</button>
            </div>
        </form>
    </div>
<?php endif; ?>

