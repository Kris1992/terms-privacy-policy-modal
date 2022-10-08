<div class="tppm">
    <h3>Zgody - Regulamin, Polityka prywatności</h3>
    <div class="table-wrapper">
        <span>Użytkownik zaakceptował:</span>
        <table class="table">
            <thead>
                <tr>
                    <th>Nazwa pliku</th>
                    <th>Wersja</th>
                    <th>Data</th>
                    <th>Godzina</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (!isset($tppmFinalDocumentsData) || empty($tppmFinalDocumentsData)):
                ?>
                    <tr>
                        <td colspan="4">Brak danych o zaakceptowanych dokumentach</td>
                    </tr>
                <?php 
                    else:
                        foreach ($tppmFinalDocumentsData as $tppmFinalDocumentData) {
                        ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $tppmFinalDocumentData['attachmentLink'] ?>" target="_blank" download>
                                        <?php echo $tppmFinalDocumentData['attachmentName'] ?>
                                    </a>
                                </td>
                                <td><?php echo $tppmFinalDocumentData['version'] ?></td>
                                <td><?php echo $tppmFinalDocumentData['printDate'] ?></td>
                                <td><?php echo $tppmFinalDocumentData['printTime'] ?></td>
                            </tr>
                        <?php
                        }
                    endif;
                ?>
            </tbody>


        </table>
    </div>
</div>
