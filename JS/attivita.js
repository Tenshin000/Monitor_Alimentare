let calorie_attivita = [];
let aggiornamento = false;

// Crea la barra di ricerca delle attività
async function ricerca(event){
    // Recupera vari elementi DOM utilizzando document.getElementById
    const barra = document.getElementById("barra");
    const form = document.getElementById("formRicercaA");
    const risultati = document.getElementById("risultati");
    
    // Controlla se il valore dell'input di ricerca (barra) è vuoto. 
    // Se lo è, rimuove tutti i risultati dalla sezione dei risultati (risultati) e restituisce subito.
    if(barra.value == ""){
        while(risultati.hasChildNodes())
            risultati.removeChild(risultati.firstChild);
        return;
    }

    // Indica che la barra di ricerca deve ricercare nella tabella Attivita
    event.preventDefault();
    const _data = new FormData(form);
    _data.set("tipo", "attivita");

    await fetch("../PHP/requests/barra_di_ricerca.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    // Rimuove tutti i risultati precedenti dalla sezione dei risultati.
                    while(risultati.hasChildNodes())
                        risultati.removeChild(risultati.firstChild);

                    const tab = document.createElement("table");
                    risultati.appendChild(tab);
                    for(let i = 0; i < data["length"]; i++){
                        let r = document.createElement("tr");
                        let c = document.createElement("td");
                        c.textContent = data[i]["nome"];
                        c.addEventListener("click", mostra_attivita);
                        tab.appendChild(r);
                        r.appendChild(c);
                    }
                })
    .catch(error => window.alert(error));
}

// Questa funzione viene chiamata quando si clicca un'attività nella sezione dei risultati.
function mostra_attivita(event){
    // Ottiene l'elemento cliccato (selezionato) dall'oggetto evento.
    let selezionato = event.currentTarget;
    const attivita = document.getElementById("attivita");
    let stringa = selezionato.textContent;

    // Crea un nuovo oggetto FormData, imposta il parametro "nome" con il nome dell'attività
    let _data = new FormData();
    _data.set('nome', stringa);

    // Invia una richiesta fetch al server per ottenere i dati relativi a quell'attività dal file dati_attivita.php
    fetch("../PHP/requests/dati_attivita.php", { method: 'POST', body: _data })
        .then(response => response.json())
        .then(data => {
            if (data["length"] > 0){
                // Rimuove tutti i figli dell'elemento "attivita"
                while (attivita.hasChildNodes())
                    attivita.removeChild(attivita.firstChild);

                // Crea un nuovo paragrafo con ID "msg" e lo aggiunge all'elemento "attivita"
                const p = document.createElement("p");
                p.id = "msg";
                attivita.appendChild(p);

                // Crea un nuovo elemento di tabella e lo aggiunge all'elemento "attivita"
                const tab = document.createElement("table");
                attivita.appendChild(tab);

                // Chiama la funzione per creare la riga del titolo dei dettagli dell'attività
                riga_titolo_dettagli_attivita(tab, stringa);

                // Chiama la funzione per creare la riga con campo di input e menu a discesa
                riga_input_select(tab, data);

                // Chiama la funzione per creare la riga delle calorie bruciate
                riga_calorie_bruciate(tab, data);
            }
        })
        .catch(error => window.alert(error));
}

// Questa funzione crea la riga del titolo dei dettagli dell'attività. Usata da "mostra_attivita".
function riga_titolo_dettagli_attivita(table, stringa){
    const r = document.createElement("tr");
    const c = document.createElement("td");
    c.textContent = stringa;
    c.style.fontWeight = "bold";
    c.style.fontSize = "14pt";
    c.style.textAlign = "center";
    c.id = "nome";
    c.colSpan = 3;
    r.appendChild(c);
    table.appendChild(r);
}

// Questa funzione crea la riga con campo di input numerico e un select. Usata da "mostra_attivita".
function riga_input_select(table, data){
    const r = document.createElement("tr");
    let c = document.createElement("td");

    // Crea un campo di input numerico
    const input = document.createElement("input");
    input.type = "number";
    input.value = 1;
    input.min = 1;
    input.id = "input";
    input.addEventListener("input", cambio_input);
    c.style.textAlign = "right";
    c.appendChild(input);
    r.appendChild(c);

    c = document.createElement("td");
    // Crea un select con delle opzioni
    const select = document.createElement("select");
    for(let i = 0; i < data["length"]; i++){
        const option = document.createElement("option");
        option.value = i;
        option.textContent = data[i]["unita"];
        let calorie = parseFloat(data[i]["calorie"]);
        if(cifre_dopo_virgola(calorie))
            calorie = calorie.toFixed(2);
        calorie_attivita[i] = calorie;
        select.appendChild(option);
    }
    select.id = "select";
    select.addEventListener("change", cambio_opzione);
    c.appendChild(select);
    r.appendChild(c);

    c = document.createElement("td");
    // Crea un pulsante "Inserisci" e aggiunge un evento "on click"
    const button = document.createElement("button");
    button.textContent = "Inserisci";
    button.addEventListener("click", inserimento_attivita);
    c.appendChild(button);
    r.appendChild(c);
    table.appendChild(r);
}

