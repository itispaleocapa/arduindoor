/**
 * Arduindoor - http://arduindoor.altervista.org/
 * Copyright © 2015 Antonio Chioda <antonio.chioda@gmail.com>
 * Copyright © 2015 Bruno Palazzi <brunopalazzi0@gmail.com>
 * Copyright © 2015 Giacomo Perico <giacomo.perico@hotmail.it>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

#include <dht.h>                                //includo libreria per sensore umidita temperatura
#define dht_dpin A0                             //setto pin cui è collegato
dht DHT;                                        //avvio dht per usare la libreria
#include <Wire.h>               //includo probabilmente libreria releshield
#include "RTClib.h"                     //includo libreria rtc shield
#define Pin_elettrovalvola 7                    //setto pin dedicato a elettrovalvola
#define Pin_stufetta 2                  //setto pin dedicato a stufetta
#define Pin_luce 6              //setto pin dedicato a luce
#define Pin_terreno A3                           //decido che il pin del terreno è il 3

#include <SPI.h>
#include <Ethernet.h>                           //includo le 2 librerie per la ethernet shield
int ore_luce[] = {07, 00, 23, 00};              //orario accensione(ora,minuti) orario spegnimento(ora,minuti)
int terreno =
    0;                                //setto il valore dell'umidita terreno di default a 0
float temp_aria;                //istanzio le variabili per umidità e temperatura
float umid_aria;
RTC_DS1307
RTC;                 //attivo rtc shield per conoscere ora grazie a libreria
int stato_luce = 0;                 //quando 0 luce spenta, quanto 1 luce accesa
// assegno il mac al mio arduindoor, ****DOVRà ESSERE DIVERSO PER OGNI ARDUINDOOR ALTRIMENTI CI SARANNO ARDUINDOOR SPARITI NEL NULLA****
byte mac[] =
{
    0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED
};
// assegno l'indirizzo ip, stando attento che sia compatibile con la rete altrimenti potrei causare impossibilità di connessioni al db da parte di arduindoor
// IPAddress ip(10, 1, 200, 48);


// IPAddress myDns(8, 8, 8, 8);                    // assegno indirizzo dns per risolvere le richieste, sempre meglio usare quello di google
EthernetClient client;                          // creo un client ethernet

IPAddress server(85, 10, 204,
                 132);             // fisso l'indirizzo del server, ottenuto tramite nslookup del nostro sito, questo è l'indirizzo della macchina che ospita la VM su cui funziona il nostro server apache ed ospita il sito
int temperatura =
    20;                           //inizializzo temperatura e terra con valori di default intermedi così qualora ci fossero problemi di connessione sono prevenuto
int terra = 600;
int last_update =
    0;                            //setto last update che serve a risolvere i problemi di polling
void setup()
{
    Wire.begin();                                 //inizializzo la wire necessaria per la comunicazione in I2C della rtc
    RTC.begin();                                  //avvio la rtc e la comunicazione sulla porta 9600 così da poter far debug qualora necessario
    Serial.begin(9600);
    Ethernet.begin(
        mac);               //avvio la scheda ethernet e faccio un delay di un secondo per dargli il tempo di configurarsi
    delay(1000);
    pinMode(Pin_luce, OUTPUT);                    //setto pin luce come output
    pinMode(Pin_elettrovalvola,
            OUTPUT);          //setto il pin che apre chiude l'elettrovalvola come output
    digitalWrite(Pin_elettrovalvola,
                 LOW);        //per sicurezza spengo il pin di alimentazione elettrovalvola per evitare allagamenti
    pinMode(Pin_stufetta,
            OUTPUT);        // setto stufetta come output e la setto spenta che in caso di guasti è sempre la cosa migliore
    digitalWrite(Pin_stufetta, LOW);
    digitalWrite(Pin_luce,
                 HIGH);                 //imposto la luce accesa in caso di problemi è meglio accesa che spenta
    RTC.adjust(DateTime(__DATE__,
                        __TIME__)); //se shield è nuova non ha in memoria data e ora quindi le trasferisco e, qualora fosse gia settata ma connessa al pc si aggiorna, se invece è disconnessa usa la data e l'ora che ha in memoria
}

void loop()
{
    Serial.println("inizio");                     //inizio e scrivo i valori di temperatura e terra
    Serial.print("umidita terreno da raggiungere: ");
    Serial.println(terra);
    Serial.print("temperatura aria da raggiungere: ");
    Serial.println(temperatura);
    terreno = analogRead(
                  Pin_terreno);    //leggo e memeorizzo nella variabile terreno il valore letto, poi lo scrivo
    Serial.print("il valore letto del terreno: ");
    Serial.println(terreno);
    if(terreno >=
            1023)                           //qualora avessi impostato un terreno assolutamente arido bagno solo al raggiungimento del valore e chiudo subito
    {
        Serial.println("bagno");
        digitalWrite(Pin_elettrovalvola,
                     HIGH);     //apro la valvola per bagnare il terreno
        delay(1000);
        digitalWrite(Pin_elettrovalvola,
                     LOW);      //aspetto e chiudo per far restare il terreno il più secco possibile
    }
    else
        if(terreno >
                terra)          //controllo stato terreno, se è maggiore significa che è più arido di quanto settato
        {
            Serial.println("bagno");
            digitalWrite(Pin_elettrovalvola,
                         HIGH);     //apro la valvola per bagnare il terreno
        }
        else
            if(terreno < terra -
                    200)                //controllo in alternativa all'if, qualora fosse più umido di almeno 200 chiudo, questo per evitare un continuo switch on/switch off
            {
                Serial.println("non bagno");
                digitalWrite(Pin_elettrovalvola,
                             LOW);     //chiudo la valvola avendo bagnato a sufficienza
            }
            else
                if(terreno <
                        100)                        //qualora il valore impostato da raggiungere sia minore di 200 la valvola starebbe sempre aperta, per ovviare pongo un limite di sicurezza
                {
                    Serial.println("non bagno");
                    digitalWrite(Pin_elettrovalvola,
                                 LOW);     //chiudo la valvola avendo bagnato a sufficienza
                }
    DHT.read11(
        dht_dpin);                         //chiamo la funzione implementata nella libreria per lettura del sensore di umidita e temperatura
    umid_aria =
        DHT.humidity;                     //uso le funzioni per estrarre e salvare nelle apposite variabili la tempertaura e l'umidita dell'aria
    temp_aria = DHT.temperature;
    Serial.print("Umidita aria letta: ");
    Serial.println(umid_aria);
    Serial.print("temperatura aria letta: ");
    Serial.println(temp_aria);
    if(temp_aria < temperatura -
            2)               //pongo un limite di -2 gradi sotto la temperatura impostata per evitare un continuo switch on/switch off della stufetta
    {
        digitalWrite(Pin_stufetta,
                     HIGH);           //accendo la stufetta se sono sotto al limite minimo
        Serial.println("accendo la stufetta");
    }
    else
        if(temp_aria > temperatura +
                2)  //controllo e lascio acceso finchè non raggiungo almeno +2 gradi di differenza rispetto a quello prefissato
        {
            digitalWrite(Pin_stufetta,
                         LOW);                //spengo la stufetta raggiunta la temperatura desiderata
            Serial.println("spengo stufetta");
        }
        else
            if(temp_aria >
                    32)                       //se temperatura maggiore della soglia massima sopportabile da una pianta normale spengo per evitare danni
            {
                digitalWrite(Pin_stufetta,
                             LOW);                // spengo la stufetta in caso di emergenza
                Serial.println("spengo stufetta");
            }
    if(RTC.isrunning())                //se la scheda va controllo la luce rispetto agli orari impostati
    {
        DateTime now =
            RTC.now();           //acquisisco data e ora attuale tramite le funzioni fornite con la libreria
        int _hour =
            now.hour();             //scompongo ora e minuti con le funzioni fornite
        int _minute = now.minute();
        if(stato_luce == 0 && ((_hour > ore_luce[0] && _hour < ore_luce[2])
                               || ((_hour == ore_luce[0] && _minute > ore_luce[1]) || (_hour == ore_luce[2]
                                       && _minute <
                                       ore_luce[3]))))   //se deve essere accesa ma è spenta la accendo altrimenti non faccio nulla
        {
            stato_luce = 1;                          //salvo lo stato della luce modificato
            digitalWrite(Pin_luce, HIGH);
            Serial.println("Accendo luce");
        }
        else
            if(stato_luce == 1 && ((_hour < ore_luce[0] && _hour > ore_luce[2])
                                   || (_hour == ore_luce[2] && _minute >= ore_luce[3]) || (_hour == ore_luce[0]
                                           && _minute <= ore_luce[1])))   //se deve essere spenta ma è accesa la spengo
            {
                stato_luce = 0;                          //salvo lo stato della luce modificato
                Serial.println("Spengo luce");
                digitalWrite(Pin_luce, LOW);
            }
    }
    else                                         //se la scheda rtc non funziona per sicurezza accendo la luce
    {
        Serial.println("La rtc non va");
        digitalWrite(Pin_luce, HIGH);
        stato_luce = 1;
    }
    DateTime now =
        RTC.now();                    //scrivo come debug l'ora e la data attuale
    Serial.print(now.year(), DEC);
    Serial.print('/');
    Serial.print(now.month(), DEC);
    Serial.print('/');
    Serial.print(now.day(), DEC);
    Serial.print(' ');
    Serial.print(now.hour(), DEC);
    Serial.print(':');
    Serial.print(now.minute(), DEC);
    Serial.print(':');
    Serial.print(now.second(), DEC);
    Serial.println();
    if(last_update !=
            now.hour())              //risolvo il problema di polling effettuando una richiesta al server ad ogni minuto NB durante i test è 1 all'ora causa limite query
    {
        Serial.println("FACCIO L'UPDATE");
        httpRequest();                             //chiamo le funzioni per l'update dello stato consultabile dal sito e poi chiamo la funzione per fare l'update dei valori guida di arduindoor
        updateRequest();
        last_update =
            now.hour();                //aggiorno il minuto dell'ultimo update così l'update avviene solo una volta ogni minuto
    }
}

void httpRequest()                                 //funzione per aggiornare i valori presenti sul sito
{
    client.stop();                                   //fermo tutti i possibili client attivi
    if(client.connect(server, 80))                   //mi connetto al server
    {
        Serial.println("connecting...");
        client.print("GET /php_sito_arduino.php?temperatura=");     //assemblo la richiesta con metodo Get allegando i valori
        client.print(temp_aria);
        client.print("&&umidita=");
        client.print(umid_aria);
        client.print("&&irrigazione=");
        client.print(terreno);
        client.print("&&stato_luce=");
        client.print(stato_luce);
        client.print("&&mac=");
        for(int i = 0; i < sizeof(mac);
                i++)                        //allego il mac leggendo valore per valore fino alla fine del mac
        {
            client.print(mac[i]);
        }
        client.println(" HTTP/1.0");
        client.print("Host: ");                                    //importante per i server su cui girano VM perchè se non specifico l'host la richiesta viene mandata al sito principale
        client.println("arduindoor.altervista.org");
        client.println("Connection: close");
        client.println();
        client.println();
        client.stop();
        Serial.println("update dati sito avvenuto");
    }
    else
    {
        Serial.println("connection failed");
    }
}

void updateRequest()                                          //download dei nuovi valori dal sito
{
    client.stop();                                              //fermo tutti i possibili client attivi
    if(client.connect(server, 80))                              //mi connetto
    {
        Serial.println("connecting...come client");
        client.print("GET /arduino_json.php?mac=");               //assemblo la richiesta con metodo Get ed allego il mac con metodo identico a quello precedente
        for(int i = 0; i < sizeof(mac); i++)
        {
            client.print(mac[i]);
        }
        client.println(" HTTP/1.0");
        client.print("Host: ");                                   //importante per i server su cui girano VM per lo stesso motivo di prima
        client.println("arduindoor.altervista.org");
        client.println("Connection: close");
        client.println();
        client.println();
        bool beg =
            false;                                         //variabile per capire quando inizia la stringa json
        bool salva =
            false;                                       //variabile per capire quando iniziano i valori veri e propi da salvare
        int parametri =
            0;                                        //variabile per sapere quanti parametri ho passato
        String val[7] =
            "";                                       //creo 7 string per contenere i parametri
        while(client.available()
                || !beg)                         // questa è quella iniziare però io l'ho corretta mettendo un beg per evitare che entri nel ciclo se non c'è nessun client
            //while (client.available() || beg)
        {
            char in = client.read();                                //leggo carattere per carattere
            if(in == '{')                                           //significa che inizia la risposta json, reimposto beg
            {
                beg = true;
            }
            else
                if(beg)                                            //se è iniziata la lettura all'interno della stringa json, con else evito di entrare gia alla lettura del carattere che da il via alla stringa
                {
                    if(in == '}')                                         //se leggo il carattere terminatore significa che devo terminare il ciclo
                    {
                        beg = false;
                        client.stop();
                        break;
                    }
                    if(in == ':')                                         //se è il carattere che sta prima di un valore aumento il numero dei parametri salvati e do l'inizio al salvataggio dei parametri
                    {
                        parametri++;
                        if(salva == false)
                        {
                            salva = true;
                            Serial.print("valori letti: ");
                        }
                    }
                    if(in == ',')                                         //se carattere separatore di valori termino il salvataggio dei parametri
                    {
                        salva = false;
                    }
                    if(salva == true
                            && in != ':')                        //se c'è da salvare e non è il carattere iniziale allego
                    {
                        Serial.print(in);
                        val[parametri - 1] += in;
                    }
                }
        }
        if(parametri ==
                3)                                       //alla fine se i parametri sono 3 significa che non devo reimpostare l'ora quindi salvo solo gli altri
        {
            temperatura = val[0].toInt();
            terra = val[1].toInt();
        }
        else
            if(parametri ==
                    7)                                  //se i parametri sono 7 significa che devo salvare anche l'ora quindi modifico tutti i valori necessari
            {
                ore_luce[0] = val[0].toInt();
                ore_luce[1] = val[1].toInt();
                ore_luce[2] = val[2].toInt();
                ore_luce[3] = val[3].toInt();
                temperatura = val[4].toInt();
                terra = val[5].toInt();
            }
    }
}
