// Questo file .js è dentro il body quindi posso inizializzare qua gli elementi DOM che userò
const barra = document.getElementById("barra");
const form = document.getElementById("formRicercaC");
const risultati = document.getElementById("risultati");
const alimento = document.getElementById("alimento");
const inseriti = document.getElementById("inseriti");

let porzioni = [];
let fabbisogno_calorico = 0;
let calorie_prese = 0;
let calorie_perse = 0;
let cibi_inseriti = 0;
let aggiornamento = false;

// Crea la barra di ricerca delle attività
async function ricerca(event){
    // Controlla se il valore dell'input di ricerca (barra) è vuoto. 
    // Se lo è, rimuove tutti i risultati dalla sezione dei risultati (risultati) e restituisce subito.
    if(barra.value == ""){
        while(risultati.hasChildNodes())
            risultati.removeChild(risultati.firstChild);
        return;
    }
    
    // Indica che la barra di ricerca deve ricercare nella tabella Cibo e Porzione
    event.preventDefault();
    const _data = new FormData(form);
    _data.set('tipo', "cibo");

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
                        c.id = data[i]["ID"];
                        c.textContent = data[i]["nome"];
                        c.addEventListener("click", mostra_cibo);
                        tab.appendChild(r);
                        r.appendChild(c);
                    }
                  })
    .catch(error => window.alert(error));
}

// Questa funzione viene chiamata quando si clicca un cibo nella sezione dei risultati.
async function mostra_cibo(event){
    let identita = event.target.id;
    
    // Crea un nuovo oggetto FormData, imposta il parametro ID con l'ID del Cibo
    let _data = new FormData();
    _data.set('ID', identita);
    
    // Invia una richiesta fetch al server per ottenere i dati relativi al cibo dal file dati_alimento.php
    await fetch("../PHP/requests/dati_alimento.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    while(alimento.hasChildNodes())
                        alimento.removeChild(alimento.firstChild);

                    let p = document.createElement("p");
                    p.id = "msg";
                    alimento.appendChild(p);

                    crea_tabella_cibo(data, identita);
                  })
    .catch(error => window.alert(error));
}

// Questa funzione crea la tabella con i dati del cibo. Usata da "mostra_cibo". 
function crea_tabella_cibo(data, identita){
    const tab = document.createElement("table");
    alimento.appendChild(tab);

    for(let [key, value] of Object.entries(data)){
        if(key === "nome"){
            crea_nome_riga(tab, value);
            crea_righe_header(tab, data, identita);
        } 
        else{
            if(isNaN(key) && key !== "length"){
                if(key === "Vitamina A")
                    crea_riga_categoria(tab, "Vitamine");
                else if(key === "Calcio")
                    crea_riga_categoria(tab, "Macronutrienti");
                else if(key === "Cromo")
                    crea_riga_categoria(tab, "Micronutrienti");

                crea_riga_nutrienti(tab, key, value);
            }
        }
    }
}

// Questa funzione crea la riga per il nome del cibo. Usata da "mostra_cibo". 
function crea_nome_riga(tab, nome) {
    const r = document.createElement("tr");
    const c = document.createElement("td");
    c.textContent = nome;
    c.style.fontWeight = "bold";
    c.style.fontSize = "14pt";
    c.style.textAlign = "center";
    c.colSpan = 4;
    r.appendChild(c);
    tab.appendChild(r);
}

