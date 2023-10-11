

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
    document.getElementById('overlay').style.display = "block";

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