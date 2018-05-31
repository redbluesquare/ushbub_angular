import { Injectable } from '@angular/core';
import { Router }     from '@angular/router';
import {User } from './user';
import { HttpClient, HttpHeaders, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import { environment } from '../environments/environment';

@Injectable()
export class AuthService {
  constructor(private httpClient: HttpClient, private router: Router){
    
  };
  private Url = environment.Url;  // URL to web api
  isLoggedIn = false;
  loginData:any;
  getData:any;
  user: User[];
  
  
  // store the URL so we can redirect after logging in
  redirectUrl: string;

  login(u:string,pw:string){
    this.loginData = {
      email:u,
      sk:pw,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<User[]>(this.Url,this.loginData);
  }
  saveData(user,ref){
    if(user!=undefined){
      this.user = user;
      if(user.success){
        this.user = user;
        this.isLoggedIn = true;
        localStorage.removeItem('usertoken');
        localStorage.setItem('usertoken', user.usertoken);
        localStorage.setItem('first_name', user.first_name);
        localStorage.setItem('last_name', user.last_name);
        localStorage.setItem('isLoggedIn', '1');
        if(ref!='wc'){
          this.redirectUrl = '/';
        }else{
          this.redirectUrl = 'world-cup';
        }
        this.router.navigate([this.redirectUrl]);
        return true;

      }
      else{
        if(ref=='wc'){
          alert(user.msg);
        }
      }
    }
    return false;
  }

  logout(): void {
    this.isLoggedIn = false;
    localStorage.removeItem('usertoken');
    localStorage.removeItem('first_name');
    localStorage.removeItem('last_name');
    localStorage.removeItem('isLoggedIn');
    this.router.navigate(['/']);
  }


}