// Questa funzione crea l'intestazione della tabella per i valori nutrizionali. Usata da "mostra_cibo". 
function crea_righe_header(tab, data, identita){
    let r = document.createElement("tr");
    const headers = ["Calorie (Kcal)", "Carboidrati (g)", "Grassi (g)", "Proteine (g)"];

    // Costruzione Header 
    c = document.createElement("td");
    c.textContent = "Calorie (Kcal)";
    c.style.textAlign = "center";
    r.appendChild(c);

    c = document.createElement("td");
    c.textContent = "Carboidrati (g)";
    c.style.textAlign = "center";
    r.appendChild(c);

    c = document.createElement("td");
    c.textContent = "Grassi (g)";
    c.style.textAlign = "center";
    r.appendChild(c);

    c = document.createElement("td");
    c.textContent = "Proteine (g)";
    c.style.textAlign = "center";
    r.appendChild(c);
    tab.appendChild(r);

    // Costruzione valori relativi all'Header
    r = document.createElement("tr");
    c = document.createElement("td");
    c.id = "Calorie";
    c.style.textAlign = "center";
    r.appendChild(c);

    c = document.createElement("td");
    c.id = "Carboidrati";
    c.style.textAlign = "center";
    r.appendChild(c);

    c = document.createElement("td");
    c.id = "Grassi";
    c.style.textAlign = "center";
    r.appendChild(c);

    c = document.createElement("td");
    c.id = "Proteine";
    c.style.textAlign = "center";
    r.appendChild(c);
    tab.appendChild(r);

    crea_inserimento_righe(tab, data, identita);
}

// Questa funzione crea la riga per l'inserimento del cibo nel database. Usata da "mostra_cibo". 
function crea_inserimento_righe(tab, data, identita) {
    const r = document.createElement("tr");
    let c = document.createElement("td");

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
    let select = crea_select_nutrienti(data, identita);
    c.appendChild(select);
    r.appendChild(c);

    c = document.createElement("td");
    const button = document.createElement("button");
    button.textContent = "Inserisci";
    button.addEventListener("click", inserimento_pasto);
    c.appendChild(button);
    r.appendChild(c);

    c = document.createElement("td");
    r.appendChild(c);
    tab.appendChild(r);
}

// Questa funzione crea il menu a discesa per le porzioni. Usata da "mostra_cibo". 
function crea_select_nutrienti(data, identita) {
    const select = document.createElement("select");
    for(let i = 0; i < data["length"]; i++){
        porzioni[i] = {
            ID: identita,
            catalogazione: data[i]["catalogazione"],
            calorie: data[i]["calorie"].toFixed(2),
            carboidrati: data[i]["carboidrati"].toFixed(2),
            proteine: data[i]["proteine"].toFixed(2),
            grassi: data[i]["grassi"].toFixed(2),
            quantita: data[i]["quantita"],
            misura: data[i]["misura"]
        };

        const option = document.createElement("option");
        option.value = i;
        option.textContent = data[i]["catalogazione"];
        select.appendChild(option);

        if(i === 0 || option.textContent === "g"){
            document.getElementById("Calorie").textContent = porzioni[i]["calorie"];
            document.getElementById("Carboidrati").textContent = porzioni[i]["carboidrati"];
            document.getElementById("Proteine").textContent = porzioni[i]["proteine"];
            document.getElementById("Grassi").textContent = porzioni[i]["grassi"];
        }

        // Tengo di conto che un'alimento non potrà mai essere catalogato in grammi o in millilitri

        if(option.textContent === "g"){
            option.selected = true;
            document.getElementById("Calorie").textContent = porzioni[i]["calorie"];
            document.getElementById("Carboidrati").textContent = porzioni[i]["carboidrati"];
            document.getElementById("Proteine").textContent = porzioni[i]["proteine"];
            document.getElementById("Grassi").textContent = porzioni[i]["grassi"];
        }         

        if(option.textContent === "ml"){
            option.selected = true;
            document.getElementById("Calorie").textContent = porzioni[i]["calorie"];
            document.getElementById("Carboidrati").textContent = porzioni[i]["carboidrati"];
            document.getElementById("Proteine").textContent = porzioni[i]["proteine"];
            document.getElementById("Grassi").textContent = porzioni[i]["grassi"];
        }
    }

    select.id = "select";
    select.addEventListener("change", cambio_opzione);
    return select;
}

// Questa funzione crea una riga per una categoria di nutrienti. Usata da "mostra_cibo". 
function crea_riga_categoria(tab, nome_categoria) {
    const r = document.createElement("tr");
    let c = document.createElement("td");
    c.textContent = nome_categoria;
    c.style.fontWeight = "bold";
    r.appendChild(c);

    c = document.createElement("td");
    r.appendChild(c);

    c = document.createElement("td");
    r.appendChild(c);

    c = document.createElement("td");
    r.appendChild(c);

    tab.appendChild(r);
}

