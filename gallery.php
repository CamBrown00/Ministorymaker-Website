<?php
    include('top.php');
?>

<h2 class='page-heading'> Image Gallery </h2>
<p class='intro'> </p>

<!-- Flexslider Controls-->
<!-- 
    Name images based on the order of stories in the csv, so the image for the
    first story would be "story1.jpg", the second story image would be called
    "story2.jpg", etc..
-->
<section class="flexslider">
    <?php
        include('gallerySlider.php');
    ?>
</section>

<?php
    include('storySets.php');
    include('footer.php');
?>
