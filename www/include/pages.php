<?php

    // which page are we looking at
    $page_id = @$_GET['id'];
    if(!$page_id) $page_id = 1032; // fixme

    // mysql to get the page
    $page_id_safe = $mysqli->real_escape_string($page_id);
    $response = $mysqli->query("SELECT p.*, f.filename
                FROM pages AS p 
                JOIN files as f on f.fid = p.image_fid 
                WHERE p.nid = $page_id_safe");
    $rows = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();
    if(count($rows) != 1){
        echo "Failed to fetch page with nid = $page_id";
        exit;
    }
    
    $image_url = 'files/'. $rows[0]['filename'];
    $page_width = $rows[0]['image_width'];
    $page_height = $rows[0]['image_height'];

    $page_display_width = 1000;
    $page_display_height = $page_height * (1000/$page_width);
    $page_title = $rows[0]['title'];

    //echo "<img src=\"$image_url\"/>" ;

    // get a list of all the entries on this page
    $response = $mysqli->query("SELECT e.*, f.page_x, f.page_y
        FROM wallich.entries as e
        JOIN wallich.fragments as f on f.entry_nid = e.drupal_nid
        WHERE page_node_id = $page_id_safe;");
    $entries = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();
    //print_r($fragments);

    // get a list of the subentries
    $response = $mysqli->query("SELECT e.*, f.page_x, f.page_y  
        FROM wallich.sub_entries as e
        JOIN wallich.fragments as f on f.entry_nid = e.drupal_nid
        WHERE e.page_nid =  $page_id_safe;");
    $sub_entries = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();

    $rendered_fragments = array();

    //print_r($sub_entries);

   // echo "<img src=\"$image_url\" />";


?>
<a id="main-content"></a>
<h1 class="page__title title" id="page-title" style="width:100%;white-space: nowrap;"><?php echo $page_title  ?></h1>

<article class="node-1032 node node-catalogue-page node-promoted view-mode-full clearfix" about="/node/1032"
    typeof="sioc:Item foaf:Document">

    <header>
        <span property="dc:title" content="Page 001" class="rdf-meta element-hidden"></span><span
            property="sioc:num_replies" content="0" datatype="xsd:integer" class="rdf-meta element-hidden"></span>
        <p class="submitted">
            <span property="dc:date dc:created" content="2013-08-05T17:18:00+01:00" datatype="xsd:dateTime"
                rel="sioc:has_creator">Submitted by <span class="username" xml:lang="" about="/user/1"
                    typeof="sioc:UserAccount" property="foaf:name" datatype="">admin</span> on <time pubdate
                    datetime="2013-08-05T17:18:00+01:00">Mon, 08/05/2013 - 17:18</time></span>
        </p>

    </header>

    <div class="wallich_page_image_wrapper">
        <div class="wallich_page_image"
            style="width: <?php echo $page_display_width ?>px; height: <?php echo $page_display_height ?>px; background-size: contain; background-repeat: no-repeat; background-image:url( '<?php echo $image_url ?>' )">

            <?php 
                // render the fragments dotted over the page
              
                foreach ($entries as $entry) {

                    $link_text = $entry['entry_number'];
                    $link_text_em = strlen($link_text);
                    if($link_text_em > 2) $link_text_em = floor($link_text_em * 0.8);

                    echo "<a ";
                    echo 'href="index.php?section=entries&sub_entry=false&id=' . $entry['drupal_nid'] .'" '; // fixme different for entry or sub entry?
                    echo 'title="' . strip_tags( $entry['verbatim'] ) . '" ';
                    echo 'style="top:' . $entry['page_y'] . '%; left:' . $entry['page_x'] . '%; width: ' . $link_text_em . 'em; margin-left: -0.33em" />' ;
                    echo $link_text;
                    echo '</a>';

                    $rendered_fragments[] = $entry['page_x'] . '-' . $entry['page_y'];

                }

                foreach ($sub_entries as $sub_entry) {

                    $link_text = $sub_entry['entry_number'] . '.' . $sub_entry['entry_number_qualifier'];
                    $link_text_em = strlen($link_text);
                    if($link_text_em > 2) $link_text_em = floor($link_text_em * 0.8);

                    $left = $sub_entry['page_x'];
                    $top = $sub_entry['page_y'];

                    // if we have already rendered the fragment we shift the label down a bit
                    if(in_array($left.'-'.$top, $rendered_fragments)){
                        $top += 2;
                    }

                    echo "<a ";
                    echo 'href="index.php?section=entries&sub_entry=true&id=' . $sub_entry['drupal_nid'] .'" '; // fixme different for entry or sub entry?
                    echo 'title="' . strip_tags( $sub_entry['verbatim'] ) . '" ';
                    echo 'style="top:' . $top . '%; left:' . $left . '%; width: ' . $link_text_em . 'em; margin-left: -0.33em" />' ;
                    echo $link_text;
                    echo '</a>';

                }

            ?>
        </div>
        <div class="wallich_page_nav">
            <select id="wallich_page_jump" onchange="document.location = 'index.php?section=pages&id=' + this.value">

                <?php
    // get a list of all the pages.
    $response = $mysqli->query("SELECT p.*, f.filename
            FROM wallich.pages AS p 
            JOIN wallich.files as f on f.fid = p.image_fid 
            order by page_number asc;");
    $pages = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();

    foreach ($pages as $page) {
        $selected = $page['nid'] == $page_id ? 'selected' : '';
        echo "\t<option $selected value=\"{$page['nid']}\">Page: {$page['page_number']}</option>\n";
    }

    echo "</select>";

    // next/previous page buttons
    for ($i=0; $i < count($pages) ; $i++) { 

        // go till we find the current page
        if($pages[$i]['nid'] != $page_id) continue;

        // if we are not on the first render the previous one
        if($i > 0){
            echo "<a href=\"index.php?section=pages&id={$pages[$i-1]['nid']}\">";
            echo '<div class="wallich_page_next_prev">';
            echo "<img typeof=\"foaf:Image\" src=\"files/{$pages[$i-1]['filename']}\" alt=\"previous page\" />";
            echo "<div class=\"wallich_page_number\">Page {$pages[$i-1]['page_number']}</div>";        
            echo '</div>';
            echo '</a>';    
            
        }

        // if we are not on the last render the next one
        if($i < count($pages) -1){
            echo "<a href=\"index.php?section=pages&id={$pages[$i+1]['nid']}\">";
            echo '<div class="wallich_page_next_prev">';
            echo "<img typeof=\"foaf:Image\" src=\"files/{$pages[$i+1]['filename']}\" alt=\"previous page\" />";
            echo "<div class=\"wallich_page_number\">Page {$pages[$i+1]['page_number']}</div>";        
            echo '</div>';
            echo '</a>';    
        }

    }

?>
        </div>
    </div><!-- image wrap -->

</article>
<div style="clear:both">
    <!-- could add a list of entries here but it seems redundant -->
</div>