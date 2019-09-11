<?php
$sufixo = '';
if ($params->get('moduleclass_sfx')) {
    $sufixo = '-' . $params->get('moduleclass_sfx');
}
?>
<div id="contact-social-footer">


    <?php
    //Logo
    if (!empty($logo)): ?>
        <div class="logo-footer">
            <a href="index.php">
                <img src="<?php echo $logo; ?>" alt="<?php echo $sitename; ?>">
            </a>
        </div>
    <?php endif; ?>

    <ul class="contact-social-footer footer<?= $params->get('moduleclass_sfx')?>">

        <?php
        //E-mail
        if (!empty($email)): ?>
        <li>
            <a href="mailto:<?= $email; ?>" target="_blank" class="email">
                <?= $email; ?>
            </a>
        </li>
        <?php endif;


        //Telefone
        if (!empty($phone)): ?>
            <li>
                <a href="tel:+<?php echo preg_replace("/[^0-9]/", "", $phone); ?>" target="_blank" class="phone">
                    <?= $phone ?>
                </a>
            </li>
        <?php endif;



        //Facebook
        if (!empty($facebook)): ?>
            <li>
                <a href="<?php echo $facebook; ?>" target="_blank" class="facebook-footer">
                    <i class="fab fa-facebook-square"></i>
                </a>
            </li>
        <?php endif;

        //Youtube
        if (!empty($youtube)): ?>
            <li>
                <a href="<?php echo $youtube; ?>" target="_blank" class="youtube-footer">
                    <i class="fab fa-youtube-square"></i>
                </a>
            </li>
        <?php endif;

        //Linkedin
        if (!empty($linkedin)): ?>
            <li>
                <a href="<?php echo $linkedin; ?>" target="_blank" class="linkedin">
                    <i class="fab fa-linkedin"></i>
                </a>
            </li>
        <?php endif;

        //Instagram
        if (!empty($instagram)): ?>
            <li>
                <a href="<?php echo $instagram; ?>" target="_blank" class="instagram-footer">
                    <i class="fab fa-instagram"></i>
                </a>
            </li>
        <?php endif;

        //Twitter
        if (!empty($twitter)): ?>
            <li>
                <a href="<?php echo $twitter; ?>" target="_blank" class="twitter-footer">
                    <i class="fab fa-twitter-square"></i>
                </a>
            </li>
        <?php endif;

        //WhatsApp
        if (!empty($whatsapp)):
            //Identifica a hora para aparecer o WhatsApp
            date_default_timezone_set('America/Sao_Paulo');
            $myHour = date('H:i:s');
            if ((strtotime($myHour) <= strtotime('18:00:00')) && (strtotime($myHour) >= strtotime('08:00:00'))):
                ?>
                <li>
                    <a href="https://api.whatsapp.com/send?phone=+55<?php echo $whatsappNumber; ?><?php if (!empty($whatsapp_msg)) echo '&text=' . $whatsapp_msg; ?>" target="_blank" class="whatsapp-footer">
                        <i class="fab fa-whatsapp-square"></i>
                    </a>
                </li>
                <?php
            endif;
        endif;
        ?>
    </ul>
</div>