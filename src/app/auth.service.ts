import { Injectable } from '@angular/core';
import { Router }     from '@angular/router';
import {User } from './user';
import { HttpClient, HttpHeaders, HttpRequest } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../environments/environment';

@Injectable()
export class AuthService {
  constructor(private httpClient: HttpClient, private router: Router){
    
  };
  private profilesUrl = environment.profilesUrl;  // URL to web api
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
    return this.httpClient.post<User[]>(this.profilesUrl+'/login',this.loginData);
  }

  logout(): void {
    this.isLoggedIn = false;
    localStorage.removeItem('usertoken');
    localStorage.removeItem('first_name');
    localStorage.removeItem('last_name');
    localStorage.removeItem('isLoggedIn');
    this.router.navigate(['/']);
  }

  registerUser(user): Observable<User[]> {
    this.loginData = {
      email:user.email,
      tokenId:user.password1,
      fname:user.fname,
      regpoint:user.regpoint,
      lname:user.lname,
      ref:0,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<User[]>(this.profilesUrl+"/authenticate",this.loginData);
  }

  resetPassword(email:string): Observable<any> {
    this.loginData = {
      email:email,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<any>(this.profilesUrl+"/reset",this.loginData);
  }

  savePassword(data:any): Observable<any> {
    this.getData = {
      password:data.password,
      password1:data.password1,
      reset_token:data.ref_token,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<any>(this.profilesUrl+"/reset",this.getData);
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
        alert(user.msg);
      }
    }
    return false;
  }

}
