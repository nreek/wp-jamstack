<SmartPodcast 
title="<?= $instance['title'] ?>"
description="<?= $instance['description'] ?>"
image="<?= wp_get_attachment_image_src($instance['image'])[0] ?>"
podcast_url="<?= $instance['podcast_url'] ?>"
:button="{
    text : '<?= $instance['button_text'] ?>',
    url : '<?= $instance['button_url'] ?>'
}"
>
    <!-- <slot name="links"></slot> -->
</SmartPodcast>