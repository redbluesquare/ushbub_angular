import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-resources',
  templateUrl: './resources.component.html',
  styleUrls: ['./resources.component.css']
})
export class ResourcesComponent implements OnInit {

  constructor(private authService: AuthService) { }

  ngOnInit() {
    //this.authGuardService.checkLogin('');
    if (localStorage.getItem('isLoggedIn') == '1'){
      this.authService.isLoggedIn = true;
      return true;
    }
  }

}
