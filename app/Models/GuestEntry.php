<?php
namespace  App\Models;
use Illuminate\Database\Eloquent\Model;

class GuestEntry extends Model
{
  public $timestamps = false;
  protected $table = "user_login";
  //protected $primaryKey = "ID_USER";
 // protected $fillable = ["ID_ROLL","USERNAME","PASSWORD"];
}

class GuestEntryRoll extends Model
{
  public $timestamps = false;
  protected $table = "user_roll";
  //protected $primaryKey = "ID";
}
class GuestEntryAudit extends Model
{
  public $timestamps = false;
  protected $table = "user_auditoria";
 // protected $primaryKey = "ID";
}

class GuestEntryPermiso extends Model
{
  public $timestamps = false;
  protected $table = "user_permiso";
 // protected $primaryKey = "ID";
}
class GuestEntryMenu extends Model
{
  public $timestamps = false;
  protected $table = "user_menu";
 // protected $primaryKey = "ID";
}

class GuestEntryMenu_usuario extends Model
{
  public $timestamps = false;
  protected $table = "menu_usuario";
  //protected $primaryKey = "ID";
}

class GuestEntryAcceso extends Model
{
  public $timestamps = false;
  protected $table = "user_acceso";
 /// protected $primaryKey = "ID";
}

class GT_user extends Model
{
  public $timestamps = false;
  protected $table = "gt_user";
 // protected $primaryKey = "ID";
}
