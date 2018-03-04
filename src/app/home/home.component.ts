import { Component, OnInit } from '@angular/core';
import { User } from '../user';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor() { }
  myuser: User[];

  ngOnInit() {
    console.log(this.myuser);
  }

}