// Questa funzione crea la riga delle calorie bruciate. Usata da "mostra_attivita".
function riga_calorie_bruciate(table, data){
    const r = document.createElement("tr");
    let c = document.createElement("td");

    c.textContent = "Calorie Bruciate";
    c.style.color = "#FFE066";
    r.appendChild(c);

    c = document.createElement("td");
    let calorie = parseFloat(data[0]["calorie"]);
    if (cifre_dopo_virgola(calorie))
        calorie = calorie.toFixed(2);
    c.textContent = calorie + " Kcal";
    c.style.color = "#FFE066";
    c.id = "calorie_bruciate";
    r.appendChild(c);

    c = document.createElement("td");
    r.appendChild(c);
    table.appendChild(r);
}

// Questa funzione viene chiamata quando l'utente modifica il valore nell'input numerico per la quantità di un'attività
function cambio_input(event){
    let identita = event.target.id;
    let input = document.getElementById(identita);

    let select = document.getElementById("select");
    let indice = select.value;

    let moltiplicatore = input.value;
    if(input.value == "")
        moltiplicatore = 0;
    
    let numero = calorie_attivita[indice];
    numero = moltiplicatore * numero;
    numero = numero.toFixed(2);
    document.getElementById("calorie_bruciate").textContent = numero + " Kcal";
}

// Questa funzione viene chiamata quando l'utente cambia l'opzione nell'elemento select per le unità di misura dell'attività
function cambio_opzione(event){
    let identita = event.target.id;
    let select = document.getElementById(identita);

    let indice = select.value;
    document.getElementById("calorie_bruciate").textContent = calorie_attivita[indice] + " Kcal";

    document.getElementById("input").value = 1;
}

// Questa funzione viene chiamata quando l'utente clicca sul pulsante "Inserisci" per registrare l'attività
function inserimento_attivita(){
    let esercizio = document.getElementById("nome").textContent;
    
    let select = document.getElementById("select");
    let selected_option = select.options[select.selectedIndex];
    let unita = selected_option.textContent;

    let quantita = document.getElementById("input").value;
    if(quantita == "" || quantita == 0)
        return;

    let _data = new FormData();
    _data.set('esercizio', esercizio);
    _data.set('unita', unita);
    _data.set('quantita', quantita);

    fetch("../PHP/requests/inserimento_attivita.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if(data["result"])
                        inizializza_attivita();

                    let msg = document.getElementById("msg");
                    msg.textContent = data["msg"];
                    setTimeout(function(){
                                    let msg = document.getElementById("msg");
                                    msg.textContent = "";
                                }, 3000);
                })
    .catch(error => window.alert(error));
}

// Inizializza le attività Camminare (con unità passi) e Dormire
async function inizializza_cd(){
    const inseriti = document.getElementById("inseriti");
    
    const camminare = document.createElement("div");
    camminare.id = "Camminare_passi";
    camminare.classList.add("attivita");

    const dormire = document.createElement("div");
    dormire.id = "Dormire_h";
    dormire.classList.add("attivita");

    inseriti.appendChild(camminare);
    inseriti.appendChild(dormire);

    await fetch("../PHP/requests/recupera_cd.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                    if(data["result"]){
                        // Crea e popola la tabella per l'attività "Camminare (passi)"
                        crea_tabella(camminare, "Camminare", data["passi"], "passi", data["calorie passi"]);

                        // Crea e popola la tabella per l'attività "Dormire"
                        crea_tabella(dormire, "Dormire", data["sonno"], "ore", data["calorie sonno"]);
                    }
                    else
                        window.alert("Problema col recupero dei dati!");
                })
    .catch(error => window.alert(error));            
}

