<?php $active = JFactory::getApplication()->getMenu()->getActive(); ?>
<div id="banner-int3">
    <div class="banner-int3">
        <div class="banner3" style="background-image: url(<?php echo $imagem1; ?>);">
        </div>
        <div class="banner3" style="background-image: url(<?php echo $imagem2; ?>);">
        </div>
        <div class="banner3" style="background-image: url(<?php echo $imagem3; ?>);">
        </div>
        <div class="overlay"></div>
        <h1><?php echo !empty($active->title) ? $active->title : 'Clube Jaraguá'; ?></h1>
    </div>
</div>