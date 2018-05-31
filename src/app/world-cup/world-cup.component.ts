import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { trigger, state, style, animate, transition } from '@angular/animations';
import { ApiDataService } from '../api-data.service';
import { User } from '../user';
import { Usergroup } from '../usergroup';
import { Usergroupmap } from '../usergroupmap';
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

  itemState1:string;
  itemState2:string;
  sc_user:boolean;
  introStd:number;
  regStep:number;
  fname:string;
  email_address:string;
  password1:string;
  password2:string;
  error_msg:any;
  username_state:any;
  data:any;
  user:User;
  usergroupmap:Usergroupmap;
  usergroup:any;
  usertoken:string;

  constructor(private authService: AuthService, private apiService: ApiDataService) { }
  
  registerInterest(a){
    this.introStd = a;
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
        this.apiService.getUsergroupmap('2018 Football World Cup',localStorage.getItem('usertoken'))
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
    else{this.itemState2 = this.itemState2 === 'in' ? 'out' : 'in';}
  }

  addtolist(group,)
  {
    this.apiService.requestToJoin(group,this.usertoken)
    .subscribe(usergroup => this.updateSC(usergroup));
  }

  updateSC(ugm){
    if((ugm) && (ugm.title == '2018 Football Worl Cup'))
    {
      this.sc_user = true;
      this.usergroupmap = ugm;
    }
    
  }

  ngOnInit() {
    this.sc_user = false;
    if (localStorage.getItem('isLoggedIn') == '1'){
      this.authService.isLoggedIn = true;
      this.fname = localStorage.getItem('first_name');
      this.usergroup = localStorage.getItem('usergroup');
      this.usertoken = localStorage.getItem('usertoken');
      this.regStep = 4;
      this.introStd = 2;
      this.apiService.getUsergroupmap('2018 Football World Cup',localStorage.getItem('usertoken'))
      .subscribe(usergroupmap => this.updateSC(usergroupmap));

    }
    else{
      this.regStep = 1;
      this.introStd = 1;
      this.itemState1 = 'out';
      this.itemState2 = 'out';
    }
    
  }

}
