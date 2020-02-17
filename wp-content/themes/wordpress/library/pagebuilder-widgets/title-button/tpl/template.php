<div class="title-button">
    <h3><?= $instance['title'] ?></h3>

    <?php if(!empty('button_url')): ?>
        <a href="<?= $instance['button_url'] ?>" class="btn btn-secondary btn-sm"><?= $instance['button_text'] ?></a>
    <?php endif; ?>
</div>