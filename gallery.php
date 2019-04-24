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
    <ul class="slides">
        <li>
            <figure>
                <img src="images/storyslide1.jpg"/>
                <figcaption class="flex-caption">
                    This is the first test image.
                </figcaption>
            </figure>
        </li>
        <li>
            <figure>
                <img src="images/storyslide2.png"/>
                <figcaption class="flex-caption">
                    This is the second test image.
                </figcaption>
            </figure>
        </li>
    </ul>
</section>

<?php
    include('storySets.php');
    include('footer.php');
?>
