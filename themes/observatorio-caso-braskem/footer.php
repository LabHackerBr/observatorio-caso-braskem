</div>
<?php wp_reset_postdata() ?>
<?php if ( is_active_sidebar( 'footer_widgets' ) ):?>
    <footer class="main-footer">
        <div class="main-footer__widgets container">
            <?php dynamic_sidebar('footer_widgets') ?>
        </div>
        <div class="main-footer__legal">
            <div class="main-footer__legal-row">
                <span>
                    <a class="termos-uso" href="<?php get_site_url()?>/politica-de-privacidade" target="_blank"><span><?php _e('Terms of use', 'hacklabr') ?></span></a>
                    <p>e</p>
                    <a class="privacidade" href="<?php get_site_url()?>/politica-de-privacidade" target="_blank"><span><?php _e('Privacy Policy', 'hacklabr') ?></span></a>
                </span>

                <?php get_template_part( 'template-parts/site-by-hacklab' ); ?>
            </div>
        </div>
    </footer>
<?php endif; ?>
<?php wp_footer() ?>

</body>
</html>
