import { Component, OnInit } from '@angular/core';
import { User } from '../user';
import { ApiDataService } from '../api-data.service';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {

  constructor(private apiDataService:ApiDataService) { }
  user:User;

  loadUser(user){
    this.user = user;
  }

  ngOnInit() {
    this.apiDataService.getProfiles()
    .subscribe(user => this.loadUser(user));
  }

}