// Questa funzione crea una riga per un nutriente specifico. Usata da "mostra_cibo". 
function crea_riga_nutrienti(tab, nome_nutriente, value){
    const r = document.createElement("tr");
    let c = document.createElement("td");
    c.textContent = nome_nutriente;
    r.appendChild(c);

    c = document.createElement("td");
    r.appendChild(c);

    c = document.createElement("td");
    c.textContent = value ? "Presente" : "Assente";
    c.style.color = value ? "green" : "red";
    r.appendChild(c);

    c = document.createElement("td");
    r.appendChild(c);
    tab.appendChild(r);
}

// Questa funzione viene chiamata quando l'utente modifica il valore nell'input numerico per la quantità di un cibo
function cambio_opzione(event){
    let identita = event.target.id;
    let select = document.getElementById(identita);

    let indice = select.value;
    document.getElementById("Calorie").textContent = porzioni[indice]["calorie"];
    document.getElementById("Carboidrati").textContent = porzioni[indice]["carboidrati"];
    document.getElementById("Proteine").textContent = porzioni[indice]["proteine"];
    document.getElementById("Grassi").textContent = porzioni[indice]["grassi"];

    document.getElementById("input").value = 1;
}

// Questa funzione viene chiamata quando l'utente cambia l'opzione nell'elemento select per le unità di misura del Cibo
function cambio_input(event){
    let identita = event.target.id;
    let input = document.getElementById(identita);

    let indice = document.getElementById("select").value;

    let moltiplicatore = input.value;
    let numero = porzioni[indice]["calorie"];
    numero = moltiplicatore * numero;
    numero = numero.toFixed(2);
    document.getElementById("Calorie").textContent = numero;

    numero = porzioni[indice]["carboidrati"];
    numero = moltiplicatore * numero;
    numero = numero.toFixed(2);
    document.getElementById("Carboidrati").textContent = numero;

    numero = porzioni[indice]["proteine"];
    numero = moltiplicatore * numero;
    numero = numero.toFixed(2);
    document.getElementById("Proteine").textContent = numero;

    numero = porzioni[indice]["grassi"];
    numero = moltiplicatore * numero;
    numero = numero.toFixed(2);
    document.getElementById("Grassi").textContent = numero;
}

// Questa funzione viene chiamata quando l'utente clicca sul pulsante "Inserisci" per registrare il pasto
function inserimento_pasto(){
    let indice = document.getElementById("select").value;
    let cibo = porzioni[indice]["ID"];
    let catalogazione = porzioni[indice]["catalogazione"];
    let quantita = document.getElementById("input").value;
    
    let _data = new FormData();
    _data.set('cibo', cibo);
    _data.set('catalogazione', catalogazione);
    _data.set('quantita', quantita);


    fetch("../PHP/requests/inserimento_pasto.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if(data["result"])
                        pasti_registrati();

                    let msg = document.getElementById("msg");
                    msg.textContent = data["msg"];
                    setTimeout(function(){
                                    let msg = document.getElementById("msg");
                                    msg.textContent = "";
                                }, 3000);
                  })
    .catch(error => window.alert(error));
}

// Recupera il fabbisogno calorico e chiama la funzione pasti_registrati()
async function recupera_fabbisogno(){
    await fetch("../PHP/requests/recupera_fabbisogno.php", { method: 'GET' })
    .then(response => response.json())
    .then(data => {
                    if(data["result"])
                        fabbisogno_calorico = data["fabbisogno"];
                    else
                        window.alert("Problema con il recupero del fabbisogno calorico");
                  })
    .catch(error => window.alert(error));

    pasti_registrati();
}

