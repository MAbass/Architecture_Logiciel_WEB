<?php
// src/Controller/LuckyController.php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Collections\Collection;
class UserSoapService extends AbstractController
{

	private $em;
    private $verifierJetonService;
    private $encoder;
  
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, VerifierJetonService $verifierJetonService){
        $this->em = $em;
        $this->verifierJetonService = $verifierJetonService;
        $this->encoder = $encoder;

    }

    /**
    * @param string $emailUser 
    * @return array
    */
    
    public function list(string $emailUser){

        if(!$this->verifierJetonService->verifierJeton($emailUser, "READ")){
            return "NOK"; //NOK = Vous n'avez pas acces a ce service
        }
      
        $users = $this->em->getRepository(User::class)->findAll();
       
        $usersArray = array();
        foreach ($users as  $user) {
            $userArray = array();
            $userArray[] = $user->getId();
            $userArray[] = $user->getPrenom();
            $userArray[] = $user->getNom();
            $userArray[] = $user->getEmail();
            $userArray[] = $user->getRoles();
            $usersArray[] = $userArray;
        }

        return $usersArray;
    }

    /**
    * @param string $prenom
    * @param string $nom
    * @param string $email
    * @param string $emailUser 
    * @return string 
    */
    public function create($prenom, $nom, $email, $emailUser){


        if(!$this->verifierJetonService->verifierJeton($emailUser, "CREATE")){
            return "NOK";
        }        
        $user = new User;
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setToken(hash("sha256", "test"));
        $user->setPassword($this->encoder->encodePassword(
            $user,
            "test"
        ));
            $user->setStatus(0);
            
            $this->em->persist($user);
            $this->em->flush();
    
            return "OK";

        
       
    }
     /**
    * @param string $email
    * @param int $status
    * @param string $emailUser
    * @return string 
    */
    public function edit($email, $status, $emailUser){

        if(!$this->verifierJetonService->verifierJeton($emailUser, "UPD")){
            return "NOK";
        }
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if($status == 1){
            $user->setStatus(1);
            $user->setRoles(["ROLE_EDITEUR"]);
        }else if($status == 2){
            $user->setStatus(2);
            $user->setRoles(["ROLE_ADMIN"]);
        }else{
            $user->setStatus(0);
            $user->setRoles(["ROLE_USER"]);
        }
        $this->em->flush();


         return "OK";
           
        
     }

    /**
    * @param string $email
    * @param string $emailUser 
    * @return string 
    */
   
    public function delete($email, $emailUser){

        if(!$this->verifierJetonService->verifierJeton($emailUser, "DEL")){
            return "NOK";
        }
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->em->remove($user);
        $this->em->flush();
           
        
        return "OK";
    }

    /**
    * @param string $email
    * @param string $passe
    * @return string
    */

    public function authentificate($email, $passe){

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        
        if ($user == null){
            return "NOK";
        }

        $passe = hash("sha256", $passe);


        $users = $this->em->getRepository(User::class)->findAll();


            if($user->getToken() == $passe){
                return "OK";
            }

        return "NOK";

    }

}