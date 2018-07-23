import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '../../../node_modules/@angular/router';
import { AuthService } from '../auth.service';
import { User } from '../user';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  data:any;
  email:string;
  first_name:string;
  isLoggedIn = false;
  last_name:string;
  msg:string;
  password:string;
  password2:string;
  user:User;
  title:string;
  state:number;
  ref:string;


  constructor(private authService: AuthService,private route: ActivatedRoute) {}

  forgotpassword(){
    this.title = "Forgot my password";
    this.state = 2;
  }

  getLogin(a=0){
    this.title = "Login";
    this.state = 0;
    if(a!=0){
      this.msg='';
    }
  }

  getRegister(){
    this.title = "Register";
    this.state = 1;
  }

  login(): any{
    if((this.email) || (this.password)){
      if(this.authService.login(this.email,this.password)
        .subscribe(user => this.authService.saveData(user,''))){
      }
    }
  }

  register(): any{
    if(this.first_name || this.password || this.email || this.password || this.email || this.password){
      this.data = {
        "fname":this.first_name,
        "lname":this.last_name,
        "email":this.email,
        'regpoint':'',
        "password1":this.password,
        "password2":this.password2
      }
      this.authService.registerUser(this.data)
      .subscribe(user => this.authService.saveData(user,''));
    }
  }

  resetPassword(){
    if(this.email != undefined ){
      this.authService.resetPassword(this.email)
        .subscribe(obj => this.showMsg(obj))
    }
  }

  savePassword(){
    if(this.password == this.password2){
      this.data = {
        "password":this.password,
        "password2":this.password2,
        "ref_token":this.ref
      }
      this.authService.savePassword(this.data)
          .subscribe(obj => this.authService.saveData(obj,''));
    }
    else{
      this.msg = "The passwords don't match";
    }
  }

  showMsg(obj){
    if(obj.success==true){
      this.state = 3;
      this.email = '';
    }
    this.msg = obj.msg;
  }

  ngOnInit() {
    this.title = "Login";
    this.state = 0;
    this.msg = '';
    this.ref = this.route.snapshot.paramMap.get('ref');
    if(this.ref!=undefined){
      this.state = 4;
      this.title = "Reset Password"
    }
    if (localStorage.getItem('isLoggedIn') == '1'){
      this.authService.isLoggedIn = true;
      if(localStorage.getItem('last_name')==undefined){
        this.user.last_name = localStorage.getItem('last_name');
      }else{
        this.user.last_name = '';
      }
      this.user.first_name = localStorage.getItem('first_name');
      return true;
    }
  }

}
