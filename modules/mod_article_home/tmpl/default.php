<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_article_single
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$sufixo = '';
if ($params->get('moduleclass_sfx')) {
    $sufixo = '-' . $params->get('moduleclass_sfx');
}
?>
<div id="article-home" class="article-home<?php echo $sufixo; ?>">
    <?php foreach ($article as $article): ?>
        <div class="article-home">
            <div class="description">
                <h1>
                    <?= $article->title ?>
                </h1>
                <div class="text-description">
                    <?php
                    $start = strpos($article->introtext, '<p>');
                    $end = strpos($article->introtext, '</p>', $start);
                    $paragraph = substr($article->introtext, $start, $end-$start+4);
                    ?>
                    <?= $paragraph ?>
                    <a class="btn-simple btn-linkpage" href="<?php echo JRoute::_("index.php?Itemid={$link}"); ?>">
                        <?= $button_link ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<script>
    jQuery('document').ready(function ($) {
        $(window).on('resize', function () {
            $('#article-home .destination').height(parseInt($('#article-home .destination').width() * 0.6));
            $('#article-home .description').height($('#article-home .all-destination').height());
        }).trigger('resize');
    });
</script>