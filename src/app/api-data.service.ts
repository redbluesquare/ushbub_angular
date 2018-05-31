import { Injectable } from '@angular/core';
import { Product } from './product';
import { Category } from './category';
import { Connector } from './connector';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from '../environments/environment';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import { Vendor } from './vendor';
import { Usergroup } from './usergroup';
import { Usergroupmap } from './usergroupmap';
import { User } from './user';
import { Town } from './town';
import { AuthService } from './auth.service';


@Injectable()
export class ApiDataService {

  constructor(private httpClient: HttpClient) { }
  private productsUrl = environment.productsUrl;  // URL to web api
  private categoriesUrl = environment.categoriesUrl;  // URL to web api
  private vendorsUrl = environment.vendorsUrl;  // URL to web api
  private profilesUrl = environment.profilesUrl;  // URL to web api
  loginData:any;
  userinfo:User;

  getProducts(category=null): Observable<Product[]> {
    if(category!=null){
      return this.httpClient.get<Product[]>(this.productsUrl+'/subcat/'+category);
    }
    return this.httpClient.get<Product[]>(this.productsUrl);
  }

  getCategories(category): Observable<Category[]> {
    return this.httpClient.get<Category[]>(this.categoriesUrl+'/'+category);
  }

  getConnectors(category): Observable<Connector[]> {
    return this.httpClient.get<Connector[]>(this.productsUrl+'/connectors/'+category);
  }

  getVendors(vendor=null): Observable<Vendor[]> {
    return this.httpClient.get<Vendor[]>(this.vendorsUrl);
  }

  getTown(searchtown): Observable<Town[]> {
    return this.httpClient.get<Town[]>(this.vendorsUrl+'/getTown/'+searchtown);
  }
  getUsergroupmap(group,token): Observable<Usergroupmap[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': token })
    };
    return this.httpClient.get<Usergroupmap[]>(this.profilesUrl+'/usergroup/'+group+'?apptoken=ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t',httpOptions);
  }

  registerUser(user): Observable<User[]> {
    this.loginData = {
      email:user.email,
      tokenId:user.password1,
      fname:user.fname,
      regpoint:user.regpoint,
      lname:'',
      ref:0,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<User[]>(this.profilesUrl+"/authenticate",this.loginData);
  }

  requestToJoin(group,usertoken): Observable<Usergroup[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': usertoken })
    };
    this.loginData = {
      usergroup:group,
      state:0,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Usergroup[]>(this.profilesUrl+"/usergroup",this.loginData,httpOptions);
  }

}
