<!--          Beginning of Nav          -->
<nav class="idle" id="navbar">
    <ul>
        <?php

        /* Home Page */
        print '<li class="';
        if ($path_parts['filename'] == 'index') {
            print 'activePage';
        }
        print '">';
        print '<a href="index.php">Home</a>';
        print '</li>';

        /* Make a Story */
        print '<li class="';
        if ($path_parts['filename'] == 'makeastory') {
            print 'activePage';
        }
        print '">';
        print '<a href="makeastory.php">Make a Story</a>';
        print '</li>';
        
        /* Read a Story Page */
        print '<li class="';
        if ($path_parts['filename'] == 'gallery') {
            print 'activePage';
        }
        print '">';
        print '<a href="gallery.php">Read a Story</a>';
        print '</li>';
        
        /* About Page */ 
        print '<li class="';
        if ($path_parts['filename'] == 'about') {
            print 'activePage';
        }
        print '">';
        print '<a href="about.php">About</a>';
        print '</li>';
        
        /* Feedback Page */
        print '<li class="';
        if ($path_parts['filename'] == 'feedback') {
            print 'activePage';
        }
        print '">';
        print '<a href="feedback.php">Feedback</a>';
        print '</li>';
        ?>
    </ul>
</nav>
<!--          End of Nav          -->