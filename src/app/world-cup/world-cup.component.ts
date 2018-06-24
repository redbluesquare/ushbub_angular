import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { trigger, state, style, animate, transition } from '@angular/animations';
import { ApiDataService } from '../api-data.service';
import { User } from '../user';
import { Participant } from '../participant';
import { Usergroup } from '../usergroup';
import { Usergroupmap } from '../usergroupmap';
import { Game } from '../game';
import { Bonusquestions } from '../bonusquestions';
import { Team } from '../team';
import { Player } from '../player';
import { Userpost } from '../userpost';
//import { ActivatedRoute, Routes } from '@angular/router';

@Component({
  selector: 'app-world-cup',
  templateUrl: './world-cup.component.html',
  styleUrls: ['./world-cup.component.css'],
  animations: [ trigger('slideInOut', [
    state('in', style({
      overflow: 'hidden',
      height: '*'
    })),
    state('out', style({
      opacity: '0',
      overflow: 'hidden',
      height: '0px'
    })),
    transition('in => out', animate('400ms ease-out')),
    transition('out => in', animate('400ms ease-in'))
  ])
  ]
})

export class WorldCupComponent implements OnInit {

  best_team_game:string;
  bonusquestions:Bonusquestions[];
  country1:string;
  country2:string;
  data:any;
  email_address:string;
  error_msg:any;
  flag1:string;
  flag2:string;
  fname:string;
  games:Game[];
  game_id:number;
  golden_boot:string;
  golden_game:string;
  golden_team:string;
  group_id:number;
  guess_id:number;
  itemState1:string;
  itemState2:string;
  itemState3:string;
  itemState4:string;
  introStd:number;
  message_post:string;
  message_id:number;
  modalClass:string;
  participants:Participant[];
  password1:string;
  password2:string;
  players:Player[];
  regStep:number;
  sc_user:boolean;
  subject:string;
  teams:Team[];
  team1:number;
  team2:number;
  user:User;
  user_to:number;
  username_state:any;
  usergroupmap:Usergroupmap[];
  usergroup:any;
  userposts:Userpost[];
  usertoken:string;
  ugscore1:number;
  ugscore2:number;
  update_success:string;
  winner_wc:string;
  worst_team:string;

  constructor(private authService: AuthService, private apiService: ApiDataService) { }

  addtolist(group)
  {
    this.apiService.requestToJoin(group)
    .subscribe(usergroup => this.updateSC(usergroup));
  }

  getBonusQuestions(){
    this.apiService.getBonusQuestions()
    .subscribe(bonusquestions => this.updateUG(bonusquestions));
  }

  getGames(a = ''){
    this.apiService.getGames(a)
      .subscribe(games => this.games = games);
    if(this.modalClass == 'modalOpen'){
       this.modalClass = 'modal';
    }
  }

  getParticipants(id){
    //get the leaderboard
    this.apiService.getParticipants(id)
    .subscribe(participants => this.participants = participants);
    this.messageInit();
  }

  getTeams(){
    this.apiService.getTeams()
    .subscribe(teams => this.teams = teams);
  }

  gotoregStep(a){
    if((this.fname!=undefined) && (a == 2)){
      this.regStep = a;
      this.error_msg = '';
      return;
    }
    else if(a ==2 ){
      this.error_msg = "Please enter a name longer than 2 letters."
      return;
    }
    if((this.email_address!=undefined) && (a == 3)){
      this.regStep = a;
      this.error_msg = '';
      return;
    }else if(a ==3 ){
      this.error_msg = this.fname+", we need an e-mail address. Just enter it above :)"
      return;
    }
    if((this.password1!=undefined) && (this.password2 === this.password1) && (a == 4)){
      this.storeData(a);
      return;
    }else if(a ==4 ){
      this.error_msg = "We almost there, help us keep your account safe. Enter a password and confirm it below."
      return;
    }

  }

  messageInit(){
    this.user_to = 0;
    this.message_id = 0;
    this.subject = '';
    this.message_post = '';
    this.apiService.getUserposts(this.group_id)
    .subscribe(userpost => this.userposts = userpost);
  }

  predictModal(game = undefined){
    if(this.modalClass=='modal'){
      if(game){
        this.country1 = game.country1;
        this.country2 = game.country2;
        this.team1 = game.team1;
        this.team2 = game.team2;
        this.flag1 = game.flag1;
        this.flag2 = game.flag2;
        this.ugscore1 = game.scoreA;
        this.ugscore2 = game.scoreB;
        this.guess_id = game.guess_id;
        this.game_id = game.id;
      }
      this.modalClass = 'modalOpen';
    }else{
      this.modalClass = 'modal';
    }
  }
  