// Questa funzione è responsabile del recupero e della visualizzazione dei pasti registrati
async function pasti_registrati(){
    // Disabilita i pulsanti "Aggiorna" e "Cancella" prima del recupero dei dati
    aggiorna.disabled = true;
    cancella.disabled = true;

    await fetch("../PHP/requests/pasti_registrati.php")
    .then(response => response.json())
    .then(data => {
                    // Prima di visualizzare i dati, la funzione rimuove tutti i figli dall'elemento inseriti.
                    // Questo serve a cancellare eventuali dati precedenti prima di aggiornare la visualizzazione con i nuovi dati.
                    while(inseriti.hasChildNodes())
                        inseriti.removeChild(inseriti.firstChild);

                    cibi_inseriti = 0;
                    let tab = document.createElement("table");
                    let p = document.getElementById("conto_calorie");
                    calorie_prese = 0;

                    // All'interno del ciclo for, i dati relativi a ciascun pasto vengono estratti da data, 
                    // che è l'oggetto JSON restituito dalla richiesta fetch
                    for(let i = 0; i < data["length"]; i++){
                        let r = document.createElement("tr");
                        let c = document.createElement("td");

                        if(i == 0){
                            c.style.color = "#FFE066";
                            c.style.textAlign = "center";
                            c.style.fontWeight = "bold";
                            c.textContent = "Nome";
                            r.appendChild(c);

                            c = document.createElement("td");
                            c.style.color = "#FFE066";
                            c.style.textAlign = "center";
                            c.style.fontWeight = "bold";
                            c.textContent = "Quantità";
                            r.appendChild(c);

                            c = document.createElement("td");
                            c.style.color = "#FFE066";
                            c.style.textAlign = "center";
                            c.style.fontWeight = "bold";
                            c.textContent = "Unità";
                            r.appendChild(c);

                            c = document.createElement("td");
                            c.style.color = "#FFE066";
                            c.style.textAlign = "center";
                            c.style.fontWeight = "bold";
                            c.textContent = "Calorie";
                            r.appendChild(c);

                            c = document.createElement("td");
                            c.style.color = "#FFE066";
                            c.style.textAlign = "center";
                            c.style.display = "none";
                            c.textContent = "ID";
                            r.appendChild(c);
                            tab.appendChild(r);
                        }

                        r = document.createElement("tr");
                        c = document.createElement("td");

                        c.style.color = "#FFE066";
                        c.style.textAlign = "center";
                        c.textContent = data[i]["nome"];
                        r.appendChild(c);

                        c = document.createElement("td");
                        c.style.color = "#FFE066";
                        c.style.textAlign = "center";
                        c.textContent = data[i]["quantita"];
                        r.appendChild(c);

                        c = document.createElement("td");
                        c.style.color = "#FFE066";
                        c.style.textAlign = "center";
                        c.textContent = data[i]["catalogazione"];
                        r.appendChild(c);

                        c = document.createElement("td");
                        c.style.color = "#FFE066";
                        c.style.textAlign = "center";
                        let numero = parseFloat(data[i]["calorie"]);
                        if(cifre_dopo_virgola(numero))
                            numero = numero.toFixed(2);
                        calorie_prese += parseFloat(numero);

                        c.textContent = numero + " Kcal";
                        r.appendChild(c);
                        tab.appendChild(r);

                        c = document.createElement("td");
                        c.style.color = "#FFE066";
                        c.style.textAlign = "center";
                        c.style.display = "none";
                        c.textContent = data[i]["ID"];
                        let stringa = "r" + i;
                        r.id = stringa;
                        r.appendChild(c);                       

                        cibi_inseriti++;
                    }
                    inseriti.appendChild(tab);
                    calorie_perse = parseFloat(data["calorie perse"]);
                    let float = parseFloat(parseFloat(calorie_perse) + parseFloat(fabbisogno_calorico));
                    if(cifre_dopo_virgola(float))
                        float = float.toFixed(2);

                    let valore = parseFloat(calorie_prese);
                    if(cifre_dopo_virgola(valore))
                        valore = valore.toFixed(2);

                    p.textContent = valore + "/" + float + " Kcal";

                    if(data["length"] > 0){
                        // Se ci sono cibi riattiva i pulsanti Cancella e Aggiorna
                        document.getElementById("cancella").disabled = false;
                        document.getElementById("aggiorna").disabled = false;
                    }
                  })
    .catch(error => window.alert(error));

    // In sintesi, questa funzione consente di visualizzare in modo dettagliato i pasti registrati, inclusi il nome del cibo, 
    // la quantità, l'unità di misura e le calorie
}

