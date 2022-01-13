// Global variables handling part of toggle animation
var toggleW=true;
var toggleO=true;
var toggleU=true;

function toggleWorkers() {
    toggleW = !toggleW;
    toggleW ? document.getElementById("butWorkers").className = "toggle active" : document.getElementById("butWorkers").className = "toggle";
    
    var x = document.getElementById("workers");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
}

function toggleOthers() {
    toggleO = !toggleO;
    toggleO ? document.getElementById("butOthers").className = "toggle active" : document.getElementById("butOthers").className = "toggle";

    var x = document.getElementById("others");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
}

function toggleUsers() {
    toggleU = !toggleU;
    toggleU ? document.getElementById("butUsers").className = "toggle active" : document.getElementById("butUsers").className = "toggle";

    var x = document.getElementById("users");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
}

function searchBooks() {
    var title = document.getElementById("title").value.toUpperCase();
    var author = document.getElementById("author").value.toUpperCase();
    var genre = document.getElementById("genre").value;
    var lib = document.getElementById("library").value;
    var table = document.getElementById("books");
    var tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "table-row"; 
    }

    if(title != ''){
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                if (td.innerText.toUpperCase().indexOf(title) <= -1) {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    if(author != ''){
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2];
            if (td) {
                if (td.innerText.toUpperCase().indexOf(author) <= -1) {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    if(genre != 'všetky'){
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[3];
            if (td) {
                if (td.innerText.indexOf(genre) <= -1) {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    if(lib != 'všetky'){
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[5];
            if (td) {
                if (td.innerText.indexOf(lib) <= -1) {
                    tr[i].style.display = "none";
                } 
            }       
        }
    }
}