// Questa funzione inizializza le informazioni relative a tutte le attività
async function inizializza_attivita(){
    const inseriti = document.getElementById("inseriti");
    while(inseriti.hasChildNodes())
        inseriti.removeChild(inseriti.firstChild);

    await inizializza_cd();

    await fetch("../PHP/requests/attivita_registrate.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                    for(let i = 0; i < data["length"]; i++){
                        // Nel caso inizializza prima l'attività "Camminare" con unità minuti e poi le altre
                        if(data[i]["esercizio"] === "Camminare" && data[i]["unita"] === "min"){
                            const div = crea_attivita(inseriti, data[i]["esercizio"] + "_" + data[i]["unita"]);
                            let unita = data[i]["unita"];
                            if(data[i]["unita"] === "min")
                                unita = "minuti";
                            else if(data[i]["unita"] === "h")
                                unita = "ore";
                            crea_tabella(div, data[i]["esercizio"], data[i]["quantita"], unita, parseFloat(data[i]["calorie"]));
                        } 
                    }
                    for(let i = 0; i < data["length"]; i++){
                        // Crea un elemento "div" per le altre attivit
                        if(data[i]["esercizio"] === "Camminare" || data[i]["esercizio"] === "Dormire")
                            continue;

                        const div = crea_attivita(inseriti, data[i]["esercizio"] + "_" + data[i]["unita"]);
                        // Crea e popola una tabella per l'attività
                        let unita = data[i]["unita"];
                        if(data[i]["unita"] === "min")
                            unita = "minuti";
                        else if(data[i]["unita"] === "h")
                            unita = "ore";
                        crea_tabella(div, data[i]["esercizio"], data[i]["quantita"], unita, parseFloat(data[i]["calorie"]));
                    }
                  })
    .catch(error => window.alert(error));            
}

// Funzione per creare un nuovo elemento "div" per un'attività e aggiungerlo all'elemento "inseriti"
function crea_attivita(inseriti, id){
    const attivita = document.createElement("div");
    attivita.id = id;
    attivita.classList.add("attivita");
    inseriti.appendChild(attivita);
    return attivita;
}

// Funzione per creare una tabella con titolo e dati
function crea_tabella(conteiner, titolo, quantita, unita, calorie){
    const tab = document.createElement("table");

    // Aggiunge il titolo alla tabella
    let r = document.createElement("tr");
    let c = document.createElement("td");
    c.textContent = titolo;
    c.style.fontWeight = "bold";
    c.style.textAlign = "center";
    r.appendChild(c);
    tab.appendChild(r);

    r = document.createElement("tr");
    c = document.createElement("td");
    c.textContent = quantita + " " + unita;
    r.appendChild(c);
    tab.appendChild(r);

    r = document.createElement("tr");
    c = document.createElement("td");
    let valore = parseFloat(calorie);
    if(cifre_dopo_virgola(valore))
        valore = valore.toFixed(1);
    c.textContent = valore + " Kcal";
    r.appendChild(c);
    tab.appendChild(r);

    conteiner.appendChild(tab);

    // Aggiunge un pulsante "Aggiorna"
    crea_pulsante_aggiorna(tab); 
}

// Funzione per creare e aggiungere un pulsante "Aggiorna" con un gestore eventi
function crea_pulsante_aggiorna(conteiner){
    const c = document.createElement("td");
    const button = document.createElement("button");
    button.textContent = "Aggiorna";
    button.addEventListener("click", aggiorna);
    c.appendChild(button);
    const r = document.createElement("tr");
    r.appendChild(c);
    conteiner.appendChild(r);
}

// Questa funzione gestisce l'aggiornamento dei dati relativi alle attività. 
// La funzione può essere chiamata su eventi di bottone "Aggiorna" associati a ciascuna attività. 
async function aggiorna(event){
    let aggiorna = event.currentTarget;
    let tabella = aggiorna.parentNode.parentNode.parentNode;
    let attivita = tabella.parentNode;
    let valore = 0.0;

    if(aggiornamento){
        let stringa = attivita.id.split("_");
        let casella = tabella.firstChild.nextSibling;
        let input = casella.firstChild;

        valore = input.value;
        if(valore === "")
            valore = 0.0;

        if(cifre_dopo_virgola(valore))
            valore = valore.toFixed(1);

        let _data = new FormData();
        _data.set("nome", stringa[0]);
        _data.set("unita", stringa[1]);
        _data.set("quantita", valore);

        await fetch("../PHP/requests/aggiornamento_attivita.php", { method: 'POST', body: _data })
        .then(response => response.json())
        .then(data => {
                        if(data["result"]){
                            if(valore > 0 || (stringa[0] == "Camminare" && stringa[1] == "passi") || stringa[0] == "Dormire"){
                                let label = casella.firstChild.nextSibling;
                                stringa = input.value + " " + label.textContent;
                                while(casella.hasChildNodes())
                                    casella.removeChild(casella.firstChild);
                                casella.textContent = stringa;
                                
                                casella = casella.nextSibling.firstChild;
                                valore = parseFloat(data["calorie"]);
                                if(cifre_dopo_virgola(valore))
                                    valore = valore.toFixed(1);
                                casella.textContent = valore + " Kcal";

                                let msg = document.getElementById("msg");
                                msg.textContent = "Aggiornamento avvenuto con successo";
                                setTimeout(function(){
                                                        let msg = document.getElementById("msg");
                                                        msg.textContent = "";
                                                    }, 3000);
                            }
                            else{
                                const inseriti = document.getElementById("inseriti");
                                inseriti.removeChild(attivita);
                            }
                        }
                        else
                            window.alert("Aggiornamento non avvenuto!");
                    })
        .catch(error => window.alert(error));

        aggiornamento = false;
    }
    else{
        let casella = tabella.firstChild.nextSibling;
        let stringa = casella.textContent;
        valore = parseFloat(stringa);
        
        casella.textContent = "";
        let input = document.createElement("input");
        input.type = "number";

        if(cifre_dopo_virgola(valore))
            valore = valore.toFixed(2);
        
        input.value = valore;
        input.min = 0;
        casella.appendChild(input);
        
        let label = document.createElement("label");
        valore = stringa.split(" ");
        label.textContent = valore[1];
        casella.appendChild(label);

        aggiornamento = true;
    }
}

