remind.addEventListener("click", toggleSelect);

document.getElementById("email").value = getCookie("email") || "";
document.getElementById("senha").value = getCookie("senha") || "";

let saveToCookie = false;

if (getCookie("email"))
    remind.classList.add("remind");

function toggleSelect() {
    remind.classList.toggle("remind");
    const isRemind = !!Array.from(remind.classList).find(classe => classe === "remind");
    if (isRemind) {
        saveToCookie = true;
    } else {
        deleteCookie("email");
        deleteCookie("senha");
    }
}

document.getElementById("submit").addEventListener("click", () => {
    if (saveToCookie && document.getElementById("email").value && document.getElementById("senha").value) {
        setCookie("email", document.getElementById("email").value, 1000);
        setCookie("senha", document.getElementById("senha").value, 1000);
    }
});

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
}

function getCookie(name) {
    let cookies = document.cookie.split("; ");
    for (let cookie of cookies) {
        let [key, value] = cookie.split("=");
        if (key === name) {
            return decodeURIComponent(value);
        }
    }
    return null;
}
