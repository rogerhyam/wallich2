<?php

    $response = $mysqli->query("SELECT e.entry_number, i.filename, i.fid
        FROM wallich.entries as e
        JOIN wallich.fragments as f on f.entry_nid = e.drupal_nid
        JOIN wallich.files as i on i.fid = f.image_fid
        ORDER BY rand()
        LIMIT 1");
    $rows = $response->fetch_all(MYSQLI_ASSOC);
    $entry = $rows[0];

    // load the image
    $file_path = 'files/' . $entry['filename'];
    $img = imagecreatefromjpeg($file_path);
    // crop to left hand square
    $img = imagecrop($img, ['x' => 0, 'y' => 0, 'width' => imagesy($img), 'height' => imagesy($img)]);
    ob_start(); 
    imagejpeg($img);
    $image_data = ob_get_contents(); 
    ob_end_clean();
    $image_data_base64 = base64_encode($image_data);

    $src = 'data:image/jpeg;base64,'.$image_data_base64;

    echo '<h2>Feedback</h2>';

    $captcha = @$_SESSION['captcha'];
    $guess = trim(@$_GET['guess']);
    $failed = false;

        // they asked to be forgotten - good for debug
    if($guess == 'clear'){
        unset($_SESSION['captcha']);
        header("Location: index.php?section=feedback");
        exit(); 
    }

    if($captcha == 'human'){
        echo '<p>You can read Wallich\'s handwriting. You must be superhuman!</p>';
        echo '<p>Please email <a href="mailto:mwatson@rbge.org.uk?subject=Wallich Catalogue Feedback">Dr Mark Watson &lt;mwatson@rbge.org.uk&gt;</a> with your feedback.</p>';
    }else{

        if($guess){

            // they submitted a number
            if($guess && $guess > $captcha -2 && $guess < $captcha + 2 ){
                $_SESSION['captcha'] = 'human';
                header("Location: index.php?section=feedback");
                exit(); 
            }else{
                $failed = true;
            }

        }

        // they didn't submit or got it wrong.

        $_SESSION['captcha'] = $entry['entry_number']; // save it for when they come back
        
        echo '<p>We welcome your feedback but ';
        echo 'to prevent spam we need to check that you are human.';
        echo ' Please enter a catalogue entry number that is displayed in the image.</p>';

        echo '<div style="text-align: center;">';
        echo '<div>';
        echo "<img style=\"max-width: 400px\" src=\"$src\" alt=\"Page fragment\" />";
        echo '<form action="index.php" method="GET">';
        echo '<input type="hidden" name="section" value="feedback" />';
        echo '<input type="text" name="guess" value="" placeholder="Entry number in image" />';
        echo '<input type="submit" />';
        echo '&nbsp;<a href="index.php?section=feedback">Refresh</a>';

        if($failed){
            echo '<p style="color: red;">Sorry that didn\'t match. Please try again.</p>';
        }

        echo '</form>';
        echo '</div>';
        echo '</div>';


    }


?>