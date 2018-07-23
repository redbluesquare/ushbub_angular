import { Injectable } from '@angular/core';
import { Product } from './product';
import { Category } from './category';
import { Connector } from './connector';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from '../environments/environment';
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


@Injectable()
export class ApiDataService {

  constructor(private httpClient: HttpClient) { }
  private productsUrl = environment.productsUrl;  // URL to web api
  private categoriesUrl = environment.categoriesUrl;  // URL to web api
  private vendorsUrl = environment.vendorsUrl;  // URL to web api
  private messagesUrl = environment.messagesUrl;
  private profilesUrl = environment.profilesUrl;  // URL to web api
  private sportsCompUrl = environment.sportscompUrl;  // URL to web api
  loginData:any;
  userData:any;
  userinfo:User;

  getAllGames(id=''): Observable<Game[]> {
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    return this.httpClient.get<Game[]>(this.sportsCompUrl+'/games/'+id,httpOptions);
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

  getConnectors(category): Observable<Connector[]> {
    return this.httpClient.get<Connector[]>(this.productsUrl+'/connectors/'+category);
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

  getProducts(category=null): Observable<Product[]> {
    if(category!=null){
      return this.httpClient.get<Product[]>(this.productsUrl+'/subcat/'+category);
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

  getTown(searchtown): Observable<Town[]> {
    return this.httpClient.get<Town[]>(this.vendorsUrl+'/getTown/'+searchtown);
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

  getVendors(vendor=null): Observable<Vendor[]> {
    return this.httpClient.get<Vendor[]>(this.vendorsUrl);
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

  saveShop(data): Observable<Vendor[]>{
    const httpOptions = {
      headers: new HttpHeaders({ 'bearer': localStorage.getItem('usertoken') })
    };
    this.userData = {
      title:data.title,
      shop_type:data.shop_type,
      description:data.description,
      first_name:data.first_name,
      last_name:data.last_name,
      state:0,
      apptoken:'ksdbvskob0vwfb8BKBKS8VSFLFFPANVVOFd1nspvpwru8r8rB72r8r928t'};
    return this.httpClient.post<Vendor[]>(this.vendorsUrl,this.userData,httpOptions);
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

}
