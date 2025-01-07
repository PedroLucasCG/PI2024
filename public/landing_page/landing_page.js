import get_login_data from "../get_login_data.js";

const value = await get_login_data();
console.log(value);

if (!value.error) {
    entrar.style.display = "none";
    cadastrar.style.display = "none";
    profilePic.src = `../../blob/${value.idPessoa}/profile.png`;
    profilePic.addEventListener("click", () => {
        profileDropdown.style.display = profileDropdown.style.display == "block" ? "none" : "block";
    });
    dashboard.addEventListener("click", () => {
        window.location.replace('../profile/profile.html');
    });

    sair.addEventListener("click", () => {
        fetch("../../controllers/session/destroy.php")
            .then(response => response.text())
                .then(data => {
                    console.log(data.msg);
                    window.location.reload();
                })
                .catch(error => {
                    console.error("Error:", error);
                });
    });
} else {
    const profile = document.querySelector('li.profileContainer');
    profile.style.display = 'none';
}
