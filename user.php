<?php

class User 
{

    // Ce sont les attributs
    private $id;
    public $login;
    public $email;
    public $password;
    public $firstname;
    public $lastname;

    // Méthode qui initialise les différents attributs de mon objet
    public function __construct($id = null,$login = null,$email = null,$firstname = null,$lastname = null)
    {

        $this->id=$id;
        $this->login=$login;
        $this->email=$email;
        $this->firstname=$firstname;
        $this->lastname=$lastname;

    }

    // Méthode pour créer l’utilisateur en BDD et retourne un tableau contenant l’ensembles des informations de ce même utilisateur.
    public function register($login, $email, $password, $firstname, $lastname)
    {

        $this->login=$login;
        $this->email=$email;
        $this->password=$password;
        $this->firstname=$firstname;
        $this->lastname=$lastname;

        // Fonction mysqli pour se connecter à la base de donner
        $connect = mysqli_connect('localhost', 'root', '', 'classes');


        // Requête SQL qui crée un utilisateurs dans la BDD
        $request = "INSERT INTO utilisateurs(login,password,email,firstname,lastname) VALUES ('$login', '$password', '$email', '$firstname', '$lastname')";

        // Fonction mysqli qui exécute une requête sur la base de données
        $query = mysqli_query($connect, $request);

    }

    // Méthode qui connecte l’utilisateur, et donne aux attributs de la classe les valeurs correspondantes à celles de l’utilisateur connecté
    public function connect($login, $password)
    {

        $this->login=$login ;
        $this->password=$password;

        
        // Fonction mysqli pour se connecter à la base de donner
        $connect = mysqli_connect('localhost', 'root', '', 'classes');
        
        // Requête SQL pour selectionner table utilisateurs 
        $request = "SELECT * FROM utilisateurs";

        // Fonction mysqli qui exécute une requête sur la base de données
        $query = mysqli_query($connect, $request);

        //  Fonction mysqli qui récupère toutes les lignes de résultats dans un tableau
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);


