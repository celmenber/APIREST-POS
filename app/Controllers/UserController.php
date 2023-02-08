<?php

namespace  App\Controllers;

use App\Models\GuestEntry;
use App\Models\GuestEntryRoll;
use App\Models\GuestEntryAudit;
use App\Models\guestEntrypermiso;
use App\Models\guestEntrymenu;
use App\Models\guestEntrymenu_Usuario;
use App\Models\GuestEntryAcceso;
use App\Models\GT_user;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Illuminate\Database\Capsule\Manager as DB;
use App\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class UserController
{
    protected $customResponse;
    protected $guestEntry;
    protected $guestEntryroll;
    protected $guestEntryaudit;
    protected $guestEntrypermiso;
    protected $guestEntrymenu;
    protected $guestEntrymenu_Usuario;
    protected $guestEntryacceso;
    protected $gt_userentry;
    protected $validator;

    public function __construct()
    {
    $this->customResponse = new CustomResponse();
    $this->guestEntry = new GuestEntry();
    $this->guestEntryroll = new GuestEntryRoll();
    $this->guestEntryaudit = new GuestEntryAudit();
    $this->guestEntrypermiso = new guestEntrypermiso();
    $this->guestEntrymenu = new guestEntrymenu();
    $this->guestEntrymenu_Usuario = new guestEntrymenu_Usuario();
    $this->guestEntryacceso = new GuestEntryAcceso();
    $this->gt_userentry = new GT_user();
    $this->validator = new Validator();
    }

   public function hashPassword($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }

/* DESDE AQUI SE PROCESO EL CRUE DE LA TABLA USER LOGIN */
    public function viewUser(Response $response)
    {
        $guestEntries = guestEntry::select("ID_USER","ID_ROLL","USERNAME","ESTADO","AVATAR","FECHA")->get();
        return $this->customResponse->is200Response($response,$guestEntries); 
    }

    public function viewUserId(Response $response,$id)
    {
       $guestEntries = guestEntry::select("ID_USER","ID_ROLL","USERNAME","ESTADO","AVATAR","FECHA")
                    ->where("ID_USER","=",$id)
                    ->get();
        return $this->customResponse->is200Response($response,$guestEntries);
    }

    public function deleteUser(Response $response,$id)
    {
        $this->guestEntry->where(["ID_USER"=>$id])->delete();
        $responseMessage = "El usuario fue eliminado exitosamente";
        return $this->customResponse->is200Response($response,$responseMessage);
    }


    public function viewUserRoll(Response $response)
    {
        $guestEntriesroll = $this->guestEntryroll->get();
        return $this->customResponse->is200Response($response,$guestEntriesroll);
    }

      public function viewUserRollid(Response $response,$id)
    {
        $guestEntriesroll = $this->guestEntryroll->where(["ID"=>$id])->get();
        return $this->customResponse->is200Response($response,$guestEntriesroll);
    }


    public function createUsers(Request $request,Response $response)
    {
       $data = json_decode($request->getBody(),true);
       $this->validator->validate($request,[
           "rol"=>v::notEmpty(),
           "users"=>v::notEmpty(),
           "password"=>v::notEmpty()
         ]); 

        if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       } 
        try{
        $guestEntry = new guestEntry;
        $guestEntry->ID_ROLL     =   $data['rol'];
        $guestEntry->USERNAME    =   $data['users'];
        $guestEntry->ESTADO      =   1;
        $guestEntry->PASSWORD    =   $this->hashPassword($data['password']);
        $guestEntry->save();
        $responseMessage = array('msg' => "usuario Guardado correctamente",'id' => $guestEntry->id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
   }

    public function editUsers(Request $request,Response $response,$id)
    {
         $data = json_decode($request->getBody(),true);
         $this->validator->validate($request,[
           "rol"=>v::notEmpty(),
           "users"=>v::notEmpty(),
           "estado"=>v::notEmpty()
         ]);
         
        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }
        try{
                $guestEntry = guestEntry::find($id);
                $guestEntry->ID_ROLL     =   $data['rol'];
                $guestEntry->USERNAME    =   $data['users'];
                $guestEntry->ESTADO      =   1;
                $guestEntry->PASSWORD    =   $this->hashPassword($data['password']);
                $guestEntry->save();
                $responseMessage = array('msg' => "usuario Editado correctamente",'id' => $id);
                return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
                $responseMessage = array("err" => $err->getMessage());
                return $this->customResponse->is400Response($response,$responseMessage);
            }
    }
/* FIN DEL CRUE LOGIUN USER */
/* DESDE AQUI SE PROCESA EL CRUE DE LA TABLA AUDITORIA */
      public function viewUserauditoria(Response $response)
    {
        $guestEntriesaudit = $this->guestEntryaudit->get();
        return $this->customResponse->is200Response($response,$guestEntriesaudit);
    }

      public function viewUserauditoriaid(Response $response,$id)
    {
        $guestEntriesaudit = $this->guestEntryaudit->where(["ID"=>$id])->get();
        return $this->customResponse->is200Response($response,$guestEntriesaudit);
    }

    public function createuserauditoria(Request $request,Response $response)
    {
         $data = json_decode($request->getBody(),true);
        $this->validator->validate($request,[
           "IDUSER"=>v::notEmpty(),
           "CODREGISTRO"=>v::notEmpty(),
           "NOMTABLA"=>v::notEmpty(),
           "ACCIONREGISTRO"=>v::notEmpty() 
       ]); 

       if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }

       try{
        $guestentryaudit = new guestEntryaudit;
        $guestentryaudit->ID_USER         =   $data['IDUSER'];
        $guestentryaudit->COD_REGISTRO    =   $data['CODREGISTRO'];
        $guestentryaudit->NOM_TABLA       =   $data['NOMTABLA'];
        $guestentryaudit->ACCION_REGISTRO =   $data['ACCIONREGISTRO'];
        $guestentryaudit->save();
        $responseMessage = array('msg' => "auditoria Guardada correctamente",'id' => $guestentryaudit->id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }
   public function editUserAuditoria(Request $request,Response $response,$id)
    {
       $data = json_decode($request->getBody(),true);
        $this->validator->validate($request,[
           "IDUSER"=>v::notEmpty(),
           "CODREGISTRO"=>v::notEmpty(),
           "NOMTABLA"=>v::notEmpty(),
           "ACCIONREGISTRO"=>v::notEmpty() 
       ]); 

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }

        try{
        $guestentryaudit = guestEntryaudit::find($id);
        $guestentryaudit->ID_USER         =   $data['IDUSER'];
        $guestentryaudit->COD_REGISTRO    =   $data['CODREGISTRO'];
        $guestentryaudit->NOM_TABLA       =   $data['NOMTABLA'];
        $guestentryaudit->ACCION_REGISTRO =   $data['ACCIONREGISTRO'];
        $guestentryaudit->save();
        $responseMessage = array('msg' => "auditoria editada correctamente",'id' => $id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }
    /* FIN DEL CRUE AUDITORIA */

    /* DESDE AQUI SE PROCESA EL CRUE DE LA TABLA PERMISO */
    public function viewUserPermiso(Response $response)
    {
        $guestEntriespermiso = $this->guestEntrypermiso->get();
        return $this->customResponse->is200Response($response,$guestEntriespermiso);
    }

      public function viewUserPermisoid(Response $response,$id)
    {
        $guestEntriespermiso = $this->guestEntrypermiso->where(["ID"=>$id])->get();
        return $this->customResponse->is200Response($response,$guestEntriespermiso);
    }

    public function createuserPermiso(Request $request,Response $response)
    {

    $data = json_decode($request->getBody(),true);
        $this->validator->validate($request,[
           "IDUSERMENU"=>v::notEmpty(),
           "NOMBRE"=>v::notEmpty(),
           "ESTADO"=>v::notEmpty()
             ]); 
       	 

       if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }
 try{
       
        $guestEntrypermiso = new guestEntrypermiso;
        $guestEntrypermiso->ID_USER_MENU         =   $data['IDUSERMENU'];
        $guestEntrypermiso->NOMBRE               =   $data['NOMBRE'];
        $guestEntrypermiso->ESTADO               =   $data['ESTADO'];
        $guestEntrypermiso->save();
        $responseMessage = array('msg' => "permiso guardado correctamente",'id' => $guestEntrypermiso->id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }

   public function editUserPermiso(Request $request,Response $response,$id)
    {
        $data = json_decode($request->getBody(),true);
         $this->validator->validate($request,[
              "IDUSERMENU"=>v::notEmpty(),
              "NOMBRE"=>v::notEmpty(),
              "ESTADO"=>v::notEmpty()
        ]); 
        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }

     try{
        $guestEntrypermiso = guestEntrypermiso::find($id);
        $guestEntrypermiso->ID_USER_MENU         =   $data['IDUSERMENU'];
        $guestEntrypermiso->NOMBRE               =   $data['NOMBRE'];
        $guestEntrypermiso->ESTADO               =   $data['ESTADO'];
        $guestEntrypermiso->save();
        $responseMessage = array('msg' => "permiso editado correctamente",'id' => $id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }

    /* FIN DEL CRUE PERMISO */
    /* DESDE AQUI SE PROCESA EL CRUE DE LA TABLA MENU */
    public function viewUserMenu(Response $response)
    {
        $guestEntriesmenu = $this->guestEntrymenu->get();
        return $this->customResponse->is200Response($response,$guestEntriesmenu);
    }

    public function viewUserMenuid(Response $response,$id)
    {
        $guestEntriesmenu = $this->guestEntrymenu->where(["ID"=>$id])->get();
        return $this->customResponse->is200Response($response,$guestEntriesmenu);
    }

   public function createuserMenu(Request $request,Response $response)
    {
        $data = json_decode($request->getBody(),true);
        $this->validator->validate($request,[
            "NOMBRE"=>v::notEmpty(),
            "RUTA"=>v::notEmpty() 
       ]); 

       if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }

       try{
        $guestEntrymenu =  new guestEntrymenu; 
        $guestEntrymenu->NOMBRE         =   $data['NOMBRE'];
        $guestEntrymenu->RUTA           =   $data['RUTA'];
        $guestEntrymenu->save();
        $responseMessage = array('msg' => "user menu guardado correctamente",'id' => $guestEntrymenu->id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }

    public function editUserMenu(Request $request,Response $response,$id)
    {
         $data = json_decode($request->getBody(),true);
         $this->validator->validate($request,[
            "NOMBRE"=>v::notEmpty(),
            "RUTA"=>v::notEmpty() 
          ]); 

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }

        try{
        $guestEntrymenu = guestEntrymenu::find($id);
        $guestEntrymenu->NOMBRE         =   $data['NOMBRE'];
        $guestEntrymenu->RUTA           =   $data['RUTA'];
        $guestEntrymenu->save();
        $responseMessage = array('msg' => "user menu editado correctamente",'id' => $id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }
     /* FIN DEL CRUE MENU */
     
    /* DESDE AQUI SE PROCESA EL CRUE DE LA TABLA ACCESOS */
 public function viewUserAcceso(Response $response)
    {
        $guestEntriesacceso = $this->GuestEntryacceso->get();
        return $this->customResponse->is200Response($response,$guestEntriesacceso);
    }

    public function viewUserAccesoid(Response $response,$id)
    {
        $guestEntriesacceso = $this->GuestEntryacceso->where(["ID"=>$id])->get();
        return $this->customResponse->is200Response($response,$guestEntriesacceso);
    }

   public function createuserAcceso(Request $request,Response $response)
    {

         $data = json_decode($request->getBody(),true);
         $this->validator->validate($request,[
            "IDUSER"=>v::notEmpty(),
            "LAT"=>v::notEmpty(),
            "LON"=>v::notEmpty(),
            "CIUDAD"=>v::notEmpty(),
            "PAIS"=>v::notEmpty(),
            "IP"=>v::notEmpty()
          ]); 

       if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }

    try{
        $Guestentryacceso = new GuestEntryacceso;
        $Guestentryacceso->ID_USER         =   $data['IDUSER'];
        $Guestentryacceso->LAT             =   $data['LAT'];
        $Guestentryacceso->LON             =   $data['LON'];
        $Guestentryacceso->CIUDAD          =   $data['CIUDAD'];
        $Guestentryacceso->PAIS            =   $data['PAIS'];
        $Guestentryacceso->IP              =   $data['IP'];
        $Guestentryacceso->save();
        $responseMessage = array('msg' => "auditoria Guardada correctamente",'id' => $Guestentryacceso->id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }
    /* FIN DEL CRUE ACCESOS */
    /* DESDE AQUI SE PROCESA EL CRUE DE LA TABLA GT_USER LA CUAL ES AUXILIAR */
    public function viewUserGt(Response $response)
    {
        $guestgt_userentry = $this->gt_userentry->get();
        return $this->customResponse->is200Response($response,$guestEntriesroll);
    }

    public function viewUserGtid(Response $response,$id)
    {
        $guestgt_userentry = $this->gt_userentry->where(["ID"=>$id])->get();
        return $this->customResponse->is200Response($response,$guestgt_userentry);
    }

    public function createuserGt(Request $request,Response $response)
    {
        $data = json_decode($request->getBody(),true);
        $this->validator->validate($request,[
            "IDUSER"=>v::notEmpty(),
            "IDTIPOUSER"=>v::notEmpty() 
       ]); 

       if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }

       try{
        $gtuserentry =  new gt_userentry; 
        $gtuserentry->ID_USER         =   $data['IDUSER'];
        $gtuserentry->ID_TIPO_USER    =   $data['IDTIPOUSER'];
        $gtuserentry->save();
        $responseMessage = array('msg' => "gt USUARIO guardado correctamente",'id' => $gtuserentry->id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }

    public function editUserGt(Request $request,Response $response,$id)
    {
         $data = json_decode($request->getBody(),true);
         $this->validator->validate($request,[
            "IDUSER"=>v::notEmpty(),
            "IDTIPOUSER"=>v::notEmpty() 
          ]); 

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }

        try{
        $gtuserentry = gt_userentry::find($id);
        $gtuserentry->ID_USER         =   $data['IDUSER'];
        $gtuserentry->ID_TIPO_USER    =   $data['IDTIPOUSER'];
        $gtuserentry->save();
        $responseMessage = array('msg' => "gt USUARIO editado correctamente",'id' => $id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }



/* DESDE AQUI SE PROCESA EL CRUE DE LA TABLA MENU USUARIO LA CUAL ES AUXILIAR */
 public function viewUserMenuUsuario(Response $response)
    {
        $guestntrymenusuario = $this->guestEntrymenu_Usuario->get();
        return $this->customResponse->is200Response($response,$guestntrymenusuario);
    }

    public function viewUserMenuUsuarioid(Response $response,$id)
    {
        $guestntrymenusuario = $this->guestEntrymenu_Usuario->where(["ID"=>$id])->get();
        return $this->customResponse->is200Response($response,$guestntrymenusuario);
    }

    public function createuserMenuUsuario(Request $request,Response $response)
    {
        $data = json_decode($request->getBody(),true);
        $this->validator->validate($request,[
            "IDUSER"=>v::notEmpty(),
            "IDMENU"=>v::notEmpty() 
       ]); 

       if($this->validator->failed())
       {
           $responseMessage = $this->validator->errors;
           return $this->customResponse->is400Response($response,$responseMessage);
       }

       try{
        $guestEntrymenu_Usuario =  new guestEntrymenu_Usuario; 
        $guestEntrymenu_Usuario->ID_USER        =   $data['IDUSER'];
        $guestEntrymenu_Usuario->ID_MENU        =   $data['IDMENU'];
        $guestEntrymenu_Usuario->save();
        $responseMessage = array('msg' => "MENU USUARIO guardado correctamente",'id' => $guestEntrymenu_Usuario->id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }

    public function editUserMenuUsuario(Request $request,Response $response,$id)
    {
         $data = json_decode($request->getBody(),true);
         $this->validator->validate($request,[
            "IDUSER"=>v::notEmpty(),
            "IDMENU"=>v::notEmpty() 
          ]); 

        if($this->validator->failed())
        {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response,$responseMessage);
        }

        try{
        $guestEntrymenu_Usuario = guestEntrymenu_Usuario::find($id);
        $guestEntrymenu_Usuario->ID_USER    =   $data['IDUSER'];
        $guestEntrymenu_Usuario->ID_MENU    =   $data['IDMENU'];
        $guestEntrymenu_Usuario->save();
        $responseMessage = array('msg' => "MENU USUARIO editado correctamente",'id' => $id);
        return $this->customResponse->is200Response($response,$responseMessage);
        }catch(Exception $err){
        $responseMessage = array("err" => $err->getMessage());
        return $this->customResponse->is400Response($response,$responseMessage);
       }
    }

}