  registerInterest(a){
    this.introStd = a;
  }

  saveMessage(){
    this.data = {
      user_id_to:this.user_to,
      parent_id:this.message_id,
      group_id:this.group_id,
      subject:this.subject,
      message:this.message_post
    }
    if(this.message_post!=''){
      this.apiService.saveMessage(this.data)
      .subscribe(message => this.messageInit());
    }
  }

  saveUserguess(game_id,guess_id){
    this.apiService.saveUserguess(guess_id,game_id,this.team1,this.team2,this.ugscore1,this.ugscore2)
    .subscribe(games => this.getGames(''));
  }

  storeData(a){
    this.data = {
      "fname":this.fname,
      "email":this.email_address,
      'regpoint':'sc',
      "password1":this.password1,
      "password2":this.password2
    }
    this.apiService.registerUser(this.data)
    .subscribe(user => this.saveData(user,'wc'));
  }

  saveData(user,ref){
    if(user!=undefined){
      this.user = user;
      if(user.success){
        this.user = user;
        this.authService.isLoggedIn = true;
        localStorage.removeItem('usertoken');
        localStorage.setItem('usertoken', user.usertoken);
        localStorage.setItem('first_name', user.first_name);
        localStorage.setItem('last_name', user.last_name);
        localStorage.setItem('isLoggedIn', '1');
        this.usertoken = user.usertoken;
        this.regStep = 4;
        this.introStd = 2;
        this.apiService.getUsergroupmap()
        .subscribe(usergroupmap => this.updateSC(usergroupmap));
      }
      else{
        if(ref=='wc'){
          alert(user.msg);
        }
      }
    }
    return false;
  }

  toggleState(a): void {
    if(a === 1){this.itemState1 = this.itemState1 === 'in' ? 'out' : 'in';}
    else if(a === 2){this.itemState2 = this.itemState2 === 'in' ? 'out' : 'in';}
    else if(a === 3){this.itemState3 = this.itemState3 === 'in' ? 'out' : 'in';}
    else if(a === 4){this.itemState4 = this.itemState4 === 'in' ? 'out' : 'in';}
  }

  updateSC(ugm){
    if((ugm.length > 0))
    {
      this.sc_user = true;
      this.usergroupmap = ugm;
      //get the leaderboard
      this.getParticipants(this.usergroupmap[0].group_id);
      this.group_id = this.usergroupmap[0].group_id;
      this.messageInit();
    } 
  }

  updateBonusQ(q_id,ug_id,user_guess){
    this.apiService.saveBonusGuess(q_id,ug_id,user_guess)
      .subscribe(bonusquestion => this.updateSuccess(bonusquestion));
  }

  updateSuccess(bonusquestion){
    this.update_success = "Bonus question updated";
    this.getBonusQuestions()
    setTimeout(()=>{
      this.update_success = "";
    },3000);
  }

  updateUG(bonusquestions){
    if(bonusquestions){
      this.bonusquestions = bonusquestions;
      this.winner_wc = this.bonusquestions[0].user_guess;
      this.golden_boot = this.bonusquestions[1].user_guess;
      this.golden_game = this.bonusquestions[2].user_guess;
      this.best_team_game = this.bonusquestions[3].user_guess;
      this.worst_team = this.bonusquestions[4].user_guess;
      this.golden_team = this.bonusquestions[5].user_guess;
    }
  }

  ngOnInit() {
    this.sc_user = false;
    this.itemState1 = 'out';
    this.itemState2 = 'out';
    this.itemState3 = 'out';
    this.itemState4 = 'out';
    if (localStorage.getItem('isLoggedIn') != '1'){
      this.regStep = 1;
      this.introStd = 1;
    }
    else{
      this.authService.isLoggedIn = true;
      this.fname = localStorage.getItem('first_name');
      this.usergroup = localStorage.getItem('usergroup');
      this.usertoken = localStorage.getItem('usertoken');
      this.regStep = 4;
      this.introStd = 2;
      this.apiService.getUsergroupmap()
      .subscribe(usergroupmap => this.updateSC(usergroupmap));
      this.getBonusQuestions();
      this.apiService.getGames('')
      .subscribe(games => this.games = games);
    }
    this.apiService.getPlayers()
      .subscribe(players => this.players = players);
    this.getTeams();
    this.update_success = "";
    this.modalClass = 'modal';
  }

}