        // Boucle pour voir si l'utilisateur est bien connecté ou pas
        for ($i = 0; isset($result[$i]); $i++) {
            $id = $result[$i]['id'];
            $passwordcheck = $result[$i]['password'];
            $logincheck = $result[$i]['login'];


            if ($login == $logincheck && $password == $passwordcheck) {
                $_SESSION['id'] = $id;
                var_dump($_SESSION['id']);
                echo "You are connected" . '<br/>';
            }
        }
        if ($passwordcheck == FALSE) {
            echo "Error";
        }


    }

    // Méthode pour deconnecter l'utilisateur
    public function disconnect()
    {

        // Fonction mysqli pour se connecter à la base de donner
        $connect = mysqli_connect('localhost', 'root', '', 'classes');

        // Selectionne la table utilisateurs 
        $request = "SELECT * FROM utilisateurs";

        // Fonction mysqli pour fermer une connexion
        mysqli_close($connect);

        // Détruit une session 
        session_destroy();

        echo "You are disconnected";

    }

    // Méthode pour déconnecter et supprimer l'utilisateur
    public function delete()
    {

        // Fonction mysqli pour se connecter à la base de donner
        $connect = mysqli_connect('localhost', 'root', '', 'classes');

        // Requête SQL pour supprimer un utilisateur
        $delete = "DELETE FROM utilisateurs WHERE login = utilisateurs.login LIMIT 1";

        // Prépare et exécute une requête SQL
        $rsDelate = $connect->query($delete);
        echo "Deleted rows :" . $connect->affected_rows;
        $connect->close();
    }

    // Méthode pour mettre à jour les attributs et modifier les infos en BDD
    public function update($login, $password, $email, $firstname, $lastname)
    {

        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;


        // Fonction mysqli pour se connecter à la base de donner
        $connect = mysqli_connect('localhost', 'root', '', 'classes');

        // Requête SQL pour mettre à jour les attributs de l’objet, et modifie les informations en BDD
        $request = "UPDATE utilisateurs SET login = '$login' , password = '$password', email = '$email', firstname = '$firstname' , lastname = '$lastname' WHERE id = '$this->id'";
        $query = $connect->query($request);

    }

    public function isConnected()
    {

        
        // Fonction mysqli pour se connecter à la base de donner
        $connect = mysqli_connect('localhost', 'root', '', 'classes');

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

    // Méthode qui retourne un tableau contenant l’ensemble des informations de l’utilisateur
    public function getAllInfos()
    {
        // On défini l'id quelque part
        $this->id=$_SESSION['id']; 

        // On se connecte
        $connect = mysqli_connect('localhost', 'root', '', 'classes');

        // Petite requête pour recupérer les infos dont on a besoin selon l'utilisateurs connecté en fonction de son id du coup il me semble 
        $request = mysqli_query($connect,"SELECT * FROM `utilisateurs` WHERE id='$this->id'");

        // On le met dans un tableau assoc
        $assoc = mysqli_fetch_assoc($request);

        $this->login = $assoc['login'];
        $this->password = $assoc['password'];
        $this->email = $assoc['email'];
        $this->firstname = $assoc['firstname'];
        $this->lastname = $assoc['lastname'];


        var_dump($assoc);
        return $assoc;

    }
  
    public function getLogin()
    {
 
        // On défini l'id quelque part
        $this->id=$_SESSION['id']; 

        // On se connecte
        $connect = mysqli_connect('localhost', 'root', '', 'classes');

        // Petite requête pour recupérer les infos dont on a besoin selon l'utilisateurs connecté en fonction de son id du coup il me semble 
        $request = mysqli_query($connect,"SELECT login FROM `utilisateurs` WHERE id='$this->id'");

        // On le met dans un tableau assoc
        $assoc = mysqli_fetch_assoc($request);

        $this->login = $assoc['login'];


        // var_dump($assoc);
        return $assoc;

    }

    public function getEmail()
    {
        // On défini l'id quelque part
        $this->id=$_SESSION['id']; 

        // On se connecte
        $connect = mysqli_connect('localhost', 'root', '', 'classes');
  
        // Petite requête pour recupérer les infos dont on a besoin selon l'utilisateurs connecté en fonction de son id du coup il me semble 
        $request = mysqli_query($connect,"SELECT email FROM `utilisateurs` WHERE id='$this->id'");
  
        // On le met dans un tableau assoc
        $assoc = mysqli_fetch_assoc($request);
  
        $this->email = $assoc['email'];
  
  
        // var_dump($assoc);
        return $assoc;
    }

    public function getFirstname()
    {
        
        // On défini l'id quelque part
        $this->id=$_SESSION['id']; 

        // On se connecte
        $connect = mysqli_connect('localhost', 'root', '', 'classes');
  
        // Petite requête pour recupérer les infos dont on a besoin selon l'utilisateurs connecté en fonction de son id du coup il me semble 
        $request = mysqli_query($connect,"SELECT firstname FROM `utilisateurs` WHERE id='$this->id'");
  
        // On le met dans un tableau assoc
        $assoc = mysqli_fetch_assoc($request);
  
        $this->firstname = $assoc['firstname'];
  
  
        // var_dump($assoc);
        return $assoc;

    }

    public function getLastname()
    {


        // On défini l'id quelque part
        $this->id=$_SESSION['id']; 

        // On se connecte
        $connect = mysqli_connect('localhost', 'root', '', 'classes');
  
        // Petite requête pour recupérer les infos dont on a besoin selon l'utilisateurs connecté en fonction de son id du coup il me semble 
        $request = mysqli_query($connect,"SELECT lastname FROM `utilisateurs` WHERE id='$this->id'");
  
        // On le met dans un tableau assoc
        $assoc = mysqli_fetch_assoc($request);
  
        $this->lastname = $assoc['lastname'];
  
  
        // var_dump($assoc);
        return $assoc;

    }

}

$user = new User;

// $user->register('ama','amarilystenorio@yahoo.fr','okpoint','amarilys','tenorio');

$user->connect('ama', 'okpoint');

$user->isConnected();

// $objet = $user->getAllInfos();
$objet = $user->getLastname();

var_dump($objet);