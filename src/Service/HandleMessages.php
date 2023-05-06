<?php
namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

define('MESSAGE_UPDATE', ' se ha actualizado correctamente.') ;
define('MESSAGE_ERROR',  '  se ha actualizado correctamente.' );
define('MESSAGE_REMOVE', ' se ha eliminado correctamente.');
define('MESSAGE_NEW_PRODUCT', ' se ha añadido correctamente.');
define('MESSAGE_UPDATE_IMAGE', ' Se ha modificado la imagen para ');

class HandleMessages extends AbstractController
{
    public function messageUpdate( $data)
    {       
        return sweetalert()->addInfo(  $data . MESSAGE_UPDATE);             
    }

    public function messageUpdateImage( $data)
    {
        return sweetalert()->addInfo( MESSAGE_UPDATE_IMAGE . $data );             
    }
    
    public function messageError( $data)
    {
       return sweetalert()->addError( $data . MESSAGE_ERROR);
    }
    
    public function messageRemove( $data)
    {
       return sweetalert()->addWarning( $data . MESSAGE_REMOVE);
    } 
    
    public function messageNew( $data)
    {
       return sweetalert()->addSuccess($data . MESSAGE_NEW_PRODUCT);
    }         
    
   
    
}
