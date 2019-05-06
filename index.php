<?php
    include('top.php');
?>
    <main>
        <section class="introSection">
            <h1 class="introHeading">"What is Mini Story Maker?"</h1>
            <p class="aboutText">
                We're a web site devoted to bringing an interactive aspect to reading
                classic children's stories. We use a random story generator that 
                takes sentences from classic children stories and let's you write the ending.
                If you're also interested in reading the 
                original stories themselves, we've got that covered too!
            </p>
        </section>
        <article id="intro-article">
            <img class="home-img-1" alt="" src="images/home-img-1.jpg">
            <p class='intro'>
                There are countless classic children's stories out there, 
                all with their own themes and characters. But what would 
                happen if these stories were combined in random, and sometimes 
                funny ways? That's what Mini Story Maker is for! 
            </p>   
        </article>
        <article>
            <img class="home-img-2" alt="" src="images/home-img-2.jpg">
            <p>
                Some stories will make sense, and some will be completely ridiculous! That's 
                where you come in. Write a sentence or two to complete your new 
                story and tie the plots together. Can you make the story make sense? 
                <a href="makeastory.php">Try now!</a>
            </p>
        </article>
    </main>

    <!-- Contents of csv file are displayed here -->
    <?php
        include('footer.php');
    ?>
</body>
</html>