import { Component, OnInit } from '@angular/core';
import { User } from '../user';
import { Vendor } from '../vendor';
import { AuthService } from '../auth.service';
import { ApiDataService } from '../api-data.service';
import { Town } from '../town';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(private authService: AuthService, private apiDataService: ApiDataService) { }
  myuser: User[];
  searchtown:string;
  location: Town[];
  postcode:string;
  town:string;
  message:string;

  changeTown(){
    this.searchtown = this.town;
    this.town = undefined;
    localStorage.removeItem("town");
    localStorage.removeItem("postcode");
  }
  
  getTown(){
    if(this.searchtown==undefined){
      this.searchtown = '';
    }
    this.apiDataService.getTown(this.searchtown)
    .subscribe(location => this.storeTown(location));
  }

  getTowns(){
    console.log(this.searchtown);
    if(this.searchtown.length < 4){
      //Dont calculate this
    }
    else{
      console.log('where');
    }
  }

  storeTown(location){
    if(location!=undefined){
      this.location = location;
      this.town = location[0].town;
      localStorage.setItem("town",location[0].town);
      localStorage.setItem("postcode",location[0].postcode);
      this.message = location[0].msg;
    }
    
  }
  ngOnInit() {
    if(localStorage.getItem('town')!="undefined"){
      this.town = localStorage.getItem('town');
      this.postcode = localStorage.getItem('postcode');
      this.apiDataService.getProductTypes('','',this.postcode)
      .subscribe(town => this.location = town);
    }else{
      this.town = undefined;
      localStorage.removeItem("town");
      localStorage.removeItem("postcode");
    }

    if (localStorage.getItem('isLoggedIn') == '1'){
      this.authService.isLoggedIn = true;
      return true;
    }
    
  }

}
