<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'name', 'email', 'image', 'about', 'type', 'github', 'city', 'is_finished', 'phone', 'birthday', 'role', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'password_reset_token', 'role', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function worker()
    {
        if($this->isWorker()){
            return $this->hasOne(Worker::class);
        }else{
            return null;
        }
    }

    public function isAdmin(){
        return $this->role>=2;
    }

    public function isWorker(){
        return $this->role==1;
    }

    public function setWorker(Worker $worker){ //Ужаснейшая реализация задумки, но с логической стороны, что каждый пользователь может быть только определённым работником, смотрится логично.. Вроде..
        if($worker->user == null){
            $worker->user_id=$this->id;
            $worker->save();
            $this->role=1;
            $this->save();
        }
    }

}
