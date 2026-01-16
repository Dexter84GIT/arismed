<?php 
    $about = get_field('about')
?>
<section class="mainAbout about section">
    <div class="container df fdc gap30">
        <?php if (!empty($about)) : ?>
            <?php echo $about; ?>
        <?php else : ?>
            <p>Текст о компании</p>    
        <?php endif; ?>
     </div>
</section>