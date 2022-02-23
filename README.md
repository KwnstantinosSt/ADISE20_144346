# ADISE20_144346

Url προς την εφαρμογή: https://users.iee.ihu.gr/~it144346/ADISE20_144346/   **Σημείωση δεν τρέχει σωστά στο server users παρόλο που την ανέβασα.Σε localhost δουλεύει πλήρως χωρίς προβλήματα!!!

Τάβλι - Πλακωτό

Στο συγκεκριμένο repository έχει γίνει ανάπτυξη ενος Web Api το οποίο περιγράφει ένα παιχνίδι τύπου τάβλι - πλακωτό το οποίο μπορούν να παίξουν δυο παίκτες μεταξύ τους. Έχει γίνει πλήρη ανάπτυξη του API του παιχνδιού και ενός πολύ απλού front end κομματιου(HTML-CSS-JS) το οποίο αξιοποιεί κάποιες βασικές λειτουργίες του API.

Αναλυτικότερα το back end κομμάτι αξιοποιεί τις τεχνολογίες (PHP,MYSQL).

Μέσα στον φάκελο <<API>> υπάρχουν τα αρχεία board.php, dices.php, players.php, status.php τα οποία κάνοντας κατάλληλες κλήσεις μέσω HTTP REQUESTS τα οποία αξιοπούν τις μεθόδους (GET,POST,PUT,DELETE) του HTTP προτόκολλου απαντούν κατάλληα και επιστέφουν τα αποτελέσματα σε JSON μορφή.Αν πάνε όλα καλα επιστρέφει "200" ο server ειδάλλως 
"400".
  
Στον φάκελο <<CORE>> υπάρχουν τα αρχεία backgammon.php το οποίο περιλαμβάνει όλες τις κλάσεις και τις μεθόδους που χρησιμοποιεί η εφαρμογή και το αρχείο initialize.php 
το οποίο περιλαμβάνει τις διαδρομές αρχειων ./backgammon.php και το αρχείο ως προς τη σύνδεση με τη βάση που βρίσκετε στo φάκελο include/dbconnect.php.
Αυτο το αρχείο initialize.php το ενσωματόνουν όλα τα αρχεία του φακέλου <<API>>.
  
Ειδικότερα στον φάκελο API το :

board.php -> 
  localhost/backgammon/api/board.php (GET)  Με κλήση GET αυτό το αρχείο επιστρέφει όλο το τρέχων board του παιχνιδιού και το επιστρέφει σε JSON μορφή(200:OK , 404:NOT OK).
  
  localhost/backgammon/api/board.php (POST)  Με κλήση POST αυτό το αρχείο επαναφέρει όλο το board του παιχνιδιού στην αρχική μορφή και το επιστρέφει σε JSON μορφή(200:OK , 404:NOT OK).
  
   localhost/backgammon/api/board.php?pos=1 (GET)  Με κλήση GET με παράμετρο (?pos=1) αυτό το αρχείο επιστρέφει τη τρέχων θέση του board και το επιστρέφει σε JSON μορφή (200:OK , 404:NOT OK). 
   
   localhost/backgammon/api/board.php (PUT)  Με κλήση PUT με παραμετρους στο body του request ("pos_old":24, "pos_new":19) θα μεταφέρει το πούλι απο τη παλιά θέση του στη καινούργια σύμφωνα με τους επιτρεπτούς κανόνες του παιχνιδιού και του ζαριού.
   
   
 dices.php ->   
 
    localhost/backgammon/api/dices.php (GET) ρίχνει και επισρέφει τα ζάρια του παίκτη. Αν κληθεί με παράμετρο (?d=1) θα επιστρέψει μόνο εκείνο το ζάρι.
   
   
 players.php ->   
 
    localhost/backgammon/api/players.php (GET) επιστρέφει όλους του παίκτες με τα στοιχεία τους , αν δοθεί παράμετρος (?p="B") θα επιστραφεί μόνο ο μαύρος παίκτης.
    
   localhost/backgammon/api/players.php (PUT) δέχεται τρεις παραμέτρους (  "username":"kwstas", "piece_color":"W","auth":"f8sd6sdsfd687df6") και τους καταχωρεί στην βάση.
    
   localhost/backgammon/api/players.php (DELETE) δέχεται με παραμέτρο ( "piece_color":"W") διαγράφει τον συγκεκριμένο παίκτη από τη βάση.
   
 status.php -> 
    localhost/backgammon/api/status.php (GET) επιστρέφει την κατάσταση του παιχνιδιού και ποιανού σειρά είναι να παίξει.
    
    localhost/backgammon/api/status.php (POST) αλλάζει τη σειρά του παίκτη που παίζει.Η κατάσταση αλλάζει αυτόματα απο τη βάση με trigger.
    
 Τέλος στον φάκελο DATABASE/ υπάρχουν αναλυτικα αρχεία όσον αφορά τη δημηουργία όλης της βάσης με τους πίνακες , triggers και proccedures καθώς και τις τιμές που χρησιμοποιήθηκαν στον πίνακα board.
 
 
    
