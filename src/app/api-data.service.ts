import { Injectable } from '@angular/core';
import { Account } from './account';
import { AccountType } from './account-type';
import { Product } from './product';
import { Category } from './category';
import { Connector } from './connector';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from '../environments/environment';
import { Location } from './location';
import { Observable } from 'rxjs';
import { Vendor } from './vendor';
import { Usergroup } from './usergroup';
import { Usergroupmap } from './usergroupmap';
import { User } from './user';
import { Game } from './game';
import { Town } from './town';
import { Participant } from './participant';
import { Bonusquestions } from './bonusquestions';
import { Team } from './team';
import { Player } from './player';
import { Userpost } from './userpost';
import { Vendorservice } from './vendorservice';
import { Vehicle } from './vehicle';


@Injectable()
export class ApiDataService {

  constructor(private httpClient: HttpClient) { }
  
  private categoriesUrl = environment.categoriesUrl;
  private currenciesUrl = environment.currenciesUrl;
  private accountsUrl = environment.accountsUrl;
  private messagesUrl = environment.messagesUrl;
  private productsUrl = environment.productsUrl;
  private profilesUrl = environment.profilesUrl;
  private sportsCompUrl = environment.sportscompUrl;
  private targetsUrl = environment.targetsUrl;
  private transactionsUrl = environment.transactionsUrl;
  private vehiclesUrl = environment.vehiclesUrl;
  private vendorsUrl = environment.vendorsUrl;
  private vendorproductsUrl = environment.vendorproductsUrl;
  private vendorservicesUrl = environment.vendorservicesUrl;

  loginData:any;
  userData:any={};
  userinfo:User;

  deleteVehicle(data): Observable<Vehicle[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      id:data.id,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.delete<Vehicle[]>(this.vehiclesUrl+'/'+data.id,httpOptions);
  }

  getAccounts(id='',nature:any=0): Observable<Account[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Account[]>(this.accountsUrl+'/'+id+'?account_nature='+nature,httpOptions);
  }

  getAccountTypes(id=''): Observable<AccountType[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<AccountType[]>(this.accountsUrl+'/types/'+id,httpOptions);
  }

  getAllGames(id=''): Observable<Game[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Game[]>(this.sportsCompUrl+'/games/'+id,httpOptions);
  }

  getBalances(id='',acc:any=0): Observable<any[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<any[]>(this.accountsUrl+'/balances/'+id+'?account_id='+acc,httpOptions);
  }

  getBonusQuestions(): Observable<Bonusquestions[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Bonusquestions[]>(this.sportsCompUrl+'/bonusquestions',httpOptions);
  }

  getCategories(category): Observable<Category[]> {
    return this.httpClient.get<Category[]>(this.categoriesUrl+'/'+category);
  }

  getCountries(id=0): Observable<any[]> {
    return this.httpClient.get<any[]>(this.vendorsUrl+'/getcountries/'+id);
  }

  getConnectors(category): Observable<Connector[]> {
    return this.httpClient.get<Connector[]>(this.productsUrl+'/connectors/'+category);
  }

  getCurrencies(id=0): Observable<any[]> {
    return this.httpClient.get<any[]>(this.currenciesUrl+'/'+id);
  }

  getGames(game=null): Observable<Game[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Game[]>(this.sportsCompUrl+'/'+game,httpOptions);
  }

  getParticipants(id): Observable<Participant[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Participant[]>(this.profilesUrl+'/participants/'+id,httpOptions);
  }

  getProducts(category=null,type=0): Observable<Product[]> {
    if(category!=null){
      if(type==0){
        return this.httpClient.get<Product[]>(this.productsUrl+'/subcat/'+category);
      }else if(type==1){
        return this.httpClient.get<Product[]>(this.vendorproductsUrl+'/shop/'+category);
      }
    }
    return this.httpClient.get<Product[]>(this.productsUrl);
  }

  getProductTypes(id, pt_id, pc):Observable<Town[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Town[]>(this.vendorsUrl+'/producttypes?pc='+pc,httpOptions);
  }

