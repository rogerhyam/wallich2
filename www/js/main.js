const graphQlUri = "https://list.worldfloraonline.org/gql.php";

function popUpDescription(triggerDiv) {

    // get a copy of the content to display
    content = triggerDiv.childNodes[1].cloneNode(true);
    content.style.display = "block"; // turn it on

    // get the place we will put it
    place = document.getElementById('overlay_content');

    // remove all the children
    while (place.firstChild) {
        place.removeChild(place.firstChild);
    }

    // add the content
    place.appendChild(content);

    // turn it on
    overlay = document.getElementById('overlay');
    overlay.style.display = "block";
    overlay.style.top = (document.documentElement.scrollTop + 200) + 'px';

    console.log(place.style.top);

    // scroll it down if we need to


    // console.log(document.getElementById('overlay').style.top);
    // document.getElementById('overlay').style.top = document.getElementById('overlay').style.top + document.documentElement.scrollTop + 'px';
    //  console.log(document.getElementById('overlay').style.top);

}

function popDownDescription() {

    // turn it off
    document.getElementById('overlay').style.display = "none";

    // get the place we have put stuff
    place = document.getElementById('overlay_content');

    // remove all the children
    while (place.firstChild) {
        place.removeChild(place.firstChild);
    }


}

function runGraphQuery(query, variables, giveBack) {

    const payload = {
        'query': query,
        'variables': variables
    }

    var options = {
        'method': 'POST',
        'contentType': 'application/json',
        'headers': {},
        'body': JSON.stringify(payload)
    };

    const response = fetch(graphQlUri, options)
        .then((response) => response.json())
        .then((data) => giveBack(data));

    return;
}
