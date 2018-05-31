import { Component, OnInit } from '@angular/core';
import { User } from '../user';
import { AuthService } from '../auth.service';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  isLoggedIn = false;
  email = null;
  password = null;
  user : User;


  constructor(private authService: AuthService) {}
   
  
  login(): any{
    if((this.email) || (this.password)){
      if(this.authService.login(this.email,this.password)
        .subscribe(user => this.authService.saveData(user,''))){
      }
    }

  }

  ngOnInit() {
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