// Questa funzione viene chiamata quando l'utente fa click sul pulsante "Cancella" nell'applicazione
function click_cancella(){
    const tabella = inseriti.firstChild;
    const righe = tabella.getElementsByTagName("tr");
    for(let i = 1; i < cibi_inseriti + 1; i++){
        let colonne = righe[i].getElementsByTagName("td");
        
        for(let j = 0; j < colonne.length; j++){
            // Se una colonna ha lo stile color rosso, significa che era pronta per la cancellazione, ma và tolta 
            // la funzione elimina(event)
            if(colonne[j].style.color == "red"){
                colonne[j].style.color = "#FFE066";
                aggiorna.disabled = false;
                righe[i].removeEventListener("click", elimina);
            }
            // Se una colonna ha lo stile color giallo (#FFE066), significa che non è pronta per essere cancellata e quindi 
            // si inizializzano le righe con la funzione elimina(event)
            else{
                colonne[j].style.color = "red";
                aggiorna.disabled = true;
                righe[i].addEventListener("click", elimina);
            }
        }
    }
}

// Questa funzione viene chiamata quando un utente fa click su una riga (un pasto registrato) all'interno della tabella
async function elimina(event){
    let identita = event.currentTarget.id;
    let riga = document.getElementById(identita);

    const colonne = riga.getElementsByTagName("td");
    let _data = new FormData();
    let contenuti = [];

    for(let i = 0; i < 5; i++){
        contenuti.push(colonne[i].textContent);
    }
        
    _data.set("ID", parseInt(contenuti[4]));
    _data.set("quantita", parseInt(contenuti[1]));
    _data.set("catalogazione", contenuti[2]);

    await fetch("../PHP/requests/eliminazione_pasto.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {
                    if(data["result"]){
                        const tabella = riga.parentNode;
                        tabella.removeChild(riga);
                        
                        cibi_inseriti--;
                        calorie_prese -= parseFloat(contenuti[3]);
                        if(cibi_inseriti == 0){
                            aggiorna.disabled = true;      
                            cancella.disabled = true;      
                            inseriti.removeChild(tabella);                    
                        }
                    }

                    let msg = document.getElementById("msg");
                    msg.textContent = data["msg"];
                    setTimeout(function(){
                                            let _msg = document.getElementById("msg");
                                            _msg.textContent = "";
                                         }, 3000);

                    let p = document.getElementById("conto_calorie");

                    let valore = parseFloat(calorie_prese);
                    if(cifre_dopo_virgola(valore))
                        valore = valore.toFixed(2);

                    let fabb = parseFloat(parseFloat(calorie_perse) + parseFloat(fabbisogno_calorico));
                    if(cifre_dopo_virgola(fabb))
                        fabb = fabb.toFixed(2);

                    p.textContent = valore + "/" + fabb + " Kcal";
                  })
    .catch(error => window.alert(error));
}

// Questa funzione viene chiamata quando l'utente fa clic sul pulsante "Aggiorna" nell'applicazione.
// A seconda della variabile globale aggiornamento vede se è necessario aggiornare tutte le righe della tabella 
// (i pasti registrati) e riportare tutto con i valori aggiornati oppure se modificare l'interfaccia per far 
// registrare i cibi registrati.
async function click_aggiorna() {
    const tabella = inseriti.firstChild;
    const righe = tabella.getElementsByTagName("tr");

    for(let i = 1; i < cibi_inseriti + 1; i++){
        let colonne = righe[i].getElementsByTagName("td");
        if(aggiornamento){
            let controllo = await esegui_aggiornamento(tabella, righe, colonne, i);
            if(controllo)
                i--;
        }
        else
            abilita_modifica(colonne);
    }

    aggiornamento = !aggiornamento;
}

