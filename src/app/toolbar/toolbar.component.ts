import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-toolbar',
  templateUrl: './toolbar.component.html',
  styleUrls: ['./toolbar.component.css']
})
export class ToolbarComponent implements OnInit {
  
  constructor(public authService: AuthService) {}

  isLoggedIn = this.authService.isLoggedIn;
  image_logo:any;
  logout(){
    this.authService.logout();
    this.isLoggedIn = false;
  }

  ngOnInit() {
    this.image_logo = "assets/images/logo_ushbub.png";
  }

}
