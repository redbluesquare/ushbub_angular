import { Component, OnInit } from '@angular/core';
import { Usergroupmap } from '../usergroupmap';
import { ApiDataService } from '../api-data.service';
import { Participant } from '../participant';
import { trigger, state, style, animate, transition } from '@angular/animations';

@Component({
  selector: 'app-score-board',
  templateUrl: './score-board.component.html',
  styleUrls: ['./score-board.component.css'],
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
export class ScoreBoardComponent implements OnInit {

  constructor(private apiService:ApiDataService) { }

  classbox:any;
  country1:string;
  country2:string;
  flag1:string;
  flag2:string;
  group_id:number;
  game_id:number;
  games:any[];
  games2:any[];
  itemState:string;
  modalClass:string;
  participants:Participant[];
  score1:number;
  score2:number;
  secret:string;
  usergroupmap:Usergroupmap[];

  closeModal(){
    this.modalClass = 'modal';
  }

  getAllGames(id){
    //get the leaderboard
    this.apiService.getAllGames(id)
    .subscribe(games => this.games = games);
  }

  getParticipants(id){
    //get the leaderboard
    this.apiService.getParticipants(id)
    .subscribe(participants => this.participants = participants);
  }

  saveGamescore(){
    this.apiService.saveScore(this.game_id,this.score1,this.score2,this.secret)
    .subscribe(games => this.getAllGames(''));
    this.modalClass = 'modal';
  }

  scoreModal(game = undefined){
    if(game){
      this.country1 = game.country1;
      this.country2 = game.country2;
      this.flag1 = game.flag1;
      this.flag2 = game.flag2;
      this.score1 = game.score1;
      this.score2 = game.score2;
      this.game_id = game.id;
    }
    this.modalClass = 'modalOpen';
  }

  updateSC(ugm){
    if((ugm.length > 0))
    {
      this.usergroupmap = ugm;
      //get the leaderboard
      this.getParticipants(this.usergroupmap[0].group_id);
      this.group_id = this.usergroupmap[0].group_id;
    } 
  }

  openBox(id){
    this.itemState = this.itemState === 'in' ? 'out' : 'in';
  }

  ngOnInit() {
    this.apiService.getUsergroupmap()
      .subscribe(usergroupmap => this.updateSC(usergroupmap));
    this.modalClass = 'modal';
    this.itemState = 'out';
    this.getAllGames('');
    this.game_id = 1;
    this. secret= '';
    this.apiService.getAllGames('')
    .subscribe(games => this.games2 = games);
  }

}
