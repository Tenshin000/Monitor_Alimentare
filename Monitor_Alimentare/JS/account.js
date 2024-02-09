function crea_tabella(){
    let tab = document.getElementById("info");

    // Effettua una richiesta HTTP GET per recuperare le informazioni dell'account
    fetch("../PHP/requests/reperisci_informazioni_account.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                    while(tab.hasChildNodes())
                            tab.removeChild(tab.firstChild);

                    if(data["result"]){
                        costruisci_tabella(data);
                    }
                    else
                        window.alert("Dati non recuperati. Riprova!");
                  })
    .catch(error => window.alert(error));
}

// Funzione per costruire la tabella con i dati ottenuti
function costruisci_tabella(data){
    let tab = document.getElementById("info");
    
    for([key, value] of Object.entries(data)){
        if(key != "result"){
            let r = document.createElement("tr");
            let c = document.createElement("td");
            c.style.fontWeight = "bold";
            c.textContent = key;
            r.appendChild(c);

            c = document.createElement("td");
            if(key === "Statura"){
                c.textContent = value + " cm";
            }
            else{
                if(key === "Peso"){
                    let numero = parseFloat(value);
                    if(cifre_dopo_virgola(numero))
                        numero = numero.toFixed(1);
                    else
                        numero = numero.toFixed(0);
                    c.textContent = numero + " Kg";
                }
                else{
                    if(key === "Dimensione del Bicchiere"){
                        let numero = parseFloat(value);
                        if(cifre_dopo_virgola(numero))
                            numero = numero.toFixed(2);
                        else
                            numero = numero.toFixed(0);
                        c.textContent = numero + " L";
                    }
                    else
                        c.textContent = value;
                }
            }
            r.appendChild(c);

            c = document.createElement("td");
            let b = document.createElement("button");
            b.textContent = "Modifica";
            if(key === "Dimensione del Bicchiere")
                b.id = "DimensioneDelBicchiere";
            else{
                if(key === "Data di Nascita")
                    b.id = "DataDiNascita";
                else{
                    if(key === "Preferenza Dietetica")
                        b.id = "PreferenzaDietetica";
                    else
                        b.id = key;
                }
            }
            
            b.addEventListener("click", modifica);
            c.appendChild(b);
            r.appendChild(c);
            tab.appendChild(r);
        }
    }
}

// Funzione per avviare la modifica tramite il click di un button
function modifica(event) {
    const button = event.currentTarget;
    const riga = button.parentNode.parentNode;
    const casella = riga.firstElementChild.nextElementSibling;
    
    let valore = casella.textContent;
    casella.textContent = "";
    button.textContent = "Conferma";
    
    if(isInputField(button.id)){
        crea_input(casella, button.id, valore);
        button.removeEventListener("click", modifica);
        button.addEventListener("click", nuovo_valore);
    } 
    else if(isSelectField(button.id)){
        crea_select(casella, button.id, valore);
        button.removeEventListener("click", modifica);
        button.addEventListener("click", nuovo_valore);
    }
}

// Verifica se il campo richiede un input
function isInputField(id) {
    return ["Nome", "Cognome", "DataDiNascita", "Statura", "Peso", "DimensioneDelBicchiere"].includes(id);
}

// Verifica se il campo richiede un select
function isSelectField(id) {
    return ["Sesso", "Attività", "PreferenzaDietetica"].includes(id);
}

// Crea un campo di input
function crea_input(casella, identita, value) {
    let input = document.createElement("input");
    casella.appendChild(input);

    if(identita=== "Nome" || identita === "Cognome"){
        input.type = "text";
        input.pattern = "[\\p{L}'\\s]+";
    } 
    else if (identita=== "Statura" || identita === "Peso" || identita === "DimensioneDelBicchiere"){
        input.type = "number";
        if(identita === "Peso") 
            input.step = "0.1";
        if(identita === "DimensioneDelBicchiere") 
            input.step = "0.01";
        value = parseFloat(value);
    }
    else if(identita === "DataDiNascita"){
        input.type = "date";
    }
    input.value = value;
}

// Crea un campo di selezione
function crea_select(casella, identita, value) {
    let select = document.createElement("select");
    casella.appendChild(select);

    if(identita === "Sesso"){
        crea_select_option(select, "M", value);
        crea_select_option(select, "F", value);
    } 
    else if(identita === "Attività"){
        crea_select_option(select, "Basso", value);
        crea_select_option(select, "Moderato", value);
        crea_select_option(select, "Elevato", value);
    } 
    else if(identita === "PreferenzaDietetica"){
        crea_select_option(select, "Classica", value);
        crea_select_option(select, "Vegetariana", value);
        crea_select_option(select, "Vegana", value);
    }
}

// Crea un'opzione dentro il select dato in input
function crea_select_option(select, option_value, selected_value) {
    let option = document.createElement("option");
    option.value = option_value;
    option.selected = (option_value === selected_value);
    option.text = option_value;
    select.appendChild(option);
}

// Aggiorna il database col nuovo valore inserito
async function nuovo_valore(event){
    const button = event.currentTarget;
    const riga = button.parentNode.parentNode;
    const casella = riga.firstChild.nextSibling;

    let value = casella.firstChild.value;

    let _data = new FormData();
    _data.set("attribute", button.id);
    _data.set("value", value);
    
     // Effettua una richiesta HTTP POST per aggiornare i dati nell'account
    await fetch("../PHP/requests/aggiorna_informazioni_account.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if(!data["result"]){
                        window.alert("Dati non recuperati. Riprova!");
                    }
                  })
    .catch(error => window.alert(error));

    casella.removeChild(casella.firstChild);
    
    if(button.id === "Statura"){
        casella.textContent = value+ " cm";
    }
    else{
        if(button.id === "Peso"){
            let numero = parseFloat(value);
            if(cifre_dopo_virgola(numero))
                numero = numero.toFixed(1);
            casella.textContent = numero + " Kg";
        }
        else{
            if(button.id === "DimensioneDelBicchiere")
                casella.textContent = value + " L";
            else
                casella.textContent = value;
        }
    }

    button.removeEventListener("click", nuovo_valore);
    button.addEventListener("click", modifica);
    button.textContent = "Modifica";
}

function cifre_dopo_virgola(numero){
    // Converto il numero in una stringa
    var numeroStringa = numero.toString();

    // Uso un'espressione regolare per verificare se ci sono cifre dopo la virgola
    var regex = /(\.\d*[1-9])|(\.\d*[1-9]0+)$/;
    return regex.test(numeroStringa);
}

function inizializzazione(){
    crea_tabella();
}