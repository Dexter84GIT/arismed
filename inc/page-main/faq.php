<section class="faq section mainFaq">
    <div class="container df fdc gap30">
        <div class="top df aic jcsb">
            <h2 class="sectionTitle druk">Вопрос-ответ</h2>
        </div>
        <div class="content df fdc accordeon gap20">
        <?php if(have_rows("faq")): ?>
            <?php while(have_rows('faq')): the_row(); 
                 $question = get_sub_field('question'); 
                 $answer = get_sub_field('answer')?>
                        <div class="item df fdc">
                            <div class="arrow">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5.72665 11.06L8.77999 8L5.72665 4.94L6.66665 4L10.6667 8L6.66665 12L5.72665 11.06Z" />
                                </svg>
                            </div>
                            <div class="question druk"><?php echo $question; ?></div>
                            <div class="answer df fdc gap20">
                                <?php echo $answer; ?>
                            </div>
                        </div>
            <?php endwhile; ?> 
        <?php endif; ?>
        </div>
    </div>
</section>