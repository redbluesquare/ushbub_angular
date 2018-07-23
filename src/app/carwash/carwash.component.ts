import { Component, OnInit } from '@angular/core';
import { User } from '../user';
import { ApiDataService } from '../api-data.service';

@Component({
  selector: 'app-carwash',
  templateUrl: './carwash.component.html',
  styleUrls: ['./carwash.component.css']
})
export class CarwashComponent implements OnInit {

  state:number;
  modalClass:string;
  first_name:string;
  last_name:string;
  car_reg:string;
  email:string;
  user:User;
  requestDate:string;

  constructor(private apiService: ApiDataService) { }

  updateState(a){
    this.state = a;
  }

  selectDate(d){
    this.modalClass = 'selected';
    this.requestDate = d;
  }

  updateUser(user){
    if(user.first_name!=undefined){
      this.state = 1;
    }
    this.first_name = user.first_name;
    this.last_name = user.last_name;
    this.email = user.email;
  }

  ngOnInit() {
    this.state = 0;
    this.apiService.getProfiles()
    .subscribe(user => this.updateUser(user));
  }

}
