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
  username = null;
  password = null;
  user : User[];


  constructor(private authService: AuthService) {}
   
  
  login(): any{
    if((this.username) || (this.password)){
      if(this.authService.login(this.username,this.password).subscribe(user => this.authService.saveData(user))){
      }
    }

  }

  ngOnInit() {
    console.log(this.user);
  }

}
