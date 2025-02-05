try {
    const response = await fetch('../../controllers/common/getError.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Network response was not ok 😢');
    }

    const errors = await response.json();
    console.log(errors);
    for (const error of errors) {
        if (typeof(error) == 'string')
            makeErrorDiv(error);
    }


} catch (error) {
    console.error('Algo deu errado ', error);
}

function makeErrorDiv(message) {
    let newDiv = document.createElement("div");

    newDiv.textContent = message;

    newDiv.setAttribute("id", "errorDiv");
    newDiv.style.position = "fixed";
    newDiv.style.top = "0";
    newDiv.style.left = "0";
    newDiv.style.right = "0";
    newDiv.style.padding = "10px";
    newDiv.style.backgroundColor = "#E32636";
    newDiv.style.color = "white";
    newDiv.style.color = "white";
    newDiv.style.textAlign = "center";
    newDiv.style.zIndex = "100";

    document.body.appendChild(newDiv);

    setTimeout(function() {
        var element = document.getElementById("errorDiv");
        if (element) {
          element.style.display = "none";
        }
      }, 1000);
}
