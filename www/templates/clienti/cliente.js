const categories = {
    anagrafica: document.getElementById("anagrafica"),

    optometrie: document.getElementById("optometrie"),
}

function showCategory(selected){
    switch(selected){
        case "anagrafica":
            categories.anagrafica.classList.remove("d-none");
            categories.optometrie.classList.add("d-none")
        break;

        case "optometrie":
            categories.anagrafica.classList.add("d-none");
            categories.optometrie.classList.remove("d-none")
        break;
    }
}