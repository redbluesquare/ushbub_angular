import { Injectable } from '@angular/core';
import { Router }     from '@angular/router';
import {User } from './user';
import { HttpClient, HttpHeaders, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';

@Injectable()
export class AuthService {
  constructor(private httpClient: HttpClient, private router: Router){};
  private Url = 'http://localhost:8888/ushbub/api/profiles/login';  // URL to web api
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
  saveData(user){
    if(user!=undefined){
      this.user = user;
      this.isLoggedIn = true;
      localStorage.removeItem('usertoken');
      localStorage.setItem('usertoken', user.usertoken);
      localStorage.setItem('fullname', user.fullname);
      localStorage.setItem('isLoggedIn', '1');
      this.redirectUrl = 'profile';
      this.router.navigate([this.redirectUrl]);
    }
    return false;
  }

  logout(): void {
    this.isLoggedIn = false;
    localStorage.removeItem('usertoken');
    localStorage.removeItem('fullname');
    localStorage.removeItem('isLoggedIn');
    this.router.navigate(['/']);
  }


}
