<div id="modservices">
    <div id="services">
        <?php foreach ($services as $services): ?>
            <?php
            $link = 'index.php/' . $services->link;
            $backgroundPort = 'style="background: url(\'' . $services->image . '\')"';
            ?>
            <div class="service">
                <div class="item-service">
                    <h1>
                        <a href="<?php echo $link; ?>"><?php echo $services->name; ?></a>
                    </h1>
                    <a href="<?php echo $link; ?>" class="img-service" <?php echo $backgroundPort; ?>>
                    </a>
                    <div class="desc">
                        <a href="<?php echo $link; ?>">
                            <?php echo $services->description; ?>
                        </a>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {

        $(window).on('resize', function () {

            //TAMANHO IMAGEM
            var tamImg = 0;
            var img = $("#services .service .item-service a.img-service");
            img.each(function () {
                var tamImgH = $(this).width();
                if (tamImgH > tamImg) {
                    tamImg = tamImgH;
                }
            });
            $("#services .service .item-service a.img-service").height(tamImg * 0.6);

            $("#services .service .item-service").height(fixButtonHeights($("#services .service .item-service")));

        }).trigger('resize');

        function fixButtonHeights(heightDiv) {
            var heights = new Array();

            // Loop to get all element heights
            heightDiv.each(function() {
                // Need to let sizes be whatever they want so no overflow on resize
                $(this).css('min-height', '0');
                $(this).css('max-height', 'none');
                $(this).css('height', 'auto');

                // Then add size (no units) to array
                heights.push($(this).outerHeight());
            });

            // Find max height of all elements
            var max = Math.max.apply( Math, heights );

            return max;

        }
    });
</script>