// Questa funzione gestisce l'aggiornamento dei dati relativi alle attività.
async function aggiorna(event){
    let aggiorna = event.currentTarget;
    let tabella = aggiorna.parentNode.parentNode.parentNode;
    let attivita = tabella.parentNode;
    

    if(aggiornamento){
        await esegui_aggiornamento(attivita, tabella);
        aggiornamento = false;
    }  
    else{
        attiva_modifica(tabella);
        aggiornamento = true;
    }       
}

// Questa funzione esegue l'aggiornamento dei dati dell'attività
async function esegui_aggiornamento(attivita, tabella){
    let stringa = attivita.id.split("_");
    let casella = tabella.firstElementChild.nextElementSibling;
    let input = casella.firstElementChild;

    let valore = input.value;
    if (valore === "")
        valore = 0.0;

    if (cifre_dopo_virgola(valore))
        valore = valore.toFixed(1);

    let _data = new FormData();
    _data.set("nome", stringa[0]);
    _data.set("unita", stringa[1]);
    _data.set("quantita", valore);

    await fetch("../PHP/requests/aggiornamento_attivita.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if (data["result"]){
                        if (valore > 0 || (stringa[0] == "Camminare" && stringa[1] == "passi") || stringa[0] == "Dormire") {
                            let label = casella.firstElementChild.nextElementSibling;
                            stringa = input.value + " " + label.textContent;
                            while (casella.hasChildNodes())
                                casella.removeChild(casella.firstChild);
                            casella.textContent = stringa;
                
                            casella = casella.nextElementSibling.firstElementChild;
                            valore = parseFloat(data["calorie"]);
                            if (cifre_dopo_virgola(valore))
                                valore = valore.toFixed(1);
                            casella.textContent = valore + " Kcal";
                             
                            let msg = document.getElementById("msg");
                            msg.textContent = "Aggiornamento avvenuto con successo";
                            setTimeout(function(){
                                                    let _msg = document.getElementById("msg");
                                                    _msg.textContent = "";
                                                 }, 3000);
                        } 
                        else{
                            const inseriti = document.getElementById("inseriti");
                            inseriti.removeChild(attivita);
                        }
                    } 
                    else
                        window.alert("Aggiornamento non avvenuto!");
                  })
    .catch(error => window.alert(error));
}

// Questa funzione abilita la modifica dell'attività
function attiva_modifica(tabella) {
    let casella = tabella.firstElementChild.nextElementSibling;
    let stringa = casella.textContent;
    let valore = parseFloat(stringa);

    casella.textContent = "";
    let input = document.createElement("input");
    input.type = "number";

    if (cifre_dopo_virgola(valore))
        valore = valore.toFixed(2);

    input.value = valore;
    input.min = 0;
    casella.appendChild(input);

    let label = document.createElement("label");
    valore = stringa.split(" ");
    label.textContent = valore[1];
    casella.appendChild(label);
}

function cifre_dopo_virgola(numero){
    // Converto il numero in una stringa
    var numero_stringa = numero.toString();  

    // Uso un'espressione regolare per verificare se ci sono cifre dopo la virgola
    var regex = /(\.\d*[1-9])|(\.\d*[1-9]0+)$/;
    return regex.test(numero_stringa);
}

function inizializzazione(){
    const barra = document.getElementById("barra");
    const form = document.getElementById("formRicercaA");
    form.addEventListener("submit", ricerca);
    barra.addEventListener("input", ricerca);
    inizializza_attivita();
}