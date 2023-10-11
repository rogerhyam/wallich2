<?php

    // we need to load the entry even if we have been passed a subentry 
    if(@$_GET['sub_entry'] == 'true'){
        $safe_nid = $mysqli->real_escape_string($_GET['id']);
        $response = $mysqli->query("SELECT entry_number FROM sub_entries WHERE drupal_nid = $safe_nid;");
        $sub_entries = $response->fetch_all(MYSQLI_ASSOC);
        $response->close();
        if(count($sub_entries) < 1){
            echo "Failed to pull sub entry from db.";
            exit;
        }
        $entry_number = $sub_entries[0]['entry_number'];
    }else{
        $entry_number = $mysqli->real_escape_string(@$_GET['id']);
    }
    
    // fail disgracefully.
    if(!$entry_number) $entry_number = 1;

    // Lets actually load the entry
    $response = $mysqli->query("SELECT e.*, i.filename, p.page_number 
        FROM wallich.entries as e
        JOIN wallich.fragments as f on f.entry_nid = e.drupal_nid
        JOIN wallich.files as i on i.fid = f.image_fid
        JOIN wallich.pages as p on p.nid = page_node_id
        WHERE e.entry_number = $entry_number;");
    $entries = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();
    if(count($entries) != 1){
        echo "Failed to pull entry from db.";
        exit;
    }
    $entry = $entries[0];

    // load the sub entries
    $response = $mysqli->query("SELECT e.*, i.filename 
        FROM wallich.sub_entries as e
        JOIN wallich.fragments as f on f.entry_nid = e.drupal_nid
        JOIN wallich.files as i on i.fid = f.image_fid
        WHERE e.entry_number = $entry_number
        order by `order` ;");
    $sub_entries = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();

    // we need to normalize out the locations and collector tids
    // the subentries will be repeating otherwise.
    $normals = array();
    foreach ($sub_entries as $sub) {

        if(!isset($normals[$sub['drupal_nid']])){
            // if we don't have it already we add it
            $new_sub = $sub;
            $new_sub['location_tid'] = $sub['location_tid'] ? array($sub['location_tid']) : array();
            $new_sub['collector_tid'] = $sub['collector_tid'] ? array($sub['collector_tid']) : array();
            $normals[$sub['drupal_nid']] = $new_sub;
        }else{
            // we have it so we add the new values
            $normals[$sub['drupal_nid']]['location_tid'][] = $sub['location_tid'];
            $normals[$sub['drupal_nid']]['location_tid'] = array_unique($normals[$sub['drupal_nid']]['location_tid']);
            $normals[$sub['drupal_nid']]['collector_tid'][] = $sub['collector_tid'];
            $normals[$sub['drupal_nid']]['collector_tid'] = array_unique($normals[$sub['drupal_nid']]['collector_tid']);
        }
        
    }
    $sub_entries = array_values($normals);


?>
<div id="wallich-next-prev-entry">
    <?php

    // select the entries
    $response = $mysqli->query("SELECT * FROM entries WHERE entry_number < $entry_number ORDER BY entry_number DESC LIMIT 1;");
    $prevs = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();
    if(count($prevs) == 1){
        echo "<a href=\"index.php?section=entries&id={$prevs[0]['entry_number']}\" >&lt; Previous</a>&nbsp;";
    }

    // select the entries
    $response = $mysqli->query("SELECT * FROM entries WHERE entry_number > $entry_number ORDER BY entry_number ASC LIMIT 1;");
    $nexts = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();
    if(count($nexts) == 1){
        echo "<a href=\"index.php?section=entries&id={$nexts[0]['entry_number']}\" >Next &gt;</a>&nbsp;";
    }

/*
    if($entry_number < 9148){
        $next = $entry_number +1 ;
        echo "<a href=\"index.php?section=pages&number=$next\" >Next &gt;</a>";
    }
*/
?>
</div>

<a id="main-content"></a>
<h1 class="page__title title" id="page-title">
    <?php echo $entry['entry_number'] . ': ' . $entry['taxon_name'] . ' ' . $entry['author_name'] ?></h1>
<article class="node-10530 node node-entry node-promoted view-mode-full clearfix">
    <!-- entry -->
    <div class="wallich-entry wallich-editorial-status-complete">
        <div class="wallich-entry-fragment">
            <img src="files/<?php echo $entry['filename'] ?>" alt="Page fragment" />
        </div>
        <div class="wallich-entry-details">
            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Entry:</strong>
                <span><?php echo $entry['entry_number'] ?></span>
            </div>
            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Page:</strong>
                <span><a
                        href="index.php?section=pages&id=<?php echo $entry['page_node_id'] ?>"><?php echo $entry['page_number'] ?></a></span>
            </div>
            <div class="wallich-verbatim">
                <p><?php echo $entry['verbatim'] ?></p>
            </div>

            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Taxon:</strong>
                <span><?php echo $entry['taxon_name'] ?></span>
            </div>

            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Authority:</strong>
                <span><?php echo $entry['author_name'] ?></span>
            </div>

            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">IPNI:</strong>
                <a href="https://www.ipni.org/n/<?php echo $entry['ipni_id'] ?>"
                    target="ipni"><?php echo $entry['ipni_id'] ?></a>
            </div>

            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Notes:</strong>
                <span><?php echo $entry['notes'] ?></span>
            </div>
        </div>

        <?php render_specimens($entry['drupal_nid']); ?>

    </div>

    <!-- subentries -->

    <div class="wallich-subentries">

        <?php
        foreach ($sub_entries as $sub_entry) {

            // need to fetch the page number if it is different
            if($sub_entry['page_nid'] == $entry['page_node_id']){
                $page_number = $entry['page_number'];
            }else{
                $response = $mysqli->query("SELECT page_number FROM pages WHERE nid = {$sub_entry['page_nid']};");
                $sub_entry_pages = $response->fetch_all(MYSQLI_ASSOC);
                $response->close();
                $page_number = $sub_entry_pages[0]['page_number'];
            }
    ?>

        <div class="wallich-entry-sub wallich-editorial-status-complete">

            <div class="wallich-entry-fragment">
                <img src="files/<?php echo $sub_entry['filename'] ?>" alt="Page fragment" />
            </div>

            <div class="wallich-entry-sub-details">
                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Collection:</strong>
                    <span><?php echo $sub_entry['title'] ?></span>
                </div>
                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Page:</strong>
                    <span><a
                            href="index.php?section=pages&id=<?php echo $sub_entry['page_nid'] ?>"><?php echo $page_number ?></a></span>
                </div>
                <div class="wallich-verbatim"><?php echo $sub_entry['verbatim'] ?></div>
                <?php
                    // fetch the location
                    if(count($sub_entry['location_tid']) > 0 ){
                        $sql = "SELECT * FROM gazetteer WHERE tid in (". implode(',' , $sub_entry['location_tid'])  .");";
                        $response = $mysqli->query($sql);
                        if($mysqli->error){
                            echo $mysqli->error;
                            echo $sql;
                        }

                        $locations = $response->fetch_all(MYSQLI_ASSOC);
                        $response->close();

                        echo '<div class="wallich-entity-field">';
                        echo '<strong class="wallich-entity-field-title">Location:</strong>';
                        $first = true;
                        foreach ($locations as $location) {
                            if(!$first) echo ' - ';
                            $first = false;
                            echo '<span class="wallich_pop_trigger" onclick="popUpDescription(this)">';
                            echo $location['name'];
                            echo '<div style="display:none;"><p><strong>Location: </strong>';
                            echo $location['description'];
                            echo '</div>';
                            echo '</span>';
                        }
                        echo '</div>';

                    } // check for locations

                    // do the collectors too
                    if($sub_entry['collector_tid']){

                        // fetch the collectors
                        $response = $mysqli->query("SELECT * FROM collectors WHERE tid in  (". implode(',', $sub_entry['collector_tid'])  .");");
                        $collectors = $response->fetch_all(MYSQLI_ASSOC);
                        $response->close();    

                        echo '<div class="wallich-entity-field">';
                        echo '<strong class="wallich-entity-field-title">Collector:</strong>';
                        $first = true;
                        foreach($collectors as $collector){

                            if(!$first) echo ' - ';
                            $first = false;
                            
                            echo '<span class="wallich_pop_trigger" onclick="popUpDescription(this)">';
                            echo $collector['name'];
                            echo '<div style="display:none;"><p><strong>Collector: </strong>';
                            echo $collector['description'];
                            echo '</div>';
                            echo '</span>';
                        }
                        echo '</div>';

                    } // collector check
                ?>

                <?php if($sub_entry['year']){ ?>
                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Year:</strong>
                    <span><?php echo $sub_entry['year']?></span>
                </div>
                <?php } // end year check ?>
            </div>

            <?php render_specimens($sub_entry['drupal_nid']); ?>

            <?php
            } // end foreach subentry
            ?>

        </div><!-- end entry details -->
    </div><!-- sub entries -->

</article>

<?php
function render_specimens($entity_id){

    global $mysqli;

    // fetch the specimens
    $response = $mysqli->query("SELECT * FROM specimens WHERE sub_entry_tid = $entity_id;");
    $specimens = $response->fetch_all(MYSQLI_ASSOC);
    $response->close();    
    if(count($specimens) == 0) return "<!-- no specimens -->";

?>
<div style=" background-color: none; margin-left: -5px; margin-bottom: 5px; ">
    <table class="wallich-specimens">
        <tbody>
            <tr>
                <th class="wallich-specimens-header">Associated Specimen</th>
                <th class="wallich-specimens-header">Herbarium</th>
                <th class="wallich-specimens-header">Stable URI</th>
            </tr>
            <?php
    foreach ($specimens as $specimen) {

        switch (substr($specimen['barcode'], 0, 1)) {
            case 'E':
                $stable_uri = "https://data.rbge.org.uk/herb/" . $specimen['barcode'];
                $target = "Edinburgh";
                break;
            case 'K':
                $stable_uri = "http://specimens.kew.org/herbarium/" . $specimen['barcode'];
                $target = "Kew";
                break;
            case 'B':
                $stable_uri = "http://herbarium.bgbm.org/object/" . $specimen['barcode'];
                $target = "Berlin";
                break;
            default:
                # code...
                break;
        }

        echo "<tr>";
        echo "<td>{$specimen['barcode']}</td>";
        echo "<td>{$specimen['herbarium_name']}</td>";
        echo "<td><a target=\"$target\" href=\"$stable_uri\">{$stable_uri}</a></td>";
        echo "</tr>";
    }
?>

        </tbody>
    </table>

</div>
<?php
}
?>