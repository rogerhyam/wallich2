<?php


?>
<div id="content" class="column" role="main">
    <a id="main-content"></a>
    <h1 class="page__title title" id="page-title">Search</h1>

    <form class="search-form" action="index.php" method="GET" id="search-form" accept-charset="UTF-8">
        <input type="hidden" name="section" value="search" />
        <div style="margin-top: 1em;">
            <input type="text" id="edit-keys" name="terms" value="<?php echo @$_GET['terms'] ?>" size="40"
                placeholder="Enter keywords or entry number" />
            <input type="submit" id="edit-submit" value="Search" />
        </div>
    </form>

    <h2>Search results</h2>
    <ul class="search-results node-results">
        <?php

    $count = 0;
    if(@$_GET['terms']){

        $terms = trim($_GET['terms']);
        // we have something to search for.
        // see if it is a number or not
        if(preg_match('/^[0-9]+$/', $terms)){

            $response = $mysqli->query("SELECT * FROM entries WHERE entry_number = $terms;");
            $entries = $response->fetch_all(MYSQLI_ASSOC);
            $response->close();   

            foreach($entries as $entry){
                render_search_result('index.php?section=entries&id='.$entry['entry_number'], $entry['title'], strip_tags($entry['verbatim']), $terms);
                $count++;
            }
        }

        // try and find just a name
        $terms_safe = $mysqli->real_escape_string($terms);

        $response = $mysqli->query("SELECT * FROM entries WHERE taxon_name like  '$terms_safe%' LIMIT 20;");
        $entries = $response->fetch_all(MYSQLI_ASSOC);
        $response->close();   

        foreach($entries as $entry){
            render_search_result('index.php?section=entries&id='.$entry['entry_number'], $entry['title'], strip_tags($entry['verbatim']), $terms);
            $count++;
        }

        $response = $mysqli->query("SELECT * FROM sub_entries WHERE taxon_name like  '$terms_safe%' LIMIT 20;");
        $subs = $response->fetch_all(MYSQLI_ASSOC);
        $response->close();   

        foreach($subs as $sub){
            render_search_result('index.php?section=entries&id='.$sub['entry_number'], $sub['title'], strip_tags($sub['verbatim']), $terms);
            $count++;
        }

        // try a general search

        $response = $mysqli->query("SELECT * FROM search WHERE MATCH (body) AGAINST ('$terms_safe') LIMIT 100;") ;
        $results = $response->fetch_all(MYSQLI_ASSOC);
        $response->close();

        foreach($results as $result){

            if($result['kind'] == 'entry'){
                $link = 'index.php?section=entries&sub_entry=false&id='.$result['nid'];
            }else{
                $link = 'index.php?section=entries&sub_entry=true&id='.$result['nid'];
            }
            render_search_result($link, $result['title'], strip_tags($result['body']), $terms);
            $count++;
        }

    }

    if($count == 0){
       echo '<li>No results.</li>';
    }

?>
    </ul>
</div>

<?php

function render_search_result($link, $title, $text, $terms = ''){


    $words = explode(' ', $terms);
    foreach($words as $word){
        //$text = str_replace($word, "<strong>$word</strong>", $text);
        $text = preg_replace("/$word/i", "<strong>$word</strong>", $text);
    }

?>
<li class="search-result">
    <span class="title">
        <a href="<?php echo $link ?>"><?php echo $title ?></a>
    </span>
    <span class="search-snippet-info">
        <span class="search-snippet"><?php echo $text ?></span>
    </span>
</li>

<?php
}
?>