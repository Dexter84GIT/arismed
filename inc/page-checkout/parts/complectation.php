
<div class="tabContent block table df fdc">  
   <div class="row legend df aic gap30 jcsb">
        <p>Наименование</p>
        <p>Количество</p>
    </div>
    <?php if (have_rows("list")) : ?>
        <ol class="list">
            <?php while (have_rows("list")) : the_row(); ?>
                    <li class="row item df aic gap10">
                <?php if (get_sub_field('name')) : ?>
                    <p class="name"><?php the_sub_field("name") ?></p>
                <?php endif; ?>
                <?php if (get_sub_field('number')) : ?>
                    <p class="number"><?php the_sub_field("number") ?></p>
                <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ol>
    <?php endif; ?>
</div>