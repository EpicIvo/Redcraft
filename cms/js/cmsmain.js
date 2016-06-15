/**
 * Created by ivovanderknaap on 15/05/16.
 */
window.addEventListener('load', init);

function init() {
    ajaxRequest();
}

// ajax request for the database data with news posts

function ajaxRequest() {

    var data = {};
    var request = new XMLHttpRequest();
    request.open('GET', 'http://localhost/Redcraft/index.php', true);
    request.onload = function () {
        if (request.status >= 200 && request.status < 400) {
            console.log("succes");
            data = JSON.parse(request.responseText);

            processData(data);

        } else {
            console.log("We reached our target server, but it returned an error")
        }
    };
    request.onerror = function () {
        console.log("There was a connection error of some sort");
    };
    request.send();

}

// function for processing the data to front end
/**
 * 
 * @param data
 */
function processData(data) {

    console.log(data);

    console.log(data.newsposts[0].title);

    for (i = 0; i < data.newsposts.length; i++) {

        var postcontainer = document.getElementById('posts-list');

        var link = document.createElement('a');
        link.setAttribute('href', 'editpost.php?postId=' + data.newsposts[i].id);

        var post = document.createElement('div');
        post.setAttribute('class', 'news-updates-post');
        post.setAttribute('id', 'news-updates-post');

        var posttitle = document.createElement('div');
        posttitle.setAttribute('class', 'news-updates-post-title');
        posttitle.setAttribute('id', 'news-updates-post-title');
        posttitle.innerHTML = data.newsposts[i].title;

        var deletelink = document.createElement('a');
        deletelink.setAttribute('href', 'deletepost.php?postId=' + data.newsposts[i].id);

        var deletetext = document.createElement('div');
        deletetext.setAttribute('class', 'deletetext');
        deletetext.setAttribute('id', 'deletetext');
        deletetext.innerHTML = 'Delete';

        var deletediv = document.createElement('div');
        deletediv.setAttribute('class', 'delete');
        deletediv.setAttribute('id', 'delete');

        postcontainer.appendChild(link);
        link.appendChild(post);
        post.appendChild(posttitle);
        post.appendChild(deletelink);
        deletelink.appendChild(deletediv);
        deletediv.appendChild(deletetext);

    }
}