  getProfiles(): Observable<User> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<User>(this.profilesUrl,httpOptions);
  }

  getTeams(): Observable<Team[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Team[]>(this.sportsCompUrl+'/teams',httpOptions);
  }

  getPlayers(): Observable<Player[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Player[]>(this.sportsCompUrl+'/players',httpOptions);
  }

  getServices(): Observable<Vendorservice[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Vendorservice[]>(this.vendorservicesUrl,httpOptions);
  }

  getStripecustomer(): Observable<any> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<any[]>(this.profilesUrl+'/stripecustomer',httpOptions);
  }

  getTown(searchtown): Observable<Town[]> {
    return this.httpClient.get<Town[]>(this.vendorsUrl+'/getTown/'+searchtown);
  }

  getTransactions(): Observable<any[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<any[]>(this.transactionsUrl,httpOptions);
  }

  getTransSummary(account_to,from_date,to_date): Observable<any[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<any[]>(this.transactionsUrl+'/summary?account_to='+account_to+'&from_date='+from_date+'&to_date='+to_date,httpOptions);
  }

  getUsergroupmap(): Observable<Usergroupmap[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Usergroupmap[]>(this.profilesUrl+'/usergroup?apptoken=ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t',httpOptions);
  }

  getUserposts(group_id): Observable<Userpost[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Userpost[]>(this.messagesUrl+'?apptoken=ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t&group='+group_id,httpOptions);
  }

  getVehicles(vehicle=''): Observable<Vehicle[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Vehicle[]>(this.vehiclesUrl+'/'+vehicle,httpOptions);
  }

  getVendors(vendor=null,admin=null): Observable<Vendor[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    if(vendor==null){
      if(admin!=null){
        return this.httpClient.get<Vendor[]>(this.vendorsUrl+'?admin=true',httpOptions);
      }
      return this.httpClient.get<Vendor[]>(this.vendorsUrl,httpOptions);
    }
    else{
      if(admin!=null){
        return this.httpClient.get<Vendor[]>(this.vendorsUrl+'/'+vendor+'?admin=true',httpOptions);
      }
      return this.httpClient.get<Vendor[]>(this.vendorsUrl+'/'+vendor,httpOptions);
    }
  }
  getVendorLocations(location=''): Observable<Location[]> {
    return this.httpClient.get<Location[]>(this.vendorsUrl+'/locations/'+location);
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

  requestToJoin(group): Observable<Usergroup[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.loginData = {
      usergroup:group,
      state:0,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Usergroup[]>(this.profilesUrl+"/usergroup",this.loginData,httpOptions);
  }

  saveAccount(data): Observable<Account[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    Object.assign(this.userData,data,{apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'});
    return this.httpClient.post<Account[]>(this.accountsUrl,this.userData,httpOptions);
  }

  saveBalance(data): Observable<Account[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    Object.assign(this.userData,data,{apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'});
    return this.httpClient.post<Account[]>(this.accountsUrl+'/balances',this.userData,httpOptions);
  }

  saveBonusGuess(q_id,ug_id,user_guess): Observable<Bonusquestions[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    if(ug_id==undefined){
      ug_id='';
    }
    this.userData = {
      question_id:q_id,
      guess_id:ug_id,
      user_guess:user_guess,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Bonusquestions[]>(this.sportsCompUrl+'/bonusquestions/'+ug_id,this.userData,httpOptions);
  }

  saveCarwashLocation(id): Observable<Location>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      id:id,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Location>(this.profilesUrl+'/cwlocation',this.userData,httpOptions);
  }

  saveProduct(data): Observable<Product[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    Object.assign(this.userData,data,{apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'});
    return this.httpClient.post<Product[]>(this.vendorproductsUrl,this.userData,httpOptions);
  }

  saveScore(game_id,score1,score2,secret): Observable<Game[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      game_id:game_id,
      score1:score1,
      score2:score2,
      secret:secret,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Game[]>(this.sportsCompUrl+'/matchday/'+game_id,this.userData,httpOptions);
  }

  saveService(data): Observable<Vendorservice[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      car_id:data.car_id,
      service_date:data.service_date,
      mobile_no:data.mobile_no,
      vendor_id:data.vendor_id,
      product_id:data.product_id,
      service_price:data.service_price,
      payment_method:data.payment_method,
      state:0,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Vendorservice[]>(this.vendorservicesUrl,this.userData,httpOptions);
  }

  saveShop(data): Observable<Vendor[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      ddc_vendor_id:data.vendor_id,
      title:data.title,
      shop_type:data.shop_type,
      introduction:data.introduction,
      description:data.description,
      address1:data.address1,
      address2:data.address2,
      city:data.city,
      county:data.county,
      country:data.country,
      post_code:data.post_code,
      full_name:data.full_name,
      email:data.email,
      state:0,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Vendor[]>(this.vendorsUrl,this.userData,httpOptions);
  }

  saveStripecustomer(data): Observable <any>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      token:data.token.id,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<any[]>(this.profilesUrl+'/stripecustomer',this.userData,httpOptions);
  }

  saveTarget(data): Observable<any[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    Object.assign(this.userData,data,{apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'});
    return this.httpClient.post<any[]>(this.targetsUrl,this.userData,httpOptions);
  }

  saveTransaction(data): Observable<any[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    Object.assign(this.userData,data,{apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'});
    return this.httpClient.post<any[]>(this.transactionsUrl,this.userData,httpOptions);
  }

  saveUserSubscribe(user): Observable<User[]> {
    this.loginData = {
      email:user.email,
      first_name:user.first_name,
      subpoint:user.sub_point,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<User[]>(this.profilesUrl+"/saveaddress",this.loginData);
  }

  saveMessage(data): Observable<Bonusquestions[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      user_id_to:data.user_id_to,
      group_id:data.group_id,
      message:data.message,
      subject:data.subject,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Bonusquestions[]>(this.messagesUrl,this.userData,httpOptions);
  }

  saveUserguess(guess_id,game_id,ugTeam1,ugTeam2,ugScore1,ugScore2): Observable<Game[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    if(guess_id==undefined){
      guess_id='';
    }
    this.userData = {
      guess_id:guess_id,
      game_id:game_id,
      teamA:ugTeam1,
      teamB:ugTeam2,
      scoreA:ugScore1,
      scoreB:ugScore2,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Game[]>(this.sportsCompUrl+'/userguesses/'+guess_id,this.userData,httpOptions);
  }

  saveUserImage(l,id,sf): Observable<User> {
    const fd = new FormData();
    fd.append('upload_photo',sf,sf.name);
    fd.append('apptoken','ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t');
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.post<User>(this.profilesUrl+'/images',fd,httpOptions);
  }

  saveUser(first_name,last_name,profession,company,aboutme){
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      first_name:first_name,
      last_name:last_name,
      profession:profession,
      company:company,
      aboutme:aboutme,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<User>(this.profilesUrl,this.userData,httpOptions);
  }

  saveVehicle(data): Observable<Vehicle[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      car_reg:data.car_reg,
      make:data.make,
      model:data.model,
      year:data.year,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Vehicle[]>(this.vehiclesUrl,this.userData,httpOptions);
  }

}
