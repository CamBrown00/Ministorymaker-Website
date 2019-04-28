<?php
    include('top.php');
?>

<h2 class='page-heading'> Image Gallery </h2>
<p class='intro'> </p>

<!-- Flexslider include -->
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
<button id="extender-button" type="button">Read More</button>

<script>
    var stories = document.getElementsByClassName("flex-caption");
    var button = document.getElementById("extender-button");
    
    button.onclick = function(){
        for (var i = 0; i < stories.length; i++){
            if (stories[i].id === "story-content-max"){
                stories[i].id = "story-content-min";
                button.innerHTML = "Read More";   
            }else{
                stories[i].id = "story-content-max";
                button.innerHTML = "Minimize";
            }
        }
        $(window).load(function() {
            $('.flexslider').flexslider({
                animation: "slide",
                slideshow: false,
                animationLoop: false
            });
        });
    };
    
    
</script>

<?php
    include('footer.php');
?>
