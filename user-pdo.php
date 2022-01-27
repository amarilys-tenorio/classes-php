<?php

class UserPdo 
{
    
    private $id;
    public $login = '';
    public $email = '';
    public $firstname = '';
    public $lastname = '';

    public function __construct(
    $login = NULL,
    $email = NULL,
    $firstname = NULL,
    $lastname = NULL)
    {

        $this->login = $login;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

    }

    public function registerPdo($login, $email, $password, $firstname, $lastname)
    {

        $this->login = $login;
        $this->email = $email;   
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        // Connexion bdd
        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        try {
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $connect->prepare("INSERT INTO utilisateurs (login, email, password, firstname, lastname) VALUES (:login,:email,:password,:firstname,:lastname)");

            // bindparam lie un paramètre à un nom de variable spécifique
            $stmt->bindparam(":login", $login);
            $stmt->bindparam(":email", $email);    
            $stmt->bindparam(":password", $passwordhash);
            $stmt->bindparam(":firstname", $firstname);
            $stmt->bindparam(":lastname", $lastname);
            $stmt->execute();

            return $stmt;
            // S'il y a une erreur
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function connectPdo($login, $password)
 
    {

        $this->login = $login;
        $this->password = $password;
    
        // Connexion bdd
        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;
    
        try {
                // prepare: Prépare une requête à l'exécution et retourne un objet (la on selectionne tte les infos de l'utilisateur à partir du login)
                $stmt = $connect->prepare("SELECT * FROM utilisateurs WHERE login=:login LIMIT 1");
                //  execut: Exécute une requête préparée
                $stmt->execute(array('login' => $login));
                // fetch: Récupère la ligne suivante d'un jeu de résultats PDO (pas compris)
                $userRow = $stmt->fetch();
                // rowCount: Retourne le nombre de lignes affectées par le dernier appel à la fonction PDOStatement::execute()
            if ($stmt->rowCount() > 0) {
                // Vérifie qu'un mot de passe correspond à un hachage et le reste regarde si tt est identique aux infos du user pour bien le connecter
                if (password_verify($password, $userRow['password'])) {
                        $_SESSION['id'] = $userRow['id'];
                        $_SESSION['login'] = $userRow['login'];
                        $_SESSION['email'] = $userRow['email'];
                        $_SESSION['firstname'] = $userRow['firstname'];
                        $_SESSION['lastname'] = $userRow['lastname'];
                        
                    return true;
                } else {
                    return false;
                }
            }
            // S'il y a une erreur
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    public function disconnectPdo()
    {

        $this->id = null;
        $this->login = null;
        $this->password = null;
        $this->email = null;
        $this->firstname = null;
        $this->lastname = null;

        echo "You are disconnected.";

    }

    public function deletePdo($id)
    {
        //  Connexion bdd
        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        try {
            // Ptite requete pour supprimer l'utilisateur connecté en fonction de son id
            $stmt = $connect->prepare("DELETE FROM utilisateurs WHERE utilisateurs.id = :id  LIMIT 1");
            //  execut: Exécute une requête préparée
            $stmt->execute(array('id' => $id));
            // fetch: Récupère la ligne suivante d'un jeu de résultats PDO (pas compris)
            $userRow = $stmt->fetch();
            echo 'Your account has been deleted and you are disconnected.';

            }
            // Au cas ou il y a une erreur 
            catch (PDOException $e) {
                echo $e->getMessage();
            }
        
    }   

    public function updatePdo($login, $password, $email, $firstname, $lastname)
    {

        $this->login = $login;
        $this->password = $password;
        $this->email = $email;   
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        try {
            $stmt = $connect->prepare("UPDATE utilisateurs SET login = '$login' , password = '$password', email = '$email', firstname = '$firstname' , lastname = '$lastname' WHERE utilisateurs.id = :id ");
            $stmt->execute(array('id' => $_SESSION['id']));
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }


    }

    public function isConnectedPdo()
    {

        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        if(isset($_SESSION['id']) == true)
        {
            echo 'The user is connected';
        }

        else {
            if(isset($_SESSION['id']) == false)
            {
                echo 'The user is not connected';
            }

        }

    }

    public function getAllInfosPdo()
    {

        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        $var = $connect->query("SELECT * FROM utilisateurs WHERE utilisateurs.id = id LIMIT 1");
        $connect->exec('$var');

        $result = $var->fetch(PDO::FETCH_ASSOC);

        // var_dump($result);

        return print_r($result);
        

    }

    public function getLoginPdo()
    {
        
        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        $var = $connect->query("SELECT login FROM utilisateurs WHERE utilisateurs.id = id LIMIT 1");
        $connect->exec('$var');

        $result = $var->fetch(PDO::FETCH_ASSOC);

        // var_dump($result);

        return print_r($result);
        
    }

    public function getEmailPdo()
    {

        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        $var = $connect->query("SELECT email FROM utilisateurs WHERE utilisateurs.id = id LIMIT 1");
        $connect->exec('$var');

        $result = $var->fetch(PDO::FETCH_ASSOC);

        // var_dump($result);

        return print_r($result);
        
    }

    public function getFirstnamePdo()
    {

        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        $var = $connect->query("SELECT firstname FROM utilisateurs WHERE utilisateurs.id = id LIMIT 1");
        $connect->exec('$var');

        $result = $var->fetch(PDO::FETCH_ASSOC);

        // var_dump($result);

        return print_r($result);

    }

    public function getLastnamePdo()
    {

        $connect = new PDO('mysql:host=localhost;dbname=classes', 'root', '') ;

        $var = $connect->query("SELECT lastname FROM utilisateurs WHERE utilisateurs.id = id LIMIT 1");
        $connect->exec('$var');

        $result = $var->fetch(PDO::FETCH_ASSOC);

        // var_dump($result);

        return print_r($result);

    }

}





