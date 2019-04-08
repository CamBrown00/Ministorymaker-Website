<!--          Beginning of Nav          -->
<nav>
    <ol>
        <?php

        /* Home Page */
        print '<li class="';
        if ($path_parts['filename'] == 'index') {
            print 'activePage';
        }
        print '">';
        print '<a href="index.php">Home</a>';
        print '</li>';

        /* Template Page */
        print '<li class="';
        if ($path_parts['filename'] == 'blank') {
            print 'activePage';
        }
        print '">';
        print '<a href="blank.php">Basic</a>';
        print '</li>';

        /* Gallery Page */
        print '<li class="';
        if ($path_parts['filename'] == 'gallery') {
            print 'activePage';
        }
        print '">';
        print '<a href="gallery.php">Gallery</a>';
        print '</li>';
        
        /* Random Story/Form Page */
        print '<li class="';
        if ($path_parts['filename'] == 'form') {
            print 'activePage';
        }
        print '">';
        print '<a href="form.php">Form</a>';
        print '</li>';
        
        /* Story Sources Page */
        print '<li class="';
        if ($path_parts['filename'] == 'sources') {
            print 'activePage';
        }
        print '">';
        print '<a href="blank.php">The Originals</a>';
        print '</li>';
        
        /* Feedback Page */
        print '<li class="';
        if ($path_parts['filename'] == 'feedback') {
            print 'activePage';
        }
        print '">';
        print '<a href="blank.php">Feedback</a>';
        print '</li>';
        ?>
    </ol>
</nav>
<!--          End of Nav          -->
