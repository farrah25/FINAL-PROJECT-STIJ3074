function rememberMe() {
    var rememberme = document.forms["loginForm"]["idremember"].checked;
    var email = document.forms["loginForm"]["idemail"].value;
    var pass = document.forms["loginForm"]["idpass"].value;

    console.log("Form data:" + rememberme + "," + email + "," + pass);
    if (!rememberme) {
        setCookies("cemail", "", 0);
        setCookies("cpass", "", 0);
        setCookies("crem", false, 0);
        document.forms["loginForm"]["idemail"].value = "";
        document.forms["loginForm"]["idpass"].value = "";
        document.forms["loginForm"]["idremember"].checked = false;
        alert("Credentials removed");
    } else {
        if (email == "" && pass == "") {
            document.forms["loginForm"]["idremember"].checked = false;
            alert("Please enter your credentials");
            return false;
        } else {
            setCookies("cemail", email, 30);
            setCookies("cpass", pass, 30);
            setCookies("crem", rememberme, 30);
            alert("Credentials Stored Success");
        }
    }
}


//script to open & close sidebar
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
}


function previewFile() {
    const preview = document.querySelector('.w3-image');
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();
    reader.addEventListener("load", function() {
        // convert image file to base64 string 
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

function confirmDialog() {
    var r = confirm("Insert this product?");
    if (r == true) {
        return true;
    } else {
        return false;
    }
}