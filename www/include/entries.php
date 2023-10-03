<?php

    // we need to load the entry even if we have been passed a subentry 
    if(@$_GET['sub_entry'] == 'true'){
        $safe_nid = $mysqli->real_escape_string($_GET['id']);
        $response = $mysqli->query("SELECT entry_number FROM sub_entries WHERE drupal_nid = $safe_nid;");
        $sub_entries = $response->fetch_all(MYSQLI_ASSOC);
        $response->close();
        if(count($sub_entries) != 1){
            echo "Failed to pull sub entry from db.";
            exit;
        }
        $entry_number = $sub_entries[0]['entry_number'];
    }else{
        $entry_number = $mysqli->real_escape_string(@$_GET['id']);
    }
    
    // fail disgracefully.
    if(!$entry_number){
        echo "No entry nid found;";
        exit;
    }

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

?>
<!-- FIXME NEXT PREVIOUS -->
<div id="wallich-next-prev-entry"><a href="/node/10529" title="34: Lomaria secunda Wall.">&lt; Previous</a> <a
        href="/node/10531" title="36: Lomaria scandens Willd.">Next &gt;</a></div>

<a id="main-content"></a>
<h1 class="page__title title" id="page-title">
    <?php echo $entry['entry_number'] . ': ' . $entry['taxon_name'] . ' ' . $entry['author_name'] ?></h1>
<article class="node-10530 node node-entry node-promoted view-mode-full clearfix">
    <!-- entry -->
    <div class="wallich-entry wallich-editorial-status-complete">
        <div class="wallich-entry-fragment">
            <img typeof="foaf:Image" src="files/<?php echo $entry['filename'] ?>" alt="Page fragment" />
        </div>
        <div class="wallich-entry-details">
            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Entry:</strong>
                <span><?php echo $entry['entry_number'] ?></span>
            </div>
            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Page:</strong>
                <span><a href="index.php?section=pages&id=<?php echo $entry['page_node_id'] ?>"
                        class="referenced-node-link"><?php echo $entry['page_number'] ?></a></span>
            </div>
            <div class="wallich-verbatim">
                <p>Lomaria ? limonifolia, Wall.</p>
            </div>

            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Taxon:</strong>
                <span>Lomaria limonifolia</span>
            </div>

            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Authority:</strong>
                <span>Wall.</span>
            </div>

            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">IPNI:</strong>
                <span class="wallich_pop_trigger" data-ipni="17139630-1">17139630-1<div class="wallich_pop_box"
                        style="height:5em; top:-6em;">Loading ...</div></span>
            </div>



            <div class="wallich-entity-field">
                <strong class="wallich-entity-field-title">Specimens:</strong>
                <span>0</span>
            </div>

        </div>

        <div class="wallich-specimens-wrapper wallich-specimens-wrapper-entry" id="wallich-specimens-10530">
            <table class="wallich-specimens">
                <tr>
                    <th class="wallich-specimens-header">Herbarium</th>
                    <th class="wallich-specimens-header">Specimen Barcode</th>
                    <th class="wallich-specimens-header">Stable URI</th>
                </tr>
                <tbody>
                </tbody>
            </table>
        </div>


    </div>

    <!-- subentries -->

    <div class="wallich-subentries">
        <div class="wallich-entry-sub wallich-editorial-status-complete"><a name="sub_entry_61223"></a>
            <div class="wallich-entry-fragment">
                <img typeof="foaf:Image"
                    src="http://wallich.rbge.info/sites/wallich.rbge.info/files/styles/wide_fragment/public/catalogue_page_fragments_34?itok=oidPNhZn"
                    alt="" />
            </div>



            <div class="wallich-entry-sub-details">


                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Collection:</strong>
                    <span>35.[1]: Lomaria limonifolia Wall.</span>
                </div>


                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Page:</strong>
                    <span><a href="/node/1033">2</a></span>
                </div>

                <div class="wallich-verbatim">Singapore 1822 (frond. sterilaes)</div>

                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Location:</strong>
                    <span class="wallich_pop_trigger">Singapore<div class="wallich_pop_box"
                            style="height:3em; top: -5em;">
                            <p>Singapore (Republic of Singapore)</p>
                        </div></span>
                </div>



                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Collector:</strong>
                    <span class="wallich_pop_trigger">Wallich, N.<div class="wallich_pop_box"
                            style="height:10em; top: -12em;">
                            <p>Nathaniel <strong>Wallich </strong>(1786-1854)</p>
                            <p>Superintendent at the EIC&#39;s Botanic Garden at Sibpur, near Calcutta, India.</p>
                        </div></span>
                </div>

                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Year:</strong>
                    <span>1822</span>
                </div>



                <div class="wallich-entity-field">
                    <strong class="wallich-entity-field-title">Specimens:</strong>
                    <span>1 <a href="#"
                            class="wallich-specimens-switch wallich-specimens-switch-61223 wallich-specimens-switch-show"
                            data-nid="61223">Show &#9660;</a> <a href="#"
                            class="wallich-specimens-switch wallich-specimens-switch-61223 wallich-specimens-switch-hide"
                            data-nid="61223">Hide &#9650;</a></span>
                </div>

            </div><!-- end entry details -->

        </div>
        <div class="wallich-specimens-wrapper" id="wallich-specimens-61223">
            <table class="wallich-specimens">
                <tr>
                    <th class="wallich-specimens-header">Herbarium</th>
                    <th class="wallich-specimens-header">Specimen Barcode</th>
                    <th class="wallich-specimens-header">Stable URI</th>
                </tr>
                <tbody>
                    <tr class="wallich-specimen wallich-specimen-odd ">
                        <td class="wallich-specimen-cell wallich-specimen-catalogue-herbarium">
                            Royal Botanic Garden Kew (Wallich)(K-W)
                        </td>
                        <td class="wallich-specimen-cell wallich-specimen-catalogue-number">

                            K001109072
                        </td>
                        <td>
                            <a class="wallich-specimen-cell wallich-specimen-stable-uri cetaf-specimen-link" href="http://specimens.kew.org/herbarium/K001109072
">http://specimens.kew.org/herbarium/K001109072
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</article>