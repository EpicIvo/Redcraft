/**
 * Created by ivovanderknaap on 02/03/16.
 */
window.addEventListener('load', init);

function init() {
    ajaxRequest();
}

// ajax request for the database data with news posts
function ajaxRequest() {

    var data = {};
    var request = new XMLHttpRequest();
    request.open('GET', 'http://localhost/Redcraft/php/index.php', true);
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

    //Logging the data so that you can always see what de request is providing
    console.log(data);

    for (i = 0; i < data.newsposts.length; i++) {

        var postcontainer = document.getElementById('news-updates');

        var fullpost = document.createElement('a');
        fullpost.setAttribute('href', 'http://localhost/Redcraft/php/fullpost.php?id=' + data.newsposts[i].id);

        var post = document.createElement('div');
        post.setAttribute('class', 'news-updates-post');
        post.setAttribute('id', 'news-updates-post');

        var postdate = document.createElement('div');
        postdate.setAttribute('class', 'news-updates-post-date');
        postdate.setAttribute('id', 'news-updates-post-date');
        postdate.innerHTML = data.newsposts[i].date;

        var posttitle = document.createElement('div');
        posttitle.setAttribute('class', 'news-updates-post-title');
        posttitle.setAttribute('id', 'news-updates-post-title');
        posttitle.innerHTML = data.newsposts[i].title;

        var postcontent = document.createElement('div');
        postcontent.setAttribute('class', 'news-updates-post-content');
        postcontent.setAttribute('id', 'news-updates-post-content');
        postcontent.innerHTML = data.newsposts[i].content;

        postcontainer.appendChild(fullpost);
        fullpost.appendChild(post);
        post.appendChild(postdate);
        post.appendChild(posttitle);
        post.appendChild(postcontent);

    }
}