// Questa funzione esegue l'aggiornamento dei dati dei pasti
async function esegui_aggiornamento(tabella, righe, colonne, indice){
    let bool = false;
    if(indice == 1)
        calorie_prese = 0;
    
    cancella.disabled = false;
    let _data = new FormData();
    let contenuti = [];

    for(let j = 0; j < 5; j++){
        if(j != 1)
            contenuti.push(colonne[j].textContent);
        else{
            if(colonne[j].firstChild.value == "")
                contenuti.push(0);
            else
                contenuti.push(parseInt(colonne[j].firstChild.value));
        }
    }

    _data.set("ID", parseInt(contenuti[4]));
    _data.set("quantita", parseInt(contenuti[1]));
    _data.set("catalogazione", contenuti[2]);

    await fetch("../PHP/requests/aggiornamento_pasto.php", { method: 'POST', body: _data })
    .then(response => response.json())
    .then(data => {                                      
                    if(data["result"]){
                        if(parseInt(contenuti[1]) == 0){
                            bool = true;
                            cibi_inseriti--;
                            tabella.removeChild(righe[indice]);
                            if(cibi_inseriti == 0){
                                while(tabella.hasChildNodes())
                                    tabella.removeChild(tabella.firstChild);
                                aggiorna.disabled = true;
                                cancella.disabled = true;
                            }
                        } 
                        else{
                            colonne[1].removeChild(colonne[1].firstChild);
                            colonne[1].textContent = parseInt(contenuti[1]);
                            let numero = parseInt(contenuti[1]) * parseFloat(data["calorie"]);

                            if(cifre_dopo_virgola(numero)) 
                                numero = numero.toFixed(2);
                                
                            calorie_prese += parseFloat(numero);
                            colonne[3].textContent = parseFloat(numero) + " Kcal";

                            for(let j = 0; j < 5; j++)
                                colonne[j].style.color = "#FFE066";
                        }
                    }

                    let msg = document.getElementById("msg");
                    msg.textContent = data["msg"];
                    setTimeout(function(){
                                            let _msg = document.getElementById("msg");
                                            _msg.textContent = "";
                                         }, 3000);

                    let p = document.getElementById("conto_calorie");

                    let valore = parseFloat(calorie_prese);
                    if(cifre_dopo_virgola(valore))
                        valore = valore.toFixed(2);
                    
                    let fabb = parseFloat(parseFloat(calorie_perse) + parseFloat(fabbisogno_calorico));
                    if(cifre_dopo_virgola(fabb))
                        fabb = fabb.toFixed(2);

                    p.textContent = valore + "/" + fabb + " Kcal";
                  })
    .catch(error => window.alert(error));

    return bool;
}

// Questa funzione abilita la modifica delle colonne
function abilita_modifica(colonne) {
    cancella.disabled = true;
    for(let i = 0; i < colonne.length; i++){
        if(i == 1){
            const input = document.createElement("input");
            input.value = colonne[i].textContent;
            input.type = "number";
            input.min = 0;
            colonne[i].textContent = "";
            colonne[i].appendChild(input);
        }
        colonne[i].style.color = "blue";
    }
}

function cifre_dopo_virgola(numero){
    // Converto il numero in una stringa
    var numero_stringa = numero.toString();
  
    // Uso un'espressione regolare per verificare se ci sono cifre dopo la virgola
    var regex = /(\.\d*[1-9])|(\.\d*[1-9]0+)$/;
    return regex.test(numero_stringa);
}

function inizializzazione(){
    form.addEventListener("submit", ricerca);
    barra.addEventListener("input", ricerca);
    document.getElementById("cancella").disabled = true;
    cancella.addEventListener("click", click_cancella);
    document.getElementById("aggiorna").disabled = true;
    aggiorna.addEventListener("click", click_aggiorna);
    recupera_fabbisogno();
}