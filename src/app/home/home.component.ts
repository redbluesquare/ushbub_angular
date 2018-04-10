import { Component, OnInit } from '@angular/core';
import { User } from '../user';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(private authService: AuthService) { }
  myuser: User[];


  ngOnInit() {

    if (localStorage.getItem('isLoggedIn') == '1'){
      this.authService.isLoggedIn = true;
      return true;
    }
    
  }

}
