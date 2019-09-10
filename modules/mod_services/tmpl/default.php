<div id="modservices">
    <div id="services">
        <?php foreach ($services as $services): ?>
            <?php
            $link = 'index.php/' . $services->link;
            $backgroundPort = 'style="background-image: url(\'' . $services->image . '\')"';
            ?>
            <div class="service" <?php echo $backgroundPort; ?>>
                <a href="<?php echo $link; ?>" class="img-service">
                    <span><?php echo $services->name; ?></span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="<?= JURI::base(true) ?>/modules/mod_services/assets/js/script.js"